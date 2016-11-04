<?php

namespace Gregwar\Image\Adapter;

use Gregwar\Image\Image;
use Imagick as ImageMagick;

class Imagick extends Common{

	protected $iccProfile = null;

	protected function loadResource($resource)
	{
		parent::loadResource($resource);
	}

	public function __construct(){
		parent::__construct();

		if (!(extension_loaded('imagick'))) {
			throw new \RuntimeException('You need to install ImageMagick PHP Extension to use this library');
		}
	}

	/**
	 * Gets the name of the adapter
	 *
	 * @return string
	 */
	public function getName(){
		return 'ImageMagick';
	}

	/**
	 * Image width
	 *
	 * @return int
	 */
	public function width(){
		if (null === $this->resource) {
			$this->init();
		}

		return $this->resource->getImageWidth();
	}

	/**
	 * Image height
	 *
	 * @return int
	 */
	public function height(){
		if (null === $this->resource) {
			$this->init();
		}

		return $this->resource->getImageHeight();
	}

	/**
	 * Does this adapter supports the given type ?
	 */
	protected function supports($type){
		return true;
	}

	protected function doSave(){
		$this->resource->transformImageColorSpace(ImageMagick::COLORSPACE_SRGB);
		$this->resource->stripImage();

		$this->resource->profileImage('icc', $this->iccProfile);
	}

	/**
	 * Save the image as a gif
	 *
	 * @return $this
	 */
	public function saveGif($file){
		$this->doSave();
		$this->resource->setImageFormat("gif");

		if (null == $file) {
			echo $this->resource->getImageBlob();
		} else {
			$this->resource->writeImage($file);
		}

		return $this;
	}

	/**
	 * Save the image as a png
	 *
	 * @return $this
	 */
	public function savePng($file){
		$this->doSave();
		$this->resource->setImageFormat("png");

		if (null == $file) {
			echo $this->resource->getImageBlob();
		} else {
			$this->resource->writeImage($file);
		}

		return $this;
	}

	/**
	 * Save the image as a jpeg
	 *
	 * @return $this
	 */
	public function saveJpeg($file, $quality){
		$this->doSave();

		// remove alpha channel
		try {
			if ($this->resource->getImageAlphaChannel()) {
				$this->resource->setImageBackgroundColor('white');
				$this->resource->setImageAlphaChannel(ImageMagick::ALPHACHANNEL_DEACTIVATE);
			}
		} catch (\Exception $e) {
			// ignore error and continue.
		}

		$this->resource->setCompression(ImageMagick::COMPRESSION_JPEG);
		$this->resource->setImageCompressionQuality($quality);
		$this->resource->setImageFormat("jpeg");

		if (null == $file) {
			echo $this->resource->getImageBlob();
		} else {
			$this->resource->writeImage($file);
		}

		return $this;
	}

	public function clear(){
		$this->resource->clear();
		return $this;
	}

	/**
	 * Crops the image
	 *
	 * @param int $x the top-left x position of the crop box
	 * @param int $y the top-left y position of the crop box
	 * @param int $width the width of the crop box
	 * @param int $height the height of the crop box
	 *
	 * @return $this
	 */
	public function crop($x, $y, $width, $height){
		$this->resource->cropImage($width, $height, $x, $y);
		return $this;
	}

	/**
	 * Fills the image background to $bg if the image is transparent
	 *
	 * @param int $background background color
	 *
	 * @return $this
	 */
	public function fillBackground($background = 0xffffff){
		// $w = $this->width();
  //       $h = $this->height();
  //       $n = new ImageMagick();
		// $n->newimage($width, $height);

  //       imagefill($n, 0, 0, ImageColor::gdAllocate($this->resource, $background));
  //       imagecopyresampled($n, $this->resource, 0, 0, 0, 0, $w, $h, $w, $h);
  //       imagedestroy($this->resource);
  //       $this->resource = $n;

  //       return $this;
	}

	/**
	 * Negates the image
	 *
	 * @return $this
	 */
	public function negate(){
		$this->resource->negateImage(true);
		return $this;
	}

	/**
	 * Changes the brightness of the image
	 *
	 * @param int $brightness the brightness
	 *
	 * @return $this
	 */
	public function brightness($brightness){
		// TODO: Implement brightness() method.
	}

	/**
	 * Contrasts the image
	 *
	 * @param int $contrast the contrast [-100, 100]
	 *
	 * @return $this
	 */
	public function contrast($contrast){
		$this->resource->contrastImage($contrast);
		return $this;
	}

	/**
	 * Apply a grayscale level effect on the image
	 *
	 * @return $this
	 */
	public function grayscale(){
		// TODO: Implement grayscale() method.
	}

	/**
	 * Emboss the image
	 *
	 * @return $this
	 */
	public function emboss(){
		$this->resource->embossImage(0, 1);
		return $this;
	}

	/**
	 * Smooth the image
	 *
	 * @param int $p value between [-10,10]
	 *
	 * @return $this
	 */
	public function smooth($p){
		$this->resource->blurImage($p, 1);
		return $this;
	}

	/**
	 * Sharps the image
	 *
	 * @return $this
	 */
	public function sharp(){
		$this->resource->sharpenImage(0, 1);
		return $this;
	}

	/**
	 * Sharpens the image using and unsharp mask
	 *
	 * @return $this
	 */
	public function unsharp($radius, $sigma, $amount, $threshold){
		$this->resource->unsharpMaskImage((float)$radius, (float)$sigma, (float)$amount, (float)$threshold);
		return $this;
	}

	/**
	 * Edges the image
	 *
	 * @return $this
	 */
	public function edge(){
		$this->resource->edgeImage(0);
		return $this;
	}

