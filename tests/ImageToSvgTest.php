<?php

namespace Spatie\PdfToImage\Test;

use PHPUnit\Framework\TestCase;

class ImageToSvgTest extends TestCase
{
	/** @var array */
	protected $testFiles;

	public function setUp(): void
	{
		parent::setUp();

		$this->testFiles[0] = __DIR__.'/files/g1.gif';
		$this->testFiles[1] = __DIR__.'/files/j1.jpg';
		$this->testFiles[2] = __DIR__.'/files/p1.png';
	}
	
	public function testExample()
	{
		return $this->assertTrue(true);
	}
}