<?php

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 09/06/16
 * Time: 12:06
 */
class PicasaPhoto
{
	public $id;
	public $title;
	public $width;
	public $height;
	public $url;

	public function __construct($width, $height, $url)
	{
		$this->width = $width;
		$this->height = $height;
		$this->url = $url;
	}

	public function getImageURL($size = null)
	{
		if ($size == null) {
			$size = $this->width > $this->height ? $this->width : $this->height;
		}
		$url_parts = explode('/', $this->url);
		array_splice($url_parts, count($url_parts) - 1, 0, 's' . $size);
		$url = implode('/', $url_parts);
		return $url;
	}

	public function getImage($size = null)
	{
		return '<img src="' . $this->getImageURL($size) . '" alt="' . $this->title . '"/>';
	}

	public function getThumb($size)
	{
		ob_start();
		?>
		<div class="image-thumb-container"
		     style="
			     width: <?php echo $size; ?>;
			     height: <?php echo $size; ?>;
			     padding: 5px;
			     background-image: url(<?php echo $this->getImageURL($size); ?>);
			     background-position: center;
			     background-size: cover;
			     "
		></div>
		<?php
		return ob_get_clean();
	}
}