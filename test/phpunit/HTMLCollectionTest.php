<?php
namespace Gt\Dom\Test;

use Gt\Dom\Element;
use Gt\Dom\Exception\HTMLCollectionImmutableException;
use Gt\Dom\Facade\HTMLCollectionFactory;
use Gt\Dom\Facade\NodeListFactory;
use Gt\Dom\Test\TestFactory\NodeTestFactory;
use PHPUnit\Framework\TestCase;

class HTMLCollectionTest extends TestCase {
	public function testLength():void {
		$sut = HTMLCollectionFactory::create(fn() => NodeListFactory::create(
			NodeTestFactory::createNode("example"),
			NodeTestFactory::createNode("example")
		));
		self::assertEquals(2, $sut->length);
	}

	public function testCount():void {
		$sut = HTMLCollectionFactory::create(fn() => NodeListFactory::create(
			NodeTestFactory::createNode("example"),
			NodeTestFactory::createNode("example")
		));
		self::assertCount(2, $sut);
	}

	public function testItem():void {
		$element1 = NodeTestFactory::createNode("example");
		$element2 = NodeTestFactory::createNode("example");
		$sut = HTMLCollectionFactory::create(fn() => NodeListFactory::create(
			$element1,
			$element2
		));
		self::assertSame($element2, $sut->item(1));
	}

	public function testItemNone():void {
		$element1 = NodeTestFactory::createNode("example");
		$element2 = NodeTestFactory::createNode("example");
		$sut = HTMLCollectionFactory::create(fn() => NodeListFactory::create(
			$element1,
			$element2
		));
		self::assertNull($sut->item(2));
	}

	public function testNamedItem():void {
		$element1 = NodeTestFactory::createNode("example");
		$element1->id = "first";
		$element1->setAttribute("name", "abc");
		$element2 = NodeTestFactory::createNode("example");
		$element2->id = "second";
		$element2->setAttribute("name", "xyz");

		$sut = HTMLCollectionFactory::create(fn() => NodeListFactory::create(
			$element1,
			$element2
		));
		self::assertSame($element1, $sut->namedItem("first"));
		self::assertSame($element1, $sut->namedItem("abc"));
		self::assertSame($element2, $sut->namedItem("second"));
		self::assertSame($element2, $sut->namedItem("xyz"));
		self::assertNull($sut->namedItem("nope"));
	}

	public function testOffsetExists():void {
		$sut = HTMLCollectionFactory::create(fn() => NodeListFactory::create(
			NodeTestFactory::createNode("example"),
			NodeTestFactory::createNode("example")
		));
		self::assertTrue(isset($sut[0]));
		self::assertTrue(isset($sut[1]));
		self::assertFalse(isset($sut[2]));
	}

	public function testOffsetSetImmutable():void {
		$sut = HTMLCollectionFactory::create(fn() => NodeListFactory::create(
			NodeTestFactory::createNode("example"),
			NodeTestFactory::createNode("example")
		));
		self::expectException(HTMLCollectionImmutableException::class);
		$sut[0] = "test";
	}

	public function testOffsetUnsetImmutable():void {
		$sut = HTMLCollectionFactory::create(fn() => NodeListFactory::create(
			NodeTestFactory::createNode("example"),
			NodeTestFactory::createNode("example")
		));
		self::expectException(HTMLCollectionImmutableException::class);
		unset($sut[0]);
	}

	public function testIterator():void {
		$sut = HTMLCollectionFactory::create(fn() => NodeListFactory::create(
			NodeTestFactory::createNode("example"),
			NodeTestFactory::createNode("example")
		));
		foreach($sut as $key => $value) {
			self::assertInstanceOf(Element::class, $value);
		}
		self::assertEquals(1, $key);
	}
}
