<?php

class SetNode {
  function __construct($name, $value) {
    $this->name = $name;
    $this->value = $value;
  }
}

class GetNode {
  function __construct($name) {
    $this->name = $name;
  }
}

class StringNode {
  function __construct($string) {
    $this->string = $string;
  }
}

class IntegerNode {
  function __construct($integer) {
    $this->integer = $integer;
  }
}

class LambdaNode {
  function __construct($arguments, $instructions=array()) {
    $this->arguments = $arguments;
    $this->instructions = $instructions;
  }
}

class CallNode {
  function __construct($name, $arguments=array()) {
    $this->name = $name;
    $this->arguments = $arguments;
  }
}

class PutsNode {
  function __construct($val) {
    $this->val = $val;
  }
}

class AddNode {
  function __construct($first, $second) {
    $this->first = $first;
    $this->second = $second;
  }
}


function str($string) {
  return new StringNode($string);
}

function int($int) {
  return new IntegerNode($int);
}

function lambda($arguments, $instructions) {
  return new LambdaNode($arguments, $instructions);
}

function get($name) {
  return new GetNode($name);
}

function set($name, $value) {
  return new SetNode($name, $value);
}

function call() {
  $args = func_get_args();
  $name = array_shift($args);
  return new CallNode($name, $args);
}

function puts($val) {
  return new PutsNode($val);
}

function add($first, $second) {
  return new AddNode($first, $second);
}
