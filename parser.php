<?php

class Parser { 
  public $state = array();
  public $calls = array();

  function __construct($state=array(), $calls=array()) {
    $this->state = $state;
    $this->calls = $calls;
  }

  function parse($nodes = array()) {
    foreach ($nodes as $node) {
      if (get_class($node) == 'SetNode') {
        $this->state[$node->name] = $node->value;

      } elseif (get_class($node) == 'GetNode') {
        $parser = new Parser($this->state, $this->calls);
        return $parser->parse(array($this->state[$node->name]));

      } elseif (get_class($node) == 'IntegerNode') {
        return $node->integer;

      } elseif (get_class($node) == 'StringNode') {
        return $node->string;

      } elseif (get_class($node) == 'PutsNode') {
        $this->calls[] = $node;
        $parser = new Parser($this->state, $this->calls);
        print $parser->parse(array($node->val)) ."\n";

      } elseif (get_class($node) == 'MathNode') {
        $first_parser = new Parser($this->state,$this->calls);
        $first = $first_parser->parse(array($node->first));
        $second_parser = new Parser($this->state,$this->calls);
        $second = $second_parser->parse(array($node->second));
        switch ($node->operand) {
        case '+': return $first + $second;
        case '-': return $first - $second;
        case '*': return $first * $second;
        case '/': return $first / $second;
        case '%': return $first % $second;
        }

      } elseif (get_class($node) == 'LambdaNode') {
        return $node;

      } elseif (get_class($node) == 'CallNode') {
        $lambda = $this->state[$node->name];
        $body = $lambda->instructions;
        foreach ($lambda->arguments as $k => $arg) {
          $this->state[$arg] = $node->arguments[$k];
        }
        $parser = new Parser($this->state, $this->calls);
        return $parser->parse($body);

      } else {
        // literal
        // return $node;
      }
    }
  }
}

