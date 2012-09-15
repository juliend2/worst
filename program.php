<?php
include 'nodes.php';
include 'parser.php';

$parser = new Parser();
$parser->parse(array(

  set('joie', str('cool')),
  set('un', int(1)),

  set('+', lambda(array('first', 'second'), 
    math('+', get('first'), get('second')))),
  set('-', lambda(array('first', 'second'), 
    math('-', get('first'), get('second')))),
  set('*', lambda(array('first', 'second'), 
    math('*', get('first'), get('second')))),
  set('/', lambda(array('first', 'second'), 
    math('/', get('first'), get('second')))),

  puts(call('+', int(2), get('un'))),
  puts(get('joie'))

));


