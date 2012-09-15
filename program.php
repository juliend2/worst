<?php
include 'nodes.php';
include 'parser.php';

$parser = new Parser();
$parser->parse(array(
  set('joie', str('cool')),
  set('+', lambda(array('first', 'second'), 
    array(add(get('first'), get('second'))))),
  puts(call('+', array(int(2), int(3)))),
  puts(get('joie'))
));
// $prog->execute();


