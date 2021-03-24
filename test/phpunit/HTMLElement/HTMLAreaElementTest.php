<?php
namespace Gt\Dom\Test\HTMLElement;

use Gt\Dom\HTMLElement\HTMLAreaElement;
use Gt\Dom\Test\TestFactory\NodeTestFactory;

class HTMLAreaElementTest extends HTMLElementTestCase {
	public function testAlt():void {
		/** @var HTMLAreaElement $sut */
		$sut = NodeTestFactory::createHTMLElement("area");
		self::assertPropertyAttributeCorrelate($sut, "alt");
	}
}