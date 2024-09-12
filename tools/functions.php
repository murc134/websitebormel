<?php

include_once dirname(__FILE__) . '/../config/encryption.php';
include_once dirname(__FILE__) . '/handlers/SVGClass.php';
include_once dirname(__FILE__) . '/handlers/ExperienceHandler.php';
include_once dirname(__FILE__) . '/handlers/FileHandler.php';
include_once dirname(__FILE__) . '/handlers/KeywordHandler.php';
include_once dirname(__FILE__) . '/handlers/ProjectHandler.php';
include_once dirname(__FILE__) . '/handlers/SVGClass.php';
include_once dirname(__FILE__) . '/handlers/SkillDataHandler.php';
include_once dirname(__FILE__) . '/handlers/UrlHandler.php';
include_once dirname(__FILE__) . '/handlers/UserHandler.php';
include_once dirname(__FILE__) . '/handlers/VisitorHandler.php';
include_once dirname(__FILE__) . '/handlers/YoutubeVideoHandler.php';

include_once dirname(__FILE__) . '/helpers/ApplicationToolbox.php';
include_once dirname(__FILE__) . '/helpers/Quote.php';
include_once dirname(__FILE__) . '/helpers/Color.php';
include_once dirname(__FILE__) . '/helpers/Mail.php';
include_once dirname(__FILE__) . '/helpers/RenderMode.php';
include_once dirname(__FILE__) . '/helpers/TextPlotter.php';

function sanitize_input($input) {
    if (version_compare(PHP_VERSION, '8.1.0', '<')) {
        return filter_var($input, FILTER_SANITIZE_STRING); // For older versions of PHP
    } else {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8'); // For PHP 8.1 and newer
    }
}

function getPlatforms() {
    $sql = "select * from platform order by name_key asc";
    $db = new DBConnector();
    $return = array();

    if ($result = ($db->executeQuery($sql))) {
        while ($row = $result->fetch_assoc()) {
            $return[] = $row;
        }
    }
    $result->free();
    return $return;
}

function printDefaultFooter() {
//include 'section/cableplug.php';
//include 'section/map.php';
    include 'section/footer.php';
//include 'section/copyright.php';
    include 'section/visitors.php';
}

function Utf8_ansi($json = '') {

    $utf8_ansi2 = array(
        "\u00c0" => "À",
        "\u00c1" => "Á",
        "\u00c2" => "Â",
        "\u00c3" => "Ã",
        "\u00c4" => "Ä",
        "\u00c5" => "Å",
        "\u00c6" => "Æ",
        "\u00c7" => "Ç",
        "\u00c8" => "È",
        "\u00c9" => "É",
        "\u00ca" => "Ê",
        "\u00cb" => "Ë",
        "\u00cc" => "Ì",
        "\u00cd" => "Í",
        "\u00ce" => "Î",
        "\u00cf" => "Ï",
        "\u00d1" => "Ñ",
        "\u00d2" => "Ò",
        "\u00d3" => "Ó",
        "\u00d4" => "Ô",
        "\u00d5" => "Õ",
        "\u00d6" => "Ö",
        "\u00d8" => "Ø",
        "\u00d9" => "Ù",
        "\u00da" => "Ú",
        "\u00db" => "Û",
        "\u00dc" => "Ü",
        "\u00dd" => "Ý",
        "\u00df" => "ß",
        "\u00e0" => "à",
        "\u00e1" => "á",
        "\u00e2" => "â",
        "\u00e3" => "ã",
        "\u00e4" => "ä",
        "\u00e5" => "å",
        "\u00e6" => "æ",
        "\u00e7" => "ç",
        "\u00e8" => "è",
        "\u00e9" => "é",
        "\u00ea" => "ê",
        "\u00eb" => "ë",
        "\u00ec" => "ì",
        "\u00ed" => "í",
        "\u00ee" => "î",
        "\u00ef" => "ï",
        "\u00f0" => "ð",
        "\u00f1" => "ñ",
        "\u00f2" => "ò",
        "\u00f3" => "ó",
        "\u00f4" => "ô",
        "\u00f5" => "õ",
        "\u00f6" => "ö",
        "\u00f8" => "ø",
        "\u00f9" => "ù",
        "\u00fa" => "ú",
        "\u00fb" => "û",
        "\u00fc" => "ü",
        "\u00fd" => "ý",
        "\u00ff" => "ÿ");

    foreach ($utf8_ansi2 as $key => $value) {
        $json = str_replace($key, $value, $json);
    }

    return $json;
}

