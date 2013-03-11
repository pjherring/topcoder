<?php

require "flower_garden.php";

$heights = array();
$blooms = array();
$wilts = array();
$cnt = 50;

for ($i = 0; $i < $cnt; $i++) {
    $height = -1;
    $bloom = rand(1, 365);
    $wilt = rand($bloom + 1, 365);

    do {
        $height = rand(1, 1000);
    } while (in_array($height, $heights));

    $heights[] = $height;
    $blooms[] = $bloom;
    $wilts[] = $wilt;
}

$garden = new FlowerGarden();

$start = microtime();

print_r($garden->get_ordering($heights, $blooms, $wilts));

$end = microtime();

echo "start: $start, end: $end , diff: " . ($end - $start);
