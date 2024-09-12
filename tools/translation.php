<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Translator
 *
 * @author Murc
 */
include_once 'DBConnector.php';
include_once 'functions.php';
include_once 'sessioncontrol.php';

if (isset($_GET['lang']) && !empty($_GET['lang']) && Translation::isValidLanguage($_GET['lang'], 1)) {
    $_SESSION['lang_iso'] = $_GET['lang'];
} else if (!isset($_SESSION['lang_iso']) || empty($_SESSION['lang_iso'])) {
    $lang = getDefaultLangIso();

    switch ($lang) {
        case "en":
            //echo "PAGE EN";
            $_SESSION['lang_iso'] = "en"; //include check session FR
            break;
        case "de":
            //echo "PAGE DE";
            $_SESSION['lang_iso'] = "de"; //include check session FR
            break;
        case "sv":
            //echo "PAGE DE";
            $_SESSION['lang_iso'] = "sv"; //include check session FR
            break;
        case "es":
            //echo "PAGE DE";
            $_SESSION['lang_iso'] = "es"; //include check session FR
            break;
        case "fr":
            //echo "PAGE DE";
            $_SESSION['lang_iso'] = "fr"; //include check session FR
            break;
        default:
            //echo "PAGE EN - Setting Default";
            $_SESSION['lang_iso'] = "en"; //include check session FR
            break;
    }
}

if (!isset($_SESSION['lang_iso']) || empty($_SESSION['lang_iso'])) {
    $_SESSION['lang_iso'] = "en";
}

$lang = $_SESSION['lang_iso'];

class Translation {

    const FILEEXTENSION = "php";

    public static function getLanguageByID($id) {
        $sql = "select * from languages where id_lang = '$id'";
        //echo $sql;
        $dbConnector = new DBConnector();

        if ($result = ($dbConnector->executeQuery($sql))) {
            $array = array();

            while ($row = $result->fetch_assoc()) {
                $array[] = $row;
                //printf("%s (%s)\n", $row["Name"], $row["CountryCode"]);
            }


            $result->free();
            if (count($array) <= 0) {
                return array();
            }
            return $array[0];
        } else {
            return array();
        }
    }

