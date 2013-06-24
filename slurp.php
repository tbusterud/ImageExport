<?php
require_once('slurpee.php');

print 'Slurpee aye yay moth fckr!';
$url = 'http://matbilder.x.keymedia.no/media.json';
$json = Slurpee::fetchJSONIndex($url);
$array = json_decode($json, true);

// var_dump($array);

// print "\n\n".count($array['media']);


var_dump($array['media'][0]);



?>
