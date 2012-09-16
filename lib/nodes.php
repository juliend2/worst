<?php

// Detect PHP's primitives and set them to their respective type
//
// $obj : (Mixed) value from which to guess the type
function detect_type($obj) {
  if (is_string($obj)) {
    return new StringNode($obj);
  } elseif (is_bool($obj)) {
    return new BooleanNode($obj);
  } elseif (is_numeric($obj)) {
    return new NumberNode($obj);
  } elseif (is_array($obj)) {
    return new ArrayNode($obj);
  } else {
    return $obj;
  }
}

// Variable management:
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

// Basic types:
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

class BooleanNode {
  function __construct($bool) {
    $this->bool = $bool;
  }
}

class ArrayNode {
  function __construct($arr) {
    $this->arr = $arr;
  }
}

// Functions:
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

// Standard library:
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

// Control structures:
class LoopNode {
  function __construct($collection, $lambda) {
    $this->collection = $collection;
    $this->lambda = $lambda;
  }
}