function GET($key) {
    if (isset($_GET["$key"]) && !empty($_GET["$key"])) {
        return $_GET["$key"];
    } else {
        return "";
    }
}

function POST($key) {
    if (isset($_POST["$key"]) && !empty($_POST["$key"])) {
        return $_POST["$key"];
    } else {
        return "";
    }
}

function replaceTags($text) {
    $text = str_replace("'", '"', $text);
    $text = str_replace("< ", "<", $text);
    $text = str_replace("/ >", "/>", $text);

    $start = strpos($text, "<");
    $end = strpos($text, ">");

    $clean = "";
    $tmp = $text;

    if ($start === false && $end === false) {
        return $text;
    }

    do {
        $clean .= substr($tmp, 0, $start);
        $tmp = substr($tmp, $end + 1);

        $start = strpos($tmp, "<");
        $end = strpos($tmp, ">");
    } while ($start !== false && $end !== false);
    return $clean . $tmp;
}

function getRealLengthText($text) {
    return strlen(replaceTags($text));
}

function getAVGCalcTimeCV() {
    $sql = "SELECT AVG(time) as durchschnitt FROM cv_export";
    $db = new DBConnector();
    if ($result = ($db->executeQuery($sql))) {
        if ($row = $result->fetch_assoc()) {
            return $row["durchschnitt"];
        }
    }
    return 30;
}

function getDefaultLangIso() {
    if ((isset($_SERVER)) && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    } else {
        return "en";
    }
}

function getTrackingPreferences() {
    $sql = "select * from `preferences` where `id_preference` = 0";

    $db = new DBConnector();

    $data = array();
    $data["id_preference"] = -1;
    $data["enable_tracking"] = 0;
    $data["accept_cookies"] = -1;
    $data["accept_ip_tracking"] = 0;

    if ($result = ($db->executeQuery($sql))) {
        $row = $result->fetch_assoc();
        if (count($row) > 0) {
            $data["id_preference"] = $row["id_preference"];
            $data["enable_tracking"] = $row["enable_tracking"];
        }
    }

    $sql = "select * from `cookiepreferences` where `unique_identifier` = '" . UserHandler::getUniqueIdentifier() . "'";

    if ($result = ($db->executeQuery($sql))) {
        $row = $result->fetch_assoc();

        if ($row != NULL && count($row) > 0) {
            $data["accept_cookies"] = $row["accept_cookies"];
            $data["accept_ip_tracking"] = $row["accept_ip_tracking"];

            if (!empty($data["enable_tracking"])) {
                $data["enable_tracking"] = $row["accept_anonymous_tracking"];
            }
        }
    }

    return $data;
}

function getMessageSeen($key, $min = 10) {

    $db = new DBConnector();

    $sql = "SELECT id_message FROM message where key_msg like '$key';";

    $id_msg = 0;
    if ($result = ($db->executeQuery($sql))) {
        if ($row = $result->fetch_assoc()) {
            $id_msg = $row["id_message"];
        }
    }
//get msg older than $min minutes
    $sql = "SELECT * FROM messageseen where id_message = '$id_msg' and MINUTE(TIMEDIFF(NOW(), time_seen))<$min;";

    if ($result = ($db->executeQuery($sql))) {
        $row = $result->fetch_assoc();
        if (count($row) > 0) {
            return true;
        }
    }
    return false;
}

