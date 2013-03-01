<?php

$sequence = array();
$built_from = array();
$longest = array();
$sequence_length = 10;
$longest_index = 0;

for ($i = 0; $i < $sequence_length; $i++) {
    $sequence[] = rand(0, 100);
    $sequences[] = array($sequence[$i]);
    $built_from[] = $i;
    $longest[] = 1;
}

for ($i = 1; $i < $sequence_length; $i++) {

    for ($j = 0; $j < $i; $j++) {

        if ($sequence[$i] > $sequence[$j] && ($longest[$j] + 1) > $longest[$i]) {
            $longest[$i] = $longest[$j] + 1;
            $built_from[$i] = $j;
        }
    }

    if ($longest[$i] > $longest[$longest_index]) {
        $longest_index = $i;
    }
}

print_r($sequence);
print_r($longest);
echo "longest_index $longest_index" . PHP_EOL;

$built_idx = $longest_index;

echo "Sequence: ";

while ($built_idx != $built_from[$built_idx]) {
    echo $sequence[$built_idx] . ", ";
    $built_idx = $built_from[$built_idx];
}

echo $sequence[$built_idx] . PHP_EOL;

