<?php
include_once "tools/functions.php";
include_once "tools/translation.php";

$person = getPersonalData();
$user = UserHandler::isLogged();

echo '<div><br>';
echo '<h3 class="text-sm-start text-center">' . Translation::getFileContents("Contact") . '</h3><br class="hidden_desktop">';

if(!empty($user)){
    TextPlotter::PrintSidePanelDataField(
            SVG::renderHouseSymbol(40, 40), 
            $person["street"]."<br>".$person["zip"] . " " . $person["city"]);
    TextPlotter::PrintSidePanelDataField(
            SVG::renderPhoneSymbol(40, 40), 
            '<a href="tel:' . $person["phone"] . '">' . $person["phone"] . '</a>');

}
TextPlotter::PrintSidePanelDataField(SVG::renderMailSymbol(40, 40), '<a href="mailto:' . $person["email"] . '">' . str_replace("@", "<wbr>@", $person["email"]) . '</a>');

echo '</div>';
?>
