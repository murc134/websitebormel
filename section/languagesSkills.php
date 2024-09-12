<?php

include_once "tools/functions.php";
include_once "tools/translation.php";
$languages = Translation::getLanguages(1, "asc", true);
if (count($languages) > 1) {
    echo '<div><br>';
    echo '<h3 class="text-sm-start text-center">' . Translation::getFileContents("languages") . '</h3><br class="hidden_desktop">';
    foreach ($languages as $lang) {
        $function = "renderLangSkillFlag" . ucfirst($lang["iso"]);
        echo TextPlotter::PrintSidePanelDataField(SVG::$function(50, 50), Translation::getFileContents($lang["level"]));
    }
    echo '</div>';
}
?>
