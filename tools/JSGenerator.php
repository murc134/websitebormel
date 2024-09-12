<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class JSGenerator {
    public function __construct() {

    }
    public function alert($str, $sep="'"){
        return "alert($sep$str$sep);";
    }
 }
 
