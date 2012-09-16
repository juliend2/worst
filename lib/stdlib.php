<?php

// Language constructs:
// -----------------------------------------------------

function loop($collection, LambdaNode $lambda) {
  return new LoopNode($collection, $lambda);
}

function lambda() {
  $args = func_get_args();
  $arguments = array_shift($args);
  $instructions = $args; // the rest are instructions
  $instructions = array_map('detect_type', $instructions);
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
  return new PutsNode(detect_type($val));
}

function math($operand, $first, $second) {
  return new MathNode($operand, detect_type($first), detect_type($second));
}

