<?php

namespace Programeriss\Pix2svg;

use DOMDocument;
use DOMImplementation;
use InvalidArgumentException;

class Pix2svg
{
	protected $image;
	protected $width;
	protected $height;
	protected $threshold = 0;
	
	public function convert($pathToImage)
	{
		$fgc = file_get_contents($pathToImage);
		$this->image  = imagecreatefromstring($fgc);
		$this->width  = imagesx($this->image);
		$this->height = imagesy($this->image);
		
		$svgh = $this->createSvgDocument();
		for ($y = 0; $y < $this->height; ++$y) {
			$number_of_consecutive_pixels = 1;
			for ($x = 0; $x < $this->width; $x = $x + $number_of_consecutive_pixels) {
				$number_of_consecutive_pixels = $this->createLine($svgh, $x, $y, true);
			}
		}
		
		$svg = $this->createSvgDocument();
		for ($x = 0; $x < $this->width; ++$x) {
			$number_of_consecutive_pixels = 1;
			for ($y = 0; $y < $this->height; $y = $y + $number_of_consecutive_pixels) {
				$number_of_consecutive_pixels = $this->createLine($svg, $x, $y, false);
			}
		}
		
		if ($svgh->getElementsByTagName('rect')->length < $svg->getElementsByTagName('rect')->length) {
			$svg = $svgh;
		}
		
		return $svg->saveXML($svg->documentElement);
	}
	
	private function createSvgDocument()
	{
		$imp = new DOMImplementation();
		$dom = $imp->createDocument(
			null,
			'svg',
			$imp->createDocumentType(
				'svg',
				'-//W3C//DTD SVG 1.1//EN',
				'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd'
			)
		);
		$dom->encoding     = 'UTF-8';
		$dom->formatOutput = true;
		$dom->documentElement->setAttribute('xmlns', 'http://www.w3.org/2000/svg');
		$dom->documentElement->setAttribute('shape-rendering', 'crispEdges');
		$dom->documentElement->setAttribute('width', $this->width);
		$dom->documentElement->setAttribute('height', $this->height);
		$dom->documentElement->setAttribute('viewBox', '0 0 '.$this->width.' '.$this->height);
		
		return $dom;
	}
	
	private function createLine(DOMDocument $svg, $x, $y, $action)
	{
		$rgba  = imagecolorsforindex($this->image, imagecolorat($this->image, $x, $y));
		$delta = 1;
		while ($this->isSimilarPixel($rgba, $x, $y, $delta, $action)) {
			++$delta;
		}
		$this->createRectElement($svg, $rgba, $x, $y, $delta, $action);
		
		return $delta;
	}
	
	private function isSimilarPixel($rgba, $x, $y, $delta, $action)
	{
		if ($action) {
			$res = $x + $delta;
			return $res < $this->width && ($rgba ==  imagecolorsforindex($this->image, imagecolorat($this->image, $res, $y)));
		}
		
		$res = $y + $delta;
		
		return $res < $this->height && ($rgba ==  imagecolorsforindex($this->image, imagecolorat($this->image, $x, $res)));
	}
	
	private function createRectElement(DOMDocument $svg, array $rgba, $x, $y, $width, $action)
	{
		$rectWidth  = $width;
		$rectHeight = 1;
		if (!$action) {
			$rectWidth  = 1;
			$rectHeight = $width;
		}
		$rect = $svg->createElement('rect');
		$rect->setAttribute("x", $x);
		$rect->setAttribute("y", $y);
		$rect->setAttribute("width", $rectWidth);
		$rect->setAttribute("height", $rectHeight);
		$rect->setAttribute("fill", "rgb({$rgba['red']},{$rgba['green']},{$rgba['blue']})");
		$alpha = filter_var($rgba["alpha"], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 128]]);
		if ($alpha > 0) {
			$rect->setAttribute("fill-opacity", (128 - $alpha) / 128);
		}
		$svg->documentElement->appendChild($rect);
	}
}
