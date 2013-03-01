<?php

$coins = array(1, 3, 5);
$sum = 11;
$state_solutions = array(0);

for ($i = 1; $i <= $sum; $i++) {
    $state_solutions[] = -1;
}

for ($i = 1; $i <= $sum; $i++) {

    echo "find solution for $i" . PHP_EOL;
    
    foreach ($coins as $coin) {

        echo PHP_EOL . "coin $coin" . PHP_EOL;

        if ($coin <= $i) {

            $sub_solution = $state_solutions[$i - $coin];
            echo "sub solution $sub_solution" . PHP_EOL;
            $current_solution = $state_solutions[$i];

            if ($current_solution == -1 || $current_solution > ($sub_solution + 1)) {
                $state_solutions[$i] = $sub_solution + 1;
            }
        }
    }

    echo PHP_EOL . "saved solution " . $state_solutions[$i] . PHP_EOL;
}

var_dump($state_solutions);
