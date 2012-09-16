<?php

class Parser { 
  public $state = array();

  function __construct($state=array()) {
    $this->state = $state;
  }

  function parse($nodes = array()) {
    $retval = null;
    foreach ($nodes as $node) {
      if (get_class($node) == 'SetNode') {
        $this->state[$node->name] = $node->value;
        $retval = $node->value;

      } elseif (get_class($node) == 'GetNode') {
        $parser = new Parser($this->state);
        $retval = $parser->parse(array($this->state[$node->name]));

      } elseif (get_class($node) == 'NumberNode') {
        $retval = $node->num;

      } elseif (get_class($node) == 'StringNode') {
        $retval = $node->string;

      } elseif (get_class($node) == 'ArrayNode') {
        $retval = $node->arr;

      } elseif (get_class($node) == 'BooleanNode') {
        $retval = $node->bool;

      } elseif (get_class($node) == 'PutsNode') {
        $parser = new Parser($this->state);
        $v = $parser->parse(array($node->val));
        print $v ."\n";
        $retval = $v;

      } elseif (get_class($node) == 'MathNode') {
        $first_parser = new Parser($this->state);
        $first = $first_parser->parse(array($node->first));
        $second_parser = new Parser($this->state);
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
        if (isset($this->state[$node->name])) { // Lambda exists?
          $lambda = $this->state[$node->name];
          $body = $lambda->instructions;
          if (count($node->arguments) === count($lambda->arguments)) {
            foreach ($lambda->arguments as $k => $arg) {
              $this->state[$arg] = $node->arguments[$k];
            }
          } else {
            // throw an error
            throw new Exception("The function ".$node->name." needs ".count($lambda->arguments)
              ." arguments but you passed ".count($node->arguments)." arguments to it.");
          }
          $parser = new Parser($this->state);
          $retval = $parser->parse($body);
        } elseif (function_exists($node->name)) { // PHP function exists?
          $parser = new Parser($this->state);
          $args = array();
          foreach ($node->arguments as $arg) {
            $arg_parser = new Parser($this->state);
            $args[] = $arg_parser->parse(array($arg));
          }
          $retval = $parser->parse(array(detect_type(call_user_func_array($node->name, $args))));
        }

      } elseif (get_class($node) == 'LoopNode') {
        $lambda = $node->lambda;
        $parser = new Parser($this->state);
        $collection = is_array($node->collection) ? $node->collection : $parser->parse(array($node->collection));
        foreach ($collection as $lambda_arg_k => $lambda_arg_v) {
          // pass key and value to called lambda:
          $this->state[$lambda->arguments[0]] = detect_type($lambda_arg_k);
          $this->state[$lambda->arguments[1]] = detect_type($lambda_arg_v);
          $parser = new Parser($this->state);
          $parser->parse($lambda->instructions);
        }
        $retval = $node->collection;

      } else {
        // var_dump($node);
      }

    } // end foreach

    return $retval; // return last assigned value
  }
}

