<?php
/**
 * Supporters for front page
 */
?>

<!-- Styling -->
<style>
.supporters {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  gap: 24px 36px;
  padding: 24px;
  border: 1.5px dashed #78b159;
  border-radius: 10px;
}

.supporter {
  display: block;
  max-width: 150px;
  max-height: 80px;
  height: auto;
  object-fit: contain;
}
</style>

<h5>💚 Tack</h5>

<div class="supporters">
<img src="<?php echo esc_url(LOOPIS_THEME_HQ_URI . '/assets/img/supporters/ps.png'); ?>" class="supporter" style="zoom: 0.9; filter: grayscale(10%);" alt="Postkodlotteriets stiftelse">
<img src="<?php echo esc_url(LOOPIS_THEME_HQ_URI . '/assets/img/supporters/sh.png'); ?>" class="supporter" style="zoom: 1.2;" alt="Stockholmshem">
<img src="<?php echo esc_url(LOOPIS_THEME_HQ_URI . '/assets/img/supporters/kes.png'); ?>" class="supporter" style="zoom: 1.1;" alt="Kollaborativ Ekonomi Sverige">
</div>

<?php
// echo '<img src="' . esc_url(LOOPIS_THEME_HQ_URI . '/assets/img/supporters/on.png') . '" class="supporter" style="zoom: 0.8;" alt="Omställningsnätverket">';
// echo '<img src="' . esc_url(LOOPIS_THEME_HQ_URI . '/assets/img/supporters/aea.png') . '" class="supporter" style="zoom: 0.3;" alt="Access Economy Alliance">';
?>