    public static function deleteTranslation($name_key) {
        $path = './tools/translations/' . $name_key . "/";

        // Get available languages dynamically
        $languages = Translation::getLanguages();

        foreach ($languages as $lang) {
            $iso = $lang["iso"];

            // Check if the language is valid before attempting deletion
            if (Translation::isValidLanguage($iso)) {
                $filePath = $path . $iso . ".php";
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        // Attempt to remove the directory if it's empty
        if (is_dir($path) && count(scandir($path)) == 2) { // Only '.' and '..'
            rmdir($path);
        }
    }

    public static function updateTranslation($name_key, $iso, $value) {
        $path = './tools/translations/' . $name_key . "/";

        if (Translation::isValidLanguage($iso)) {
            $myfile = fopen($path . $iso . ".php", "w") or die("Unable to open file!");
            fwrite($myfile, $value);
            fclose($myfile);
        }
    }

    public static function getLanguageByIso($iso) {
        $sql = "select * from languages where iso = '$iso'";
        //echo $sql;
        $dbConnector = new DBConnector();

        if ($result = ($dbConnector->executeQuery($sql))) {
            $array = array();

            while ($row = $result->fetch_assoc()) {
                $array[] = $row;
                //printf("%s (%s)\n", $row["Name"], $row["CountryCode"]);
            }


            $result->free();
            if (count($array) <= 0) {
                return array();
            }
            return $array[0];
        } else {
            return array();
        }
    }

    public static function getLanguages($active = -1, $order = "asc", $spoken = false) {
        $sql = "select * from languages where 1";
        $dbConnector = new DBConnector();
        if ($active >= 0) {
            $sql .= " and active = $active";
        }
        if (!empty($spoken)) {
            $sql .= " and level != 'lang_lvl_none'";
        }
        $sql .= " order by id_lang $order";
        if ($result = ($dbConnector->executeQuery($sql))) {
            $array = array();

            while ($row = $result->fetch_assoc()) {
                $array[] = $row;
                //printf("%s (%s)\n", $row["Name"], $row["CountryCode"]);
            }


            $result->free();
            return $array;
        } else {
            return array();
        }
    }

    public static function isValidLanguage($iso, $active = -1) {
        $sql = "select count(*) as `count` from languages where iso = '$iso' ";
        $result = false;
        switch ($active) {
            case $active == 0:
                $sql .= " and active = 0";
                break;
            case $active > 0:
                $sql .= " and active = 1";
                break;
            default:
                break;
        }

        $db = new DBConnector();
        //echo $sql;
        if ($result = ($db->executeQuery($sql))) {
            if ($row = $result->fetch_assoc()) {
                $result = $row["count"] > 0 ? 1 : 0;
            }
        }
        return $result;
    }

    public static function set($key, $value = "") {
        $key2 = str_replace(" ", "_", $key);
        $path = dirname(__FILE__) . '/translations/' . $key2 . "/";
        //echo $path;
        if (!file_exists($path)) {
            mkdir($path) ? "1" : "0";
        }
        foreach (self::getLanguages() as $lang) {
            $filePath = $path . $lang["iso"] . "." . self::FILEEXTENSION;
            if (!file_exists($filePath)) {
                $myfile = fopen($filePath, "w") or die("Unable to open file: " . $filePath);
                fwrite($myfile, empty($value) ? str_replace(" ", "_", $key) : $value);
                fclose($myfile);
            }
            // else {
            // File already exists, do not overwrite
            //echo "File already exists: " . $filePath . "\n";
            // }
        }
    }

    public static function exists($key, $iso = "") {
        $path = dirname(__FILE__) . '/translations/' . $key . "/";

        if (!empty($iso)) {
            if (!self::isValidLanguage($iso)) {
                return false;
            } else {
                if (!file_exists($path)) {
                    return false;
                } else {
                    return file_exists($path . $iso . "." . self::FILEEXTENSION);
                }
            }
        } else {
            return file_exists($path);
        }
    }

    public static function getFileContents($key, $iso = "") {
        $key = str_replace(" ", "_", $key);
        $path = dirname(__FILE__) . '/translations/' . $key . "/";

        if (empty($iso)) {
            $iso = $_SESSION["lang_iso"];
        }
        //echo $iso;
        if (!self::isValidLanguage($iso)) {
            //echo "no valid lang ";
            return self::getFileContents($key, "en");
        } else {
            if (self::exists($key, $iso)) {
                return getFileContent($path . $iso . "." . self::FILEEXTENSION);
            } else {
                self::set($key);
                return self::getFileContents($key, $iso);
            }
        }
    }

    public static function get($key, $iso = "") {

        $key = str_replace(" ", "_", $key);
        $path = dirname(__FILE__) . '/translations/' . $key . "/";

        if (empty($iso)) {
            $iso = $_SESSION["lang_iso"];
        }

        if (!self::isValidLanguage($iso)) {
            //echo "no valid lang ";
            return self::get($key, "en");
        } else {
            if (self::exists($key, $iso)) {
                //echo " | $key | ";
                include ($path . $iso . "." . self::FILEEXTENSION);
                return "";
            } else {
                self::set($key);
                return self::get($key, $iso);
            }
        }
    }

    public static function renderTranslationForm(array $languages = [], $find = "") {
        $find = str_replace(" ", "_", $find);
        $translation = "";

        $translationsource1 = '<form id="translate%sForm" name="translate%sForm" ><h4>%s:</h4><input type="hidden" id="key" name="key" value="%s"/><br>'; // key, key, key, key
        $translationsource2 = ' <div class="col-xs-2">%s</div><div class="col-xs-10"><textarea id="value_%s" name="value_%s" style="width: %s; min-height: %s;">%s</textarea></div>'; // iso, iso, iso, width, min-height value
        $translationsource3 = '<div id="translate%sFormSuccessInfo"></div><div><input type="submit" id="translate%sSubmit" name="translate%sSubmit"/></div></form>'; // key, key

        $url = substr(getUri(), 0, strpos(getUri(), "?"));

        $url .= "?page=translations&find=%s" . ((isset($_GET["languages"]) && !empty($_GET["languages"])) ? "&languages=" . $_GET["languages"] : "");
        $translationsource4 = '<div><a href="' . $url . '">%s</a></div><br><div><a href="' . $url . '">%s</a></div>';

        $translationsource5 = '<div><a href="' . $url . '">%s</a></div>';

        $tlJS = '<script type="text/javascript">
                                            $("#translate%sForm").submit(function (event) {
                                                event.preventDefault();
                                                var fd = new FormData(this);
                                                $.ajax({
                                                    url: "ajax/translateAjax.php",
                                                    type: "POST",
                                                    data: fd,
                                                    processData: false, // tell jQuery not to process the data
                                                    contentType: false   // tell jQuery not to set contentType
                                                }).done(function (data) {
                                                    $("#translate%sFormSuccessInfo").html("Success");
                                                    console.log(data);
                                                }).fail(function () {
                                                    $("#translate%sFormSuccessInfo").html("Error");
                                                    console.log(data);
                                                });
                                            });
                                        </script>'; // key

        if (empty($languages) || count($languages) == 0) {
            $languages = self::getLanguages();
        }

        if (empty($find)) {

            if ($handle = opendir(dirname(__FILE__) . '/translations/')) {
                $folders = array();

                /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
                while (false !== ($key = readdir($handle))) {
                    if (strpos($key, ".")) {
                        continue;
                    }
                    if ($key != "." && $key != "..") {
                        $translation .= sprintf($translationsource1, $key, $key, $key, $key);

                        foreach ($languages as $lang) {
                            $iso = $lang["iso"];
                            if (Translation::isValidLanguage($iso)) {
                                $value = self::getFileContents($key, $iso);
                                $translation .= sprintf($translationsource2, $iso, $iso, $iso, '100%', '300px', $value);
                            }
                        }
                    }
                    $translation .= sprintf($translationsource3, $key, $key, $key);
                    $translation .= sprintf($tlJS, $key, $key, $key, $key);
                }

                closedir($handle);
            }
        } else if ($handle = opendir(dirname(__FILE__) . '/translations/' . $find . "/")) {
            $translation .= sprintf($translationsource1, $find, $find, $find, $find);
            foreach ($languages as $lang) {
                $iso = $lang["iso"];
                if (Translation::isValidLanguage($iso)) {
                    $value = self::getFileContents($find, $iso);
                    $translation .= sprintf($translationsource2, $iso, $iso, $iso, '100%', '300px', $value);
                }
            }
            $translation .= sprintf($translationsource3, $find, $find, $find);
            $neighbors = self::getNeighborKeys($find);
            $translation .= sprintf($translationsource4, $neighbors[0], $neighbors[0], $neighbors[1], $neighbors[1]);
            $translation .= sprintf($tlJS, $find, $find, $find, $find);
            $translation .= "<h3>Others:</h3>";

            $keys = self::getTranslationKeys();

            foreach ($keys as $key) {
                $translation .= sprintf($translationsource5, $key, $key);
            }
        }
        return $translation;
    }

    public static function getNeighborKeys($skey) {
        $prev = "";
        $next = "";
        $found = false;
        $skey = str_replace(" ", "_", $skey);

        if ($handle = opendir(dirname(__FILE__) . '/translations/')) {
            $folders = array();

            /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
            for ($i = 0; false !== ($key = readdir($handle)); $i++) {
                if ($found) {
                    $next = $key;
                    break;
                } else if ($key != "." && $key != "..") {

                    if ($key == $skey) {
                        $found = true;
                    } else {
                        $prev = $key;
                    }
                } else {
                    $i--;
                }
            }
        }
        return [$prev, $next];
    }

    public static function getTranslationKeys() {
        $keys = array();

        if ($handle = opendir(dirname(__FILE__) . '/translations/')) {
            /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
            while (false !== ($key = readdir($handle))) {
                if ($key != "." && $key != "..") {
                    $keys[] = $key;
                }
            }
        }
        return $keys;
    }

    public static function getAllTranslations(array $languages, $find = "") {
        $translation = array();
        $find = str_replace(" ", "_", $find);

        if (empty($languages) || count($languages) == 0) {
            $languages = self::getLanguages();
        }

        if (empty($find)) {
            if ($handle = opendir(dirname(__FILE__) . '/translations/')) {

                /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
                while (false !== ($key = readdir($handle))) {

                    if ($key != "." && $key != "..") {
                        $translation[$key] = array();

                        foreach ($languages as $lang) {
                            if (Translation::isValidLanguage($lang["iso"])) {
                                $translation[$key][$lang["iso"]] = self::getFileContents($key, $lang["iso"]);
                            }
                        }
                    }
                }

                closedir($handle);
            }
        } else if ($handle = opendir(dirname(__FILE__) . '/translations/' . $find . "/")) {
            $translation[$find] = array();
            $color = getRandomHexColor();

            foreach ($languages as $lang) {
                if (Translation::isValidLanguage($lang["iso"])) {
                    $translation[$find][$lang["iso"]] = self::getFileContents($find, $lang["iso"]);
                }
            }
        }
        return $translation;
    }
}

function T($key, $iso = "") {
    return Translation::get($key, $iso);
}
