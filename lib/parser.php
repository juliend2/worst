<?php

class Parser { 
  public $state = array();
  public $calls = array();

  function __construct($state=array(), $calls=array()) {
    $this->state = $state;
    $this->calls = $calls;
  }

  function parse($nodes = array()) {
    $retval = null;
    foreach ($nodes as $node) {
      if (get_class($node) == 'SetNode') {
        $this->state[$node->name] = $node->value;
        $retval = $node->value;

      } elseif (get_class($node) == 'GetNode') {
        $parser = new Parser($this->state, $this->calls);
        $retval = $parser->parse(array($this->state[$node->name]));

      } elseif (get_class($node) == 'NumberNode') {
        $retval = $node->num;

      } elseif (get_class($node) == 'StringNode') {
        $retval = $node->string;

      } elseif (get_class($node) == 'ArrayNode') {
        $retval = $node->arr;

      } elseif (get_class($node) == 'PutsNode') {
        $this->calls[] = $node;
        $parser = new Parser($this->state, $this->calls);
        $v = $parser->parse(array($node->val));
        print $v ."\n";
        $retval = $v;

      } elseif (get_class($node) == 'MathNode') {
        $first_parser = new Parser($this->state,$this->calls);
        $first = $first_parser->parse(array($node->first));
        $second_parser = new Parser($this->state,$this->calls);
        $second = $second_parser->parse(array($node->second));
        switch ($node->operand) {
          case '+': $retval = $first + $second; break;
          case '-': $retval = $first - $second; break;
          case '*': $retval = $first * $second; break;
          case '/': $retval = $first / $second; break;
          case '%': $retval = $first % $second; break;
        }

      } elseif (get_class($node) == 'LambdaNode') {
        $retval = $node;

      } elseif (get_class($node) == 'CallNode') {
        $lambda = $this->state[$node->name];
        $body = $lambda->instructions;
        foreach ($lambda->arguments as $k => $arg) {
          $this->state[$arg] = $node->arguments[$k];
        }
        $parser = new Parser($this->state, $this->calls);
        $retval = $parser->parse($body);

      } else {
        // literal
        // return $node;
      }
    } // end foreach

    return $retval; // return last assigned value
  }
}

