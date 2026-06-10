<?php
/**
 * Map for front page
 */
?>

<div class="frontpage-map">
            <div class="frontpage-map__legend">
                <h3>🗺 Karta</h3>
                <hr>
                <p>❤ Här finns LOOPIS</p>
                <p>🧡 Här öppnar snart LOOPIS</p>
                <!--p>💚 Här finns intresse för LOOPIS</p-->
            </div>
            <img src="<?php echo esc_url(LOOPIS_THEME_HQ_URI . '/assets/img/map_sweden.svg'); ?>" alt="Sverige" class="sweden-map">
        </div><!-- frontpage-map -->



<!-- Extra styling for map-->
<style>
.frontpage-map {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 0 5px;
}

.frontpage-map__legend {
  position: absolute;
  top: 18px;
  left: 18px;
  z-index: 1;
  padding: 10px 12px;
  background: rgba(255, 255, 255, 0.92);
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  text-align: left;
}

.frontpage-map__legend p {
  margin: 0 0 6px 0;
  font-size: 0.95em;
  line-height: 1.35;
}

.frontpage-map__legend p:last-child {
  margin-bottom: 0;
}

.sweden-map {
  width: auto;
  max-width: 100%;
  max-height: 80vh;
  display: block;
}
</style>