<?php

if (!function_exists('_getCityOptions')) {
    function _getCityOptions(){
        return [
            ['id' => 1, 'name'=> 'Pune'], 
            ['id' => 2, 'name'=> 'Mumbai'],
            ['id' => 3, 'name'=> 'Banglore'] 
        ];
    }
}

if (!function_exists('_getLeadTypeOptions')) {
    function _getLeadTypeOptions(){
        return [
            ['id' => 1, 'name'=> 'Type 1'], 
            ['id' => 2, 'name'=> 'Type 2'],
            ['id' => 3, 'name'=> 'Type 3'] 
        ];        
    }
}
