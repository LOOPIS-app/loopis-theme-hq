<?php
/**
 * Email list page
 * Tool for extracting email addresses of members
 * Should be improved with filters in the future.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define available roles
$available_roles = array(
    'member' => 'Nuvarande medlemmar',
    'member_earlier' => 'Tidigare medlemmar',
);

// Get selected roles from GET or default to all
$selected_roles = isset($_GET['roles']) ? (array) $_GET['roles'] : array_keys($available_roles);
$selected_roles = array_map('sanitize_key', $selected_roles);
$selected_roles = array_intersect($selected_roles, array_keys($available_roles));
if (empty($selected_roles)) {
    $selected_roles = array_keys($available_roles);
}

// Fetch unique users from selected roles and build rows for table/CSV.
$users_by_id = array();
$rows = array();
$role_counts = array();

foreach ($selected_roles as $role) {
    $users = get_users(array(
        'role' => $role,
        'fields' => array('ID'),
    ));

    $role_counts[$role] = count($users);

    foreach ($users as $user) {
        $user_id = isset($user->ID) ? (int) $user->ID : 0;
        if ($user_id <= 0 || isset($users_by_id[$user_id])) {
            continue;
        }

        $user_data = get_userdata($user_id);
        if (!$user_data || empty($user_data->user_email)) {
            continue;
        }

        $first_name = trim((string) get_user_meta($user_id, 'first_name', true));
        $last_name = trim((string) get_user_meta($user_id, 'last_name', true));
        $name = trim($first_name . ' ' . $last_name);

        $users_by_id[$user_id] = true;
        $rows[] = array(
            'email' => (string) $user_data->user_email,
            'name' => $name,
        );
    }
}

?>

<h1>✉ Epost-adresser</h1>
<hr>
<p class="small">💡 Verktyg för att plocka ut e-postadresser till medlemmar.</p>

<!-- Role Selection Form -->
<form method="GET" action="" style="margin-bottom: 20px;">
    <!-- Preserve the view parameter -->
    <input type="hidden" name="view" value="<?php echo esc_attr(isset($_GET['view']) ? $_GET['view'] : ''); ?>">
    <label for="roles">Välj roller:</label><br>
    <?php foreach ($available_roles as $role => $label) : ?>
        <label style="display: inline-block; margin-right: 15px; margin-bottom: 10px;">
            <input type="checkbox" name="roles[]" value="<?php echo esc_attr($role); ?>" 
                <?php checked(in_array($role, $selected_roles)); ?>>
            <?php echo esc_html($label); ?>
        </label>
    <?php endforeach; ?>
    <br>
    <button type="submit" class="green small" style="margin-top: 10px;">Hämta epostadresser</button>
</form>

<p style="margin-bottom: 20px;">
    <button type="button" id="loopis-download-csv" class="small">Ladda ner CSV</button>
</p>

<?php
$total_count = count($rows);

echo '<p><strong>Antal medlemmar:</strong><br>';
foreach ($role_counts as $role => $count) {
    echo '• ' . esc_html($available_roles[$role]) . ': ' . (int) $count . '<br>';
}
echo '• Totalt (unika): ' . (int) $total_count . '</p>';
echo '<hr>';

if (empty($rows)) {
    echo '<p>Inga medlemmar hittades för valt urval.</p>';
    return;
}
?>

<table class="widefat striped" style="max-width: 100%;">
    <thead>
        <tr>
            <th>Email</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row) : ?>
            <tr>
                <td><?php echo esc_html($row['email']); ?></td>
                <td><?php echo esc_html($row['name']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
(function() {
    var button = document.getElementById('loopis-download-csv');
    if (!button) {
        return;
    }

    var rows = <?php echo wp_json_encode($rows); ?>;

    function csvEscape(value) {
        var text = String(value || '');
        return '"' + text.replace(/"/g, '""') + '"';
    }

    button.addEventListener('click', function() {
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