function setMessageSeen($key) {
    $db = new DBConnector();
    $sql = "SELECT id_message FROM message where key_msg like '$key';";

    $id_msg = 0;
    if ($result = ($db->executeQuery($sql))) {
        if ($row = $result->fetch_assoc()) {
            $id_msg = $row["id_message"];
        }
    }

    if (!empty($id_msg)) {
        $sql2 = "REPLACE INTO `messageseen` (`id_message`, `ipv4`, `time_seen`) VALUES ('$id_msg', '" . $_SERVER['REMOTE_ADDR'] . "', now());";
        $db->executeQuery($sql2);
        return true;
    }

    return false;
}

function renderBatterie($width, $height, $rendermode = 0, $percent = 100, $params = "") {
    return SVG::renderBatterie($width, $height, $rendermode, $percent, $params);
}

function getPersonalData() {
    $sql = "SELECT * FROM person order by id_person desc;";
    $db = new DBConnector();

    if ($result = ($db->executeQuery($sql))) {
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
    } else {
        return array(
            "firstname" => "Christoph",
            "website" => "http://christoph-brucksch.de",
            "lastname" => "Brucksch",
            "street" => "Hogenbergstrasse 13",
            "zip" => "80686",
            "city" => "München",
            "phone" => "+49 (0) 172 / 632 11 97",
            "email" => "c4rtwork@gmail.com",
        );
    }
}

function getUri() {
    $url = isset($_SERVER['HTTPS']) ? "https" : "http" . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    return substr($url, 0, strpos($url, "?"));
}

function getFileContent($filepath) {
// read content
    $myfile = fopen($filepath, "r") or die("Unable to open file $filepath!");
    $content = "";
    if (filesize($filepath) > 0) {
        $content = fread($myfile, filesize($filepath));
    }

    fclose($myfile);
    return $content;
}

function doRelacements($content, $color, $class) {
    $content = str_replace('id="' . $class . '"' . ' fill="' . $color . '"', 'fill="' . $color . '"', $content);

// add class
    $content = str_replace('fill="' . $color . '"', 'id="' . $class . '"' . ' fill="' . $color . '"', $content);

    return $content;
}

function rewriteFile($filepath, $content) {
// rewrite file
    $myfile = fopen($filepath, "w") or die("Unable to open file $filepath!");
    fwrite($myfile, $content);
    fclose($myfile);
}

function repairDir($dir) {
    $dir = str_replace("\\", DIRECTORY_SEPARATOR, $dir);
    $dir = str_replace("/", DIRECTORY_SEPARATOR, $dir);
    return $dir;
}

function getColorArray() {
    $colors = scandir("css/colors");

    $array = array();
    for ($i = 0; $i < count($colors); $i++) {
        if ($colors[$i] != "off.css") {
            $array[] = str_replace(".css", "", $colors[$i]);
        }
    }
    return $array;
}

function getColorArrayJS() {
    $colors = scandir("css/colors");

    $str = "[";
    for ($i = 0; $i < count($colors); $i++) {
        if ($colors[$i] != "off.css" && $colors[$i] != ".." && $colors[$i] != ".") {
            if ($i > 2) {
                $str .= ",";
            }
            $str .= "'" . str_replace(".css", "", $colors[$i]) . "'";
        }
    }
    $str .= "]";
    return $str;
}

function getThemeColor() {
    return "blue";
    $colors = scandir("css/colors");

// TODO: Colorize pics and remove return abobe to enable multi colored scheme
    $color = "";
    do {
        $color = $colors[mt_rand(2, count($colors) - 1)];
    } while ($color == "off.css");
    return str_replace(".css", "", $color);
}

function getRandomHex() {
    return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}

function getRandomHexColor() {
    return getRandomHex() . getRandomHex() . getRandomHex();
}

function generateSkillArray($nameKey, $value, $prio = 0) {
    $return = array();

    $return["nameKey"] = $nameKey;
    $return["value"] = $value;
    $return["prio"] = $prio;

    return $return;
}

