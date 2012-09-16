<?php

require_once('simpletest/unit_tester.php');
require_once('simpletest/web_tester.php');
require_once('simpletest/reporter.php');
require_once('../lib/nodes.php');
require_once('../lib/parser.php');

class ParserTest extends UnitTestCase {

  function setUp() {
    $this->p = new Parser();
  }

  function testNodes() {
    $this->assertIsA(v('variable', 'valeur'), "SetNode");
    $this->assertIsA(v('variable'), "GetNode");
    $this->assertIsA(math('/', 2, 4), "MathNode");
    $this->assertIsA(lambda(2), "LambdaNode");
    $this->assertIsA(lambda(v('var', 'val'), v('var')), "LambdaNode");
    $this->assertIsA(lambda(array('arg1'), v('arg1')), "LambdaNode");
    $this->assertIsA(lambda(array('arg1', 'arg2'), v('var', v('arg1')), v('arg2')), "LambdaNode");
    $this->assertIsA(call('name', v('arg1'), 3), 'CallNode');
    $this->assertIsA(puts(3), 'PutsNode');
  }

  function testResults() {
    $this->assertEqual($this->p->parse(array(v('joie', 3), v('joie'))), 3);
    $this->assertEqual($this->p->parse(array(v('joie', "JOIE!"), v('joie'))), "JOIE!");
    $this->assertEqual($this->p->parse(array(v('joie', "1"), puts(v('joie')))), "1");
    $this->assertEqual($this->p->parse(array(puts('1'))), "1");
    $this->assertEqual(
      $this->p->parse(array(v('fruits', array('pomme', 'raisin', 'banane')), v('fruits'))), 
      array('pomme', 'raisin', 'banane'));
    $this->assertEqual(
      $this->p->parse(array(
        v('one', lambda(array(), 1)),
        call('one')
      )), 1);
  }

}

$test = new ParserTest();
$test->run(new HtmlReporter('utf-8'));

