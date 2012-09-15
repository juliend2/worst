<?php

// Detect PHP's primitives and set them to their respective type
//
// $obj : (Mixed) value from which to guess the type
function detect_type($obj) {
  if (is_string($obj)) {
    return new StringNode($obj);
  } elseif (is_numeric($obj)) {
    return new NumberNode($obj);
  } elseif (is_array($obj)) {
    return new ArrayNode($obj);
  } else {
    return $obj;
  }
}

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

class NumberNode {
  function __construct($num) {
    $this->num = $num;
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

class MathNode {
  function __construct($operand, $first, $second) {
    $this->operand = $operand;
    $this->first = $first;
    $this->second = $second;
  }
}


function lambda() {
  $args = func_get_args();
  $arguments = array_shift($args);
  $instructions = $args; // the rest are instructions
  return new LambdaNode($arguments, $instructions);
}

function v($name) {
  $args = func_get_args();
  if (count($args) == 1) {
    return new GetNode($args[0]);
  } else {
    return new SetNode($args[0], detect_type($args[1]));
  }
}

function call() {
  $args = func_get_args();
  $name = array_shift($args);
  $args = array_map('detect_type', $args);
  return new CallNode($name, $args);
}

function puts($val) {
  return new PutsNode($val);
}

function math($operand, $first, $second) {
  return new MathNode($operand, $first, $second);
}