function initClassesStudyiconSVG() {
    $icons = array(
        'img/studyicons/math1.svg',
        'img/studyicons/math2.svg',
        'img/studyicons/prog1.svg',
        'img/studyicons/prog2.svg',
        'img/studyicons/mediengestaltung.svg',
        'img/studyicons/richmedia.svg',
        'img/studyicons/aktustikoptik.svg',
        'img/studyicons/kommnetze.svg',
        'img/studyicons/bose.svg',
        'img/studyicons/theo.svg',
        'img/studyicons/algo.svg',
        'img/studyicons/rechnerstruk.svg',
        'img/studyicons/audiovideotech.svg', //audiovideotech
        'img/studyicons/ooad.svg',
        'img/studyicons/database.svg',
        'img/studyicons/linux-logo.svg',
        'img/studyicons/3d.svg',
        'img/studyicons/english1.svg',
        'img/studyicons/english2.svg',
        'img/studyicons/animation.svg',
        'img/studyicons/cryengine.svg',
    );

    $path = dirname(__FILE__) . '/../img/studyicons/';

    if ($handle = opendir($path)) {
        $folders = array();

        /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
        while (false !== ($key = readdir($handle))) {
            if ($key != "." && $key != "..") {
                $content = getFileContent($path . $key);
                $content = doRelacements($content, "#000000", "svgColor");
                rewriteFile($path . $key, $content);
            }
        }
    }
}

function getTimeDifference($from, $until = "") {
    $dateStart = new DateTime($from);
    $dateEnd = new DateTime();

    if (!empty($until)) {
        $dateEnd = new DateTime($until);
    }

    // Calculate the difference in years, months, and days
    $dteDiff = $dateStart->diff($dateEnd);

    $years = $dteDiff->y;
    $months = $dteDiff->m;
    $days = $dteDiff->d;

    // Adjust if the days are different
    if ($days > 0) {
        $months += 1;
    }

    // If months reach 12, roll over into years
    if ($months >= 12) {
        $years += 1;
        $months -= 12;
    }

    return [$years, $months];
}

function initClassesDefaultSVG() {
    $files = [
        "img/icons/house.svg",
        "img/icons/logo.svg",
        "img/icons/mail.svg",
        "img/icons/mousewheel.svg",
        "img/icons/osna.svg",
        "img/icons/phone.svg",
        "img/icons/touch.svg",
        "img/electricity_off.svg",
        "img/electricity_on.svg",
    ];
    $content = "";
    for ($i = 0; $i < count($files); $i++) {
        $content = getFileContent($files[$i]);

        if ("img/icons/logo.svg" == $files[$i]) {
            $content = doRelacements($content, "#840000", "svgLogoColor");
        } else {
            $content = doRelacements($content, "#840000", "svgColor");
        }

        $content = doRelacements($content, "#FFFFFF", "light_svg");
        $content = doRelacements($content, "#C60000", "svgSwitchFg");
        $content = doRelacements($content, "#FF7373", "svgSwitchFgSide");
        $content = doRelacements($content, "#820000", "svgSwitchBg");
        $content = doRelacements($content, "#212121", "svgCable");
        $content = doRelacements($content, "#202020", "svgWhiteBg");
        $content = doRelacements($content, "#9B9A95", "svgCablePlug");
        $content = doRelacements($content, "#FC9FFF", "svgNumbers");
        $content = doRelacements($content, "#F2F2F2", "svgWhite");
        rewriteFile($files[$i], $content);
    }
}

function dateDifference($startDate, $endDate) {
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);
    if ($startDate === false || $startDate < 0 || $endDate === false || $endDate < 0 || $startDate > $endDate)
        return false;

    $years = date('Y', $endDate) - date('Y', $startDate);

    $endMonth = date('m', $endDate);
    $startMonth = date('m', $startDate);

// Calculate months 
    $months = $endMonth - $startMonth;
    if ($months <= 0) {
        $months += 12;
        $years--;
    }
    if ($years < 0)
        return false;

