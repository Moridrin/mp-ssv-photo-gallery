<?php
/**
 * Plugin Name: SSV Picasa Albums
 * Plugin URI: http://studentensurvival.com/plugins/mp-ssv-picasa-albums
 * Description: SSV Picasa Albums is a plugin made to display picasa albums in WordPress.
 * Version: 1.0
 * Author: Jeroen Berkvens
 * Author URI: http://nl.linkedin.com/in/jberkvens/
 * License: WTFPL
 * License URI: http://www.wtfpl.net/txt/copying/
 */

include_once "picasa.php";
include_once "PicasaPhoto.php";

function mp_ssv_picasa_test($content)
{
	if (strpos($content, '[mp-ssv-picasa-album user={') !== false) {
		$user_id = explode('}', explode('[mp-ssv-picasa-album user={', $content)[1])[0];
		$content = str_replace(' user={' . $user_id . '}', '', $content);
		$album_id = explode('}', explode('[mp-ssv-picasa-album album={', $content)[1])[0];
		$content = str_replace(' album={' . $album_id . '}', '', $content);
		$key = null;
		if (strpos($content, '[mp-ssv-picasa-album key={') !== false) {
			$key = explode('}', explode('[mp-ssv-picasa-album key={', $content)[1])[0];
			$content = str_replace(' key={' . $key . '}', '', $content);
		}
		$size = 100;
		if (strpos($content, '[mp-ssv-picasa-album thumb_size={') !== false) {
			$size = explode('}', explode('[mp-ssv-picasa-album thumb_size={', $content)[1])[0];
			$content = str_replace(' thumb_size={' . $size . '}', '', $content);
		}
		$album = picasa::getImages($user_id, $album_id, $key);
		ob_start();
		foreach ($album as $photo) {
			?>
			<a href="<?php echo $photo->getImageURL(); ?>" class="fancybox-thumb" rel="fancybox-thumb" title="<?php echo $photo->title; ?>">
				<?php echo $photo->getThumb($size); ?>
			</a>
			<?php
		}
		echo '<div style="clear:both"></div>';
		$content = str_replace('[mp-ssv-picasa-album]', ob_get_clean(), $content);
	}
	return $content;
}

add_filter('the_content', 'mp_ssv_picasa_test');

function mp_ssv_fancybox()
{
	?>
	<script type="text/javascript">
		jQuery(document).ready(function () {
			jQuery(".fancybox-thumb").fancybox({
				prevEffect: 'none',
				nextEffect: 'none',
				helpers: {
					title: {
						type: 'inside'
					},
					thumbs: {
						width: 50,
						height: 50
					},
					buttons: {}
				}
			});
		});
	</script>
	<?php
}

add_action('wp_head', 'mp_ssv_fancybox');

wp_enqueue_script("jquery");

//Thumbs Style
wp_enqueue_style("mpssv.thumbs", WP_PLUGIN_URL . "/mp-ssv-picasa-albums/include/thumbs.css", false);

//FancyBox Main Style
wp_enqueue_style("jquery.fancybox", WP_PLUGIN_URL . "/mp-ssv-picasa-albums/include/FancyBox/source/jquery.fancybox.css", false, "2.1.5");

//FancyBox Helpers Buttons Style
wp_enqueue_style("jquery.fancyboxbuttons", WP_PLUGIN_URL . "/mp-ssv-picasa-albums/include/FancyBox/source/helpers/jquery.fancybox-buttons.css", false, "2.1.5");

//FancyBox Helpers Thumbs Style
wp_enqueue_style("jquery.fancyboxthumbs", WP_PLUGIN_URL . "/mp-ssv-picasa-albums/include/FancyBox/source/helpers/jquery.fancybox-thumbs.css", false, "2.1.5");

//FancyBox Main Pack
wp_enqueue_script("jquery.fancybox", WP_PLUGIN_URL . "/mp-ssv-picasa-albums/include/FancyBox/source/jquery.fancybox.pack.js", array("jquery"), "2.1.5", 1);

//FancyBox Lib Mousewheel
wp_enqueue_script("jquery.fancyboxmousewheel", WP_PLUGIN_URL . "/mp-ssv-picasa-albums/include/FancyBox/lib/jquery.mousewheel-3.0.6.pack.js", "3.0.6", 1);

//FancyBox Helpers Buttons
wp_enqueue_script("jquery.fancyboxbuttons", WP_PLUGIN_URL . "/mp-ssv-picasa-albums/include/FancyBox/source/helpers/jquery.fancybox-buttons.js", array("jquery"), "1.0.6", 1);

//FancyBox Helpers Media
wp_enqueue_script("jquery.fancyboxmedia", WP_PLUGIN_URL . "/mp-ssv-picasa-albums/include/FancyBox/source/helpers/jquery.fancybox-media.js", array("jquery"), "1.0.6", 1);

//FancyBox helpers Thumbs
wp_enqueue_script("jquery.fancyboxthumbs", WP_PLUGIN_URL . "/mp-ssv-picasa-albums/include/FancyBox/source/helpers/jquery.fancybox-thumbs.js", array("jquery"), "1.0.6", 1);
