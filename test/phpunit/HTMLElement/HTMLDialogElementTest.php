<?php
namespace Gt\Dom\Test\HTMLElement;

use Gt\Dom\HTMLElement\HTMLDialogElement;
use Gt\Dom\Test\TestFactory\NodeTestFactory;

class HTMLDialogElementTest extends HTMLElementTestCase {
	public function testOpenDefault():void {
		/** @var HTMLDialogElement $sut */
		$sut = NodeTestFactory::createHTMLElement("dialog");
		self::assertFalse($sut->open);
	}

	public function testOpen():void {
		/** @var HTMLDialogElement $sut */
		$sut = NodeTestFactory::createHTMLElement("dialog");
		$sut->open = true;
		self::assertTrue($sut->open);
		self::assertTrue($sut->hasAttribute("open"));

		$sut->open = false;
		self::assertFalse($sut->open);
		self::assertFalse($sut->hasAttribute("open"));
	}
}