// Calculate the days 
    $offsets = array();
    if ($years > 0)
        $offsets[] = $years . (($years == 1) ? ' year' : ' years');
    if ($months > 0)
        $offsets[] = $months . (($months == 1) ? ' month' : ' months');
    $offsets = count($offsets) > 0 ? '+' . implode(' ', $offsets) : 'now';

    $days = $endDate - strtotime($offsets, $startDate);
    $days = date('z', $days);

    return array($years, $months, $days);
}

function formatPhoneNumber($number) {
    $länderzahl = substr($number, 0, 3);
    $vorwahl = substr($number, 3, 3);
    $rest = substr($number, 6);
    $formated_rest = "";

    $j = 0;

    for ($i = 0; $i < count($rest); $i++) {
        if (($i > 3 && ++$j > 1) || $i == 3) {
            $j = 0;
            $formated_rest .= " ";
        }
        $formated_rest .= $rest[$i];
    }
    return $länderzahl . " (0) " . $vorwahl . " / " . $rest;
}

function getCVDownloadLanguageCode() {
    if ($_SESSION['lang_iso'] != "de") {
        return "en";
    } else {
        return "de";
    }
}

function encrypt($encrypt, $key = _ENCRYPTION_KEY_, $iv = _ENCRYPTION_IV_) {
    // Ensure that the IV length is correct for AES-128-CBC (16 bytes)
    $iv_length = openssl_cipher_iv_length('AES-128-CBC');

    if (strlen($iv) !== $iv_length) {
        die("IV length should be $iv_length bytes");
    }

    // Encrypt the data using OpenSSL
    $encrypted = openssl_encrypt($encrypt, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);

    // Base64 encode the encrypted data to make it easier to store
    return base64_encode($encrypted);
}

function decrypt($decrypt, $key = _ENCRYPTION_KEY_, $iv = _ENCRYPTION_IV_) {
    // Ensure that the IV length is correct for AES-128-CBC (16 bytes)
    $iv_length = openssl_cipher_iv_length('AES-128-CBC');

    if (strlen($iv) !== $iv_length) {
        die("IV length should be $iv_length bytes");
    }

    // Base64 decode the encrypted data
    $decoded = base64_decode($decrypt);

    // Decrypt the data using OpenSSL
    $decrypted = openssl_decrypt($decoded, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);

    // Return the decrypted data, trim to remove padding
    return trim($decrypted);
}

function getFileHashMD5($filepathfull) {
    return md5(getFileContent($filepathfull));
}

function getFileHashSha1($filepathfull) {
    return sha1(getFileContent($filepathfull));
}

function encryptFile($filename, $filepath, $savepath = "") {
    $data = array();

    $isEmpty = strpos($filename, ".") === false;

    if ($isEmpty) {
        return false;
    }

    $data["filename"] = strtok($filename, ".");
    $data["filetype"] = strtok(".");

    if (empty($savepath)) {
        $savepath = $filepath;
    }

    $filepathfull = $filepath . $data["filename"] . "." . $data["filetype"];
    $savepathfull = $savepath . $data["filename"] . "." . $data["filetype"];
// */
    $content = getFileContent($filepathfull);

    $block = array();
    $block["encrypted"] = encrypt($content);
    $block["hash"] = md5($content);
    $block["filename"] = $data["filename"];
    $block["filetype"] = $data["filetype"];
    if (!file_exists($savepath)) {
        mkdir($savepath, 0777, true);
    }

    rewriteFile("$savepathfull.encrypted", json_encode($block));

    $lang = Translation::getLanguageByIso(substr($data["filename"], 1 + strpos($data["filename"], "_")));
    if (empty($lang)) {
        $lang["id_lang"] = 1;
    }
    $iso = "";
    $color = "";

    $tokIso = strtok($block["filename"], "_");
    while (!empty($tokIso)) {
        $color = $iso;
        if (strpos($tokIso, ".") > 0) {
            $iso = substr($tokIso, 0, strpos($tokIso, "."));
        } else {
            $iso = $tokIso;
        }

        $tokIso = strtok("_");
    }
    $validColor = isValidColor($color);
    if (empty($validColor)) {
        $color = "none";
    }
    $sql = "replace into `file` (title_key, filename, filetype, size, filepath, fullpath, created, id_lang, encrypted, color,`hash`) "
            . "VALUES ('" . substr($data["filename"], 0, strpos($data["filename"], "_")) . "', '" . $data["filename"] . "', '" . $data["filetype"] . "', '" . filesize($filepathfull) . "', '" . $savepath . "', '" . $savepathfull . ".encrypted' , '" . date("y-m-d H:i:s") . "', " . $lang["id_lang"] . ",1, '$color', '" . $block["hash"] . "');";
//echo $sql;
    $db = new DBConnector();
    $db->executeQuery($sql);
}

