<?php
    function number($n){
        $v = number_format($n, 0, ',', '.');
        return $v;
    }
    

    function numberClearFormat($n){
        $n = str_replace(".", "", $n);
        return $n;
    } 
?>