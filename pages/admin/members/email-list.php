<?php
/**
 * Email list page
 * Tool for extracting email addresses of members
 * Should be improved with filters in the future.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get available roles and the selected role
$available_roles = wp_roles()->get_names();
$selected_role = isset($_GET['role']) ? sanitize_key(wp_unslash($_GET['role'])) : '';
if (!isset($available_roles[$selected_role])) {
    $selected_role = '';
}

$available_subsites = array();
if (is_multisite()) {
    $sites = get_sites(array(
        'number' => 0,
        'archived' => 0,
        'spam' => 0,
        'deleted' => 0,
    ));
    foreach ($sites as $site) {
        $blog_id = (int) $site->blog_id;
        if (!is_main_site($blog_id)) {
            $available_subsites[$blog_id] = get_blog_option($blog_id, 'blogname');
        }
    }
}

$selected_subsite = isset($_GET['subsite']) ? absint(wp_unslash($_GET['subsite'])) : 0;
if (!isset($available_subsites[$selected_subsite])) {
    $selected_subsite = 0;
}

// Fetch users from the selected role and build rows for table/CSV.
$rows = array();
$user_query_args = array(
    'role' => $selected_role,
    'blog_id' => get_current_blog_id(),
    'fields' => array('ID'),
);
if ($selected_subsite) {
    $user_query_args['meta_query'] = array(
        array(
            'key' => 'primary_blog',
            'value' => (string) $selected_subsite,
        ),
    );
}
$users = $selected_role ? get_users($user_query_args) : array();

foreach ($users as $user) {
    $user_id = isset($user->ID) ? (int) $user->ID : 0;
    if ($user_id <= 0) {
        continue;
    }

    $user_data = get_userdata($user_id);
    if (!$user_data || empty($user_data->user_email)) {
        continue;
    }

    $first_name = trim((string) get_user_meta($user_id, 'first_name', true));
    $last_name = trim((string) get_user_meta($user_id, 'last_name', true));
    $name = trim($first_name . ' ' . $last_name);

    $rows[] = array(
        'email' => (string) $user_data->user_email,
        'name' => $name,
    );
}
$total_count = count($rows);

?>

<h1>✉ Epost-adresser</h1>
<hr>
<p class="small">💡 Hämta e-postadresser till medlemmar.</p>

<!-- Role Selection Form -->
<h3>🎚️ Filter</h3>
<hr>
<div class="loopis-form loopis-filter">
<form method="GET" action="">
    <!-- Preserve the view parameter -->
    <input type="hidden" name="view" value="<?php echo esc_attr(isset($_GET['view']) ? $_GET['view'] : ''); ?>">
    <select name="role" id="role" required>
        <option value="" disabled <?php selected($selected_role, ''); ?>>Välj roll</option>
        <?php foreach ($available_roles as $role => $label) : ?>
            <option value="<?php echo esc_attr($role); ?>" <?php selected($role, $selected_role); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select name="subsite" id="subsite">
        <option value="" <?php selected($selected_subsite, 0); ?>>Alla subsites</option>
        <?php foreach ($available_subsites as $blog_id => $blogname) : ?>
            <option value="<?php echo esc_attr($blog_id); ?>" <?php selected($blog_id, $selected_subsite); ?>>
                <?php echo esc_html($blogname); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="green small">Visa</button>
</form>
</div>

<h3>👥 Output</h3>
<div class="columns"><div class="column1">↓ <?php echo (int) $total_count; ?> användare</div>
<div class="column2 small"><a href="#" id="loopis-download-csv">📄Email-List.csv</a></div></div>
<hr>

<?php if (!empty($rows)) : ?>
    <table>
        <tbody>
            <?php foreach ($rows as $row) : ?>
                <tr>
                    <td><?php echo esc_html($row['email']); ?></td>
                    <td><?php echo esc_html($row['name']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>💢 Inga användare</p>
<?php endif; ?>

<script>
(function() {
    var downloadLink = document.getElementById('loopis-download-csv');
    if (!downloadLink) {
        return;
    }

    var rows = <?php echo wp_json_encode($rows); ?>;

    function csvEscape(value) {
        var text = String(value || '');
        return '"' + text.replace(/"/g, '""') + '"';
    }

    downloadLink.addEventListener('click', function(event) {
        event.preventDefault();
        var lines = ['Email,Name'];

        rows.forEach(function(row) {
            lines.push(csvEscape(row.email) + ',' + csvEscape(row.name));
        });

        var csvContent = '\uFEFF' + lines.join('\n');
        var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        var url = URL.createObjectURL(blob);
        var link = document.createElement('a');

        link.href = url;
        link.download = 'member-email-list.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    });
})();
</script>