function indexFolderToDB($folder) {
    if ($handle = opendir($folder)) {
        $db = new DBConnector();
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && $entry != "index.php" && !empty($entry)) {
                $filepath = $folder . "/" . $entry;

                if (is_dir($filepath)) {
//echo "directory: " . $filepath . "<br>";
                    indexFolderToDB($filepath);
                } else {

                    $tokFilename = strtok($filepath, "\\/");

                    $filename = "";
                    $filetype = "";
                    while ($tokFilename !== false) {
                        if (!empty($tokFilename)) {
                            $filename = $tokFilename;
                        }
                        $tokFilename = strtok("\\/");
                    }
                    $tokFiletype = strtok($filename, ".");
                    while (!empty($tokFiletype)) {

                        $filetype = $tokFiletype;

                        $tokFiletype = strtok(".");
                    }
                    $fullpath = $filepath;
                    $filesize = filesize($filepath);
                    $filepath = str_replace($filename, "", $filepath);
                    $filename = str_replace("." . $filetype, "", $filename);
                    $datetime = date("y-m-d h:i:s");
                    $encrypted = ($filetype == "encrypted" ? 1 : 0);
                    $hash = getFileHashMD5($fullpath);

                    if (strpos($filename, ".") > 0) {
                        $token = substr($filename, 0, strpos($filename, "."));
                    } else {
                        $token = $filename;
                    }

                    if (strpos($token, "_") > 0) {
                        $token = substr($token, 0, strpos($token, "_"));
                    }

                    $iso = "";
                    $color = "";

                    $tokIso = strtok($filename, "_");
                    while (!empty($tokIso)) {
                        $color = $iso;
                        if (strpos($tokIso, ".") > 0) {
                            $iso = substr($tokIso, 0, strpos($tokIso, "."));
                        } else {
                            $iso = $tokIso;
                        }

                        $tokIso = strtok("_");
                    }
                    $validColor = isValidColor($color);
                    if (empty($validColor)) {
                        $color = "none";
                    }

//echo $filename . "<br>";
//echo $iso . "<br>";
//echo $color . "<br>";
                    $lang = Translation::getLanguageByIso($iso);

                    if (empty($lang)) {
                        $lang["id_lang"] = 1;
                    }

                    $sql = "insert ignore into file(title_key, filename, filetype, size, filepath, fullpath, created, id_lang, encrypted, color,`hash`) values
                            ('" . $token . "', '" . $filename . "', '" . $filetype . "', '" . $filesize . "', '" . $filepath . "', '" . $fullpath . "', '" . $datetime . "', " . $lang["id_lang"] . ", $encrypted, '$color', '$hash')";

                    $db->executeQuery($sql);
                }
            }
        }

        closedir($handle);
    }
}

function decryptFile($file, $path_open, $path_save = "", $newFilename = "") {
    $block = json_decode(getFileContent($path_open . $file . ".encrypted"));
    $content = decrypt($block->encrypted);

    if (empty($path_save)) {
        $path_save = $path_open;
    }

    $filename = strtok($file, ".");
    $filetype = strtok(".");
    if (!file_exists($path_save)) {
        mkdir($path_save, 0777, true);
    }
    if (!empty($newFilename)) {
        $filename = $newFilename;
    }

    rewriteFile($path_save . $filename . "." . $filetype, $content);
}

