<?php
include 'lib/nodes.php';
include 'lib/stdlib.php';
include 'lib/parser.php';

$parser = new Parser();
$parser->parse(array(

  v('joie', 'cool'),
  v('un', 1),

  // set shortcut functions for math operations:
  v('+', lambda(array('first', 'second'), math('+', v('first'), v('second')))),
  v('-', lambda(array('first', 'second'), math('-', v('first'), v('second')))),
  v('*', lambda(array('first', 'second'), math('*', v('first'), v('second')))),
  v('/', lambda(array('first', 'second'), math('/', v('first'), v('second')))),

  // try them out:
  puts(call('+', 9, v('un'))),
  puts(call('-', 11, v('un'))),
  puts(call('*', 10, v('un'))),
  puts(call('/', 10, v('un'))),

  puts(math('+', 5, 5)),
  puts(v('joie')),
  puts('joie'),
  puts(call('is_numeric', 4)),
  loop(array(1, 2, 3), lambda(array('key', 'value'), puts(v('key'))))

));
