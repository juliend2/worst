<?php

require_once('simpletest/unit_tester.php');
require_once('simpletest/web_tester.php');
require_once('simpletest/reporter.php');
require_once('../lib/nodes.php');
require_once('../lib/parser.php');

class ParserTest extends UnitTestCase {

  function setUp() {
    $this->parser = new Parser();
  }

  function testNodes() {
    print_r(v('joie', 'joie'));
    $this->assertIsA(v('variable', 'valeur'), "SetNode");
    $this->assertIsA(v('variable'), "GetNode");
    $this->assertIsA(math('/', 2, 4), "MathNode");
    $this->assertIsA(lambda('name', 2), "LambdaNode");
    $this->assertIsA(lambda('name', v('var', 'val'), v('var')), "LambdaNode");
    $this->assertIsA(lambda('name', array('arg1'), v('arg1')), "LambdaNode");
    $this->assertIsA(lambda('name', array('arg1', 'arg2'), v('var', v('arg1')), v('arg2')), "LambdaNode");
  }

}

$test = new ParserTest();
$test->run(new HtmlReporter('utf-8'));