function encryptFolder($to_encrypt_dir, $save_dir) {
    if ($handle = opendir($to_encrypt_dir)) {

        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $filepath = $to_encrypt_dir . "/" . $entry;
                if (is_dir($filepath)) {
//echo "directory: " . $filepath . "<br>";
                    encryptFolder($to_encrypt_dir . "/" . $entry, $save_dir . "/" . $entry);
                } else {
                    $savepath = $save_dir . "/";
//echo "file: " . $filepath . "<br>";
                    encryptFile($entry, $to_encrypt_dir . "/", $savepath);
                }
            }
        }

        closedir($handle);
    }
}

function getFilesFromDirectory($dir, $include_subfolders = 0) {

    $files = array();
    if (!file_exists($dir)) {
        return array();
    }
    if ($handle = opendir($dir)) {
        /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                if (!is_dir($dir . $entry)) {
                    $files[] = $dir . $entry;
                } else {
                    if (!empty($include_subfolders)) {
                        $files = array_merge($files, getFilesFromDirectory($dir . $entry . "/", 1));
                    }
                }
            }
        }

        closedir($handle);
    }

    return $files;
}

function getRandomFileFromFolder($dir) {
    $files = getFilesFromDirectory($dir);

    return $files[mt_rand(0, count($files) - 1)];
}

function replaceColorInText($text) {
    $text = str_replace("red", $_SESSION["color"], $text);
    $text = str_replace("green", $_SESSION["color"], $text);
    $text = str_replace("blue", $_SESSION["color"], $text);
    $text = str_replace("off", $_SESSION["color"], $text);
    return $text;
}

function isValidColor($color) {
    switch ($color) {
        case "red":
        case "green":
        case "blue":
        case "off":
            return 1;
        default:
            return 0;
    }
}

function getDatetimeFormat() {
    $sql = "select date_format from languages where iso = '" . $_SESSION["lang_iso"] . "'";
    $db = new DBConnector();

    if ($result = ($db->executeQuery($sql))) {
        if ($row = $result->fetch_assoc()) {
            return $row["date_format"];
        }
    }
    return "Y-m-d H:i:s";
}

function getDateFormat() {
    $format = getDatetimeFormat();
    return substr($format, 0, strpos($format, " "));
}

function getDateLetter($ort = "", $lang = "") {
    if (empty($lang)) {
        $lang = $_SESSION["lang_iso"];
    }
    if (empty($lang)) {
        $lang = "de";
    }
    if (empty($date)) {
        $date = date("Y-m-d");
    }
    setlocale(LC_TIME, 'en_CA.UTF-8');
    $day = date("d");
    $month = date("M");
    $year = date("Y");

    switch ($lang) {
        case 'de':
            $date = $day . ". " . Translation::getFileContents("$month") . " " . $year;
            if (!empty($ort)) {
                return $ort . ", den " . $date;
            } else {
                return $date;
            }

        case 'fr':
            $date = $day . " " . Translation::getFileContents("$month") . " " . $year;
            if (!empty($ort)) {
                return $ort . ", le " . $date;
            } else {
                return $date;
            }
        case 'es':
            $date = $day . " de " . Translation::getFileContents("$month") . " de " . $year;
            if (!empty($ort)) {
                return $ort . ", el " . $date;
            } else {
                return $date;
            }
        case 'sv':
            $date = $day . ". " . Translation::getFileContents("$month") . " " . $year;
            if (!empty($ort)) {
                return $ort . ", " . $date;
            } else {
                return $date;
            }
        case 'en':
            return $day . " " . Translation::getFileContents("$month") . " " . $year;
        default:
            return Translation::getFileContents("$month") . " $day, " . $year;
    }
}

