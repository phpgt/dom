<?php
namespace Gt\Dom\Test;

use Gt\Dom\Facade\TreeWalkerFactory;
use Gt\Dom\Node;
use Gt\Dom\NodeFilter;
use Gt\Dom\Test\TestFactory\DocumentTestFactory;
use Gt\Dom\Test\TestFactory\NodeTestFactory;
use PHPUnit\Framework\TestCase;

class TreeWalkerTest extends TestCase {
	public function testRoot():void {
		$root = self::createMock(Node::class);
		$sut = TreeWalkerFactory::create($root);
		self::assertSame($root, $sut->root);
	}

	public function testWhatToShow():void {
		$whatToShow = NodeFilter::SHOW_DOCUMENT_FRAGMENT;
		$sut = TreeWalkerFactory::create(
			self::createMock(Node::class),
			$whatToShow
		);
		self::assertSame($whatToShow, $sut->whatToShow);
	}

	public function testFilterFromCallable():void {
		$callCount = 0;
		$callable = function(Node $node) use(&$callCount) {
			$callCount++;
			return $callCount;
		};
		$root = self::createMock(Node::class);
		$sut = TreeWalkerFactory::create(
			$root,
			0,
			$callable
		);
		$nodeFilter = $sut->filter;
		$accept = $nodeFilter->acceptNode($root);
		self::assertEquals(1, $callCount);
		self::assertEquals(1, $accept);
	}

	public function testParentNode():void {
		$root = NodeTestFactory::createNode("root");
		$sut = TreeWalkerFactory::create($root);
		self::assertNull($sut->parentNode());
		self::assertSame($root, $sut->currentNode);
	}

	public function testFirstChildNone():void {
		$root = NodeTestFactory::createNode("root");
		$sut = TreeWalkerFactory::create($root);
		self::assertNull($sut->firstChild());
		self::assertSame($root, $sut->currentNode);
	}

	public function testFirstChild():void {
		$root = NodeTestFactory::createNode("root");
		$child1 = $root->ownerDocument->createElement("child");
		$child2 = $root->ownerDocument->createElement("child");
		$child3 = $root->ownerDocument->createElement("child");
		$root->append($child1, $child2, $child3);
		$sut = TreeWalkerFactory::create($root);
		self::assertSame($child1, $sut->firstChild());
		self::assertSame($child1, $sut->currentNode);
	}

	public function testLastChildNone():void {
		$root = NodeTestFactory::createNode("root");
		$sut = TreeWalkerFactory::create($root);
		self::assertNull($sut->lastChild());
		self::assertSame($root, $sut->currentNode);
	}

	public function testLastChild():void {
		$root = NodeTestFactory::createNode("root");
		$child1 = $root->ownerDocument->createElement("child");
		$child2 = $root->ownerDocument->createElement("child");
		$child3 = $root->ownerDocument->createElement("child");
		$root->append($child1, $child2, $child3);
		$sut = TreeWalkerFactory::create($root);
		self::assertSame($child3, $sut->lastChild());
		self::assertSame($child3, $sut->currentNode);
	}

	public function testPreviousSiblingNone():void {
		$root = NodeTestFactory::createNode("root");
		$root->ownerDocument->appendChild($root);
		$sut = TreeWalkerFactory::create($root);
		self::assertNull($sut->previousSibling());
		self::assertSame($root, $sut->currentNode);
	}

	public function testPreviousSibling():void {
		$root = NodeTestFactory::createNode("root");
		$root->ownerDocument->appendChild($root);
		$other1 = $root->ownerDocument->createElement("other");
		$other2 = $root->ownerDocument->createElement("other");
		$other3 = $root->ownerDocument->createElement("other");
		$root->append($other1, $other2, $other3);
		$sut = TreeWalkerFactory::create($other2);
		self::assertSame($other1, $sut->previousSibling());
		self::assertSame($other1, $sut->currentNode);
	}

	public function testNextSiblingNone():void {
		$root = NodeTestFactory::createNode("root");
		$root->ownerDocument->appendChild($root);
		$sut = TreeWalkerFactory::create($root);
		self::assertNull($sut->nextSibling());
		self::assertSame($root, $sut->currentNode);
	}

	public function testNextSibling():void {
		$root = NodeTestFactory::createNode("root");
		$root->ownerDocument->appendChild($root);
		$other1 = $root->ownerDocument->createElement("other");
		$other2 = $root->ownerDocument->createElement("other");
		$other3 = $root->ownerDocument->createElement("other");
		$root->append($other1, $other2, $other3);
		$sut = TreeWalkerFactory::create($other2);
		self::assertSame($other3, $sut->nextSibling());
		self::assertSame($other3, $sut->currentNode);
	}

	public function testPreviousNodeNone():void {
		$root = NodeTestFactory::createNode("root");
		$sut = TreeWalkerFactory::create($root);
		self::assertNull($sut->previousNode());
		self::assertSame($root, $sut->currentNode);
	}

	public function testPreviousNode():void {
		$root = NodeTestFactory::createNode("root");
		$root->ownerDocument->appendChild($root);
		$other1 = $root->ownerDocument->createElement("other");
		$other2 = $root->ownerDocument->createElement("other");
		$other3 = $root->ownerDocument->createElement("other");
		$root->append($other1, $other2, $other3);
		$sut = TreeWalkerFactory::create($other3);
		self::assertSame($other2, $sut->previousNode());
		self::assertSame($other1, $sut->previousNode());
		self::assertNull($sut->previousNode());
		self::assertSame($other1, $sut->currentNode);
	}

	public function testNextNodeNode():void {
		$root = NodeTestFactory::createNode("root");
		$sut = TreeWalkerFactory::create($root);
		self::assertNull($sut->nextNode());
		self::assertSame($root, $sut->currentNode);
	}

	public function testNextNode():void {
		$root = NodeTestFactory::createNode("root");
		$root->ownerDocument->appendChild($root);
		$other1 = $root->ownerDocument->createElement("other");
		$other2 = $root->ownerDocument->createElement("other");
		$other3 = $root->ownerDocument->createElement("other");
		$root->append($other1, $other2, $other3);
		$sut = TreeWalkerFactory::create($other1);
		self::assertSame($other2, $sut->nextNode());
		self::assertSame($other3, $sut->nextNode());
		self::assertNull($sut->nextNode());
		self::assertSame($other3, $sut->currentNode);
	}

	public function testIteration():void {
		$document = DocumentTestFactory::createHTMLDocument(DocumentTestFactory::HTML_IMAGES);
		$sut = TreeWalkerFactory::create($document->body);
		$collectedNodes = [];
		foreach($sut as $node) {
			array_push($collectedNodes, $node);
		}

		self::assertSame($collectedNodes[1], $document->querySelector("h1"));
	}
}