	/**
	 * Colorize the image
	 *
	 * @param int $red value in range [-255, 255]
	 * @param int $green value in range [-255, 255]
	 * @param int $blue value in range [-255, 255]
	 *
	 * @return $this
	 */
	public function colorize($red, $green, $blue){
		// TODO: Implement colorize() method.
	}

	/**
	 * apply sepia to the image
	 *
	 * @return $this
	 */
	public function sepia(){
		// TODO: Implement sepia() method.
	}

	/**
	 * Merge with another image
	 *
	 * @param Image $other
	 * @param int $x
	 * @param int $y
	 * @param int $width
	 * @param int $height
	 *
	 * @return $this
	 */
	public function merge(Image $other, $x = 0, $y = 0, $width = null, $height = null){
		// TODO: Implement merge() method.
	}

	/**
	 * Rotate the image
	 *
	 * @param float $angle
	 * @param int $background
	 *
	 * @return $this
	 */
	public function rotate($angle, $background = 0xffffff){
		// TODO: Implement rotate() method.
	}

	/**
	 * Fills the image
	 *
	 * @param int $color
	 * @param int $x
	 * @param int $y
	 *
	 * @return $this
	 */
	public function fill($color = 0xffffff, $x = 0, $y = 0){
		// TODO: Implement fill() method.
	}

	/**
	 * write text to the image
	 *
	 * @param string $font
	 * @param string $text
	 * @param int $x
	 * @param int $y
	 * @param int $size
	 * @param int $angle
	 * @param int $color
	 * @param string $align
	 */
	public function write($font, $text, $x = 0, $y = 0, $size = 12, $angle = 0, $color = 0x000000, $align = 'left'){
		// TODO: Implement write() method.
	}

	/**
	 * Draws a rectangle
	 *
	 * @param int $x1
	 * @param int $y1
	 * @param int $x2
	 * @param int $y2
	 * @param int $color
	 * @param bool $filled
	 *
	 * @return $this
	 */
	public function rectangle($x1, $y1, $x2, $y2, $color, $filled = false){
		// TODO: Implement rectangle() method.
	}

	/**
	 * Draws a rounded rectangle
	 *
	 * @param int $x1
	 * @param int $y1
	 * @param int $x2
	 * @param int $y2
	 * @param int $radius
	 * @param int $color
	 * @param bool $filled
	 *
	 * @return $this
	 */
	public function roundedRectangle($x1, $y1, $x2, $y2, $radius, $color, $filled = false){
		// TODO: Implement roundedRectangle() method.
	}

	/**
	 * Draws a line
	 *
	 * @param int $x1
	 * @param int $y1
	 * @param int $x2
	 * @param int $y2
	 * @param int $color
	 *
	 * @return $this
	 */
	public function line($x1, $y1, $x2, $y2, $color = 0x000000){
		// TODO: Implement line() method.
	}

	/**
	 * Draws an ellipse
	 *
	 * @param int $cx
	 * @param int $cy
	 * @param int $width
	 * @param int $height
	 * @param int $color
	 * @param bool $filled
	 *
	 * @return $this
	 */
	public function ellipse($cx, $cy, $width, $height, $color = 0x000000, $filled = false){
		// TODO: Implement ellipse() method.
	}

	/**
	 * Draws a circle
	 *
	 * @param int $cx
	 * @param int $cy
	 * @param int $r
	 * @param int $color
	 * @param bool $filled
	 *
	 * @return $this
	 */
	public function circle($cx, $cy, $r, $color = 0x000000, $filled = false){
		// TODO: Implement circle() method.
	}

	/**
	 * Draws a polygon
	 *
	 * @param array $points
	 * @param int $color
	 * @param bool $filled
	 *
	 * @return $this
	 */
	public function polygon(array $points, $color, $filled = false){
		// TODO: Implement polygon() method.
	}

	/**
	 *  @inheritdoc
	 */
	public function flip($flipVertical, $flipHorizontal) {
		// TODO: Implement flip method
	}

	protected function doOpen($file){
		$this->resource = new ImageMagick();
		$this->resource->setResolution(72, 72);
		$this->resource->readImage($file);

		$profiles = $this->resource->getImageProfiles('icc');
		if (array_key_exists('icc', $profiles)) {
			$this->iccProfile = $profiles['icc'];
		}
	}

	/**
	 * Opens the image
	 */
	protected function openGif($file){
		$this->doOpen($file);
	}

	protected function openJpeg($file){
		$this->doOpen($file);
	}

	protected function openPng($file){
		$this->doOpen($file);
	}

	protected function doCreate($width, $height){
		$n = new ImageMagick();
		$n->setResolution(72, 72);
		$n->newimage($width, $height, 'none');
		return $n;
	}

	/**
	 * Creates an image
	 */
	protected function createImage($width, $height){
		$this->resource = $this->doCreate($width, $height);
	}

	/**
	 * Creating an image using $data
	 */
	protected function createImageFromData($data){
		$this->resource = new ImageMagick();
		$this->resource->readImageBlob($data);
		return $this;
	}

	/**
	 * Resizes the image to an image having size of $target_width, $target_height, using
	 * $new_width and $new_height and padding with $bg color
	 */
	protected function doResize($bg, $target_width, $target_height, $new_width, $new_height){
		$this->resource->setImageBackgroundColor(new \ImagickPixel($bg));
		$this->resource->thumbnailImage($new_width, $new_height);
		$this->resource->extentImage($target_width, $target_height, ($target_width-$new_width)/2, ($target_height-$new_height)/2);

		return $this;
	}

	/**
	 * Gets the color of the $x, $y pixel
	 */
	protected function getColor($x, $y){
		// TODO: Implement getColor() method.
	}
}
