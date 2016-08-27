<?php
function StairCase($n) {
    for($i=1;$i<=$n;$i++){
        for($j=0;$j<$n-$i;$j++){
            echo ' ';
        }
        for($j=0;$j<$i;$j++){
            echo '#';
        }
        echo "\n";
    }

}

StairCase($argv[1]);
