<?php

/* function solution($numbers) {
    $result = [];

    for ($i = 0; $i < count($numbers) - 2; $i++) {
        $a = $numbers[$i];
        $b = $numbers[$i + 1];
        $c = $numbers[$i + 2];

        // Check if the triple (a, b, c) forms a zigzag
        if (($a < $b && $b > $c) || ($a > $b && $b < $c)) {
            $result[] = 1;
        } else {
            $result[] = 0;
        }
    }

    // Return the result array
    return $result;
}


print_r(solution([1000000000, 1000000000, 1000000000])); */