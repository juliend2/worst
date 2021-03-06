<?php

require_once('simpletest/unit_tester.php');
require_once('simpletest/web_tester.php');
require_once('simpletest/reporter.php');
require_once('../lib/nodes.php');
require_once('../lib/stdlib.php');
require_once('../lib/parser.php');

function my_add_function($arg1) {
  return $arg1 + 3;
}

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
  }

  function testLambdas() {
    $this->assertEqual(
      $this->p->parse(array( v('one', lambda(array(), 1)), call('one'))), 
      1);
    $this->assertEqual(
      $this->p->parse(array( v('one', lambda(array('arg'), v('arg'))), call('one', 2))), 
      2);

    $this->assertEqual(
      $this->p->parse(array( 

        v('global', lambda(array('n'), 
          v('sub', lambda(array(), math('*', v('n'), 2))),
          call('sub'))), 
        call('global', 5)

      )), 
      10);
  }

  function testPHPFunctions() {
    $this->assertEqual($this->p->parse(array(
      call('is_numeric', 4)
    )),
    true);
    $this->assertEqual($this->p->parse(array(
      call('my_add_function', 2)
    )),
    5);
  }

  function testLoops() {
    // assert loop() returns the same array that is passed to it:
    $this->assertEqual( $this->p->parse(array(
      loop(array(1, 2, 3), lambda(array('k', 'v'), 
        puts(v('k'))))
    )),
    array(1, 2, 3));
  }

}

$test = new ParserTest();
$test->run(new HtmlReporter('utf-8'));