function deleteDir($dirPath) {
    if (!is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city" => @$ipdat->geoplugin_city,
                        "state" => @$ipdat->geoplugin_regionName,
                        "country" => @$ipdat->geoplugin_countryName,
                        "country_code" => @$ipdat->geoplugin_countryCode,
                        "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

if (!function_exists('str_contains')) {

    function str_contains(string $haystack, string $needle) {
        return '' === $needle || false !== strpos($haystack, $needle);
    }

}

function debugInput() {
    foreach (func_get_args() as $n) {
        echo $n . "<br>";
    }
}

function debugPost() {
    foreach ($_POST as $key => $value) {
        echo $key . " => " . $value . "<br>";
    }
}

function debugFileUpload() {
    foreach ($_FILES as $key => $value) {
        echo $key . " => " . var_export($value, true) . "<br>";
    }
}

function debugGet() {
    foreach ($_GET as $key => $value) {
        echo $key . " => " . $value . "<br>";
    }
}

function handleLogin() {
    if (isset($_POST["loginButton"])) {
        if (isset($_POST["username"]) && isset($_POST["password"])) {
            $username = $_POST["username"];
            $passwd = $_POST["password"];

            $_SESSION["logged"] = UserHandler::login($username, $passwd);

            if ($_SESSION["logged"]) {
                $_SESSION["user"] = $_POST["username"];
            }
        }
    }
    return false;
}

function printJobDetailPanel($institutuionName, $jobTitle, $url, $duration, $text = "", $keywords = "", $abschluss = "") {
    echo '<div class="details">
    <div class="title">';
    if (!empty($url)) {
        echo '<h3 class="institution-name text-left"><a href="' . $url . '" target="_blank">' . $institutuionName . '</a></h3>';
    } else {
        echo '<h3 class="institution-name text-left">' . $institutuionName . '</h3>';
    }

    if (!empty($jobTitle)) {
        echo '<h4 class="job-title">
                ' . $jobTitle . '
            </h4>';
    }

    if (!empty($abschluss)) {
        echo '<h4 class="text-left education-title">
                ' . $abschluss . '
            </h4>';
    }

    if (!empty($duration)) {
        echo '<div class="additional top">
                ' . $duration . '
            </div>';
    }

    echo '</div>';

    if (!empty($text)) {
        echo '<div class="experience-details">
            ' . $text . '
        </div>';
    }

    if (!empty($keywords)) {
        echo '<div class="experience-details additional text-left">
        ' . $keywords . '
    </div>';
    }
}

function addNewsletterSubscription($email, $firstName, $iso = "en", $gender = "d", $lastName = "", $phone = "", $birthday = "") {
    try {
        // Prepare SQL statement with default values for optional fields
        $sql = 'INSERT INTO `newsletter_subscriptions` (`email`, `firstName`, `iso`, `gender`';

        if (!empty($lastName)) {
            $sql .= ', `lastName`';
        }
        if (!empty($phone)) {
            $sql .= ', `phone`';
        }
        if (!empty($birthday)) {
            $sql .= ', `birthday`';
        }

        $sql .= ') VALUES ("' . addslashes($email) . '", "' . addslashes($firstName) . '", "' . addslashes($iso) . '", "' . addslashes($gender) . '"';

        if (!empty($lastName)) {
            $sql .= ', "' . addslashes($lastName) . '"';
        }
        if (!empty($phone)) {
            $sql .= ', "' . addslashes($phone) . '"';
        }
        if (!empty($birthday)) {
            $sql .= ', "' . addslashes($birthday) . '"';
        }

        $sql .= ');';

        // Create a new instance of DBConnector and execute the query
        $db = new DBConnector();
        $db->executeQuery($sql);

        return ""; // Return an empty string if all went right
    } catch (Exception $e) {
        return "Error: " . $e->getMessage(); // Return the error message if something went wrong
    }
}

function newsletterSubscription_exists($email) {
    try {
        // Prepare SQL statement with default values for optional fields
        $sql = "SELECT count(*) as count FROM `newsletter_subscriptions` where email = '$email'";

        // Create a new instance of DBConnector and execute the query
        $db = new DBConnector();

        if ($result = ($db->executeQuery($sql))) {
            if ($row = $result->fetch_assoc()) {
                return $row["count"];
            }
        }

        return 0; // Return an empty string if all went right
    } catch (Exception $e) {
        return 0; // Return the error message if something went wrong
    }
}
