<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class FileHandler {

    private static function checkFilename($filenm) {
        $filename = $filenm;
        $pos = strpos($filename, ".");

        if ($filename[0] == "/") {
            $filename = substr($filenm, 0);
        }
        if ($pos == 0) {
            echo("wrong format - Log"); // log Event
        }
    }

    public static function getStringFromFile($filename = "customerSystemTables.sql") {

        //$username     = $_SERVER['REMOTE_USER']; // Nutzung des Authentifikationsmechanismus

        self::checkFilename($filename);

        $filepath = $filename;
        //echo $filepath."<br>";

        $fileArray = file($filepath);

        $string = "";

        foreach ($fileArray AS $row) {
            //echo $row."<br>";
            $string .= $row . "\n";
        }
        //echo $string;

        return $string;
    }

}
