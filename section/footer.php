<?php
$person = getPersonalData();
?>

<section id="footer" class="section-padding" style="">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <div class="wrap-item">
                <div class=" col-xs-12 col-lg-12 fontFooter">
                    <?php
                    $person = getPersonalData();
                    $user = UserHandler::isLogged();

                    echo '<div><br>';
                    echo '<h3 class="text-sm-start text-center">' . Translation::getFileContents("Contact") . '</h3><br class="hidden_desktop">';

                    if (!empty($user)) {
                        TextPlotter::PrintSidePanelDataField(
                                SVG::renderHouseSymbol(40, 40),
                                $person["street"] . "<br>" . $person["zip"] . " " . $person["city"]);
                        TextPlotter::PrintSidePanelDataField(
                                SVG::renderPhoneSymbol(40, 40),
                                '<a href="tel:' . $person["phone"] . '">' . $person["phone"] . '</a>');
                    }
                    TextPlotter::PrintSidePanelDataField(SVG::renderMailSymbol(40, 40), '<a href="mailto:' . $person["email"] . '">' . $person["email"] . '</a>');

                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>
        <br class="hidden_desktop hidden_notebook">
        <br class="hidden_desktop">

        <div class="col-xs-12 col-sm-6">
            <div class="wrap-item">
                <div class=" col-xs-12 col-lg-12 fontFooter">
                    <?php
                    echo '<div><br>';
                    echo '<h3 class="text-sm-start text-center">' . Translation::getFileContents("Social_Media") . '</h3><br class="hidden_desktop">';

// Define an array of social media platforms and their respective icons and labels
                    $socialMediaPlatforms = [
                        "xing" => ["label" => "Xing", "icon" => "xing.svg"],
                        "linkedIn" => ["label" => "LinkedIn", "icon" => "linkedIn.svg"],
                        "youtube" => ["label" => "YouTube", "icon" => "Youtube.svg"]
                    ];

// Iterate through the array and render each platform if the link is not empty
                    foreach ($socialMediaPlatforms as $platform => $data) {
                        if (!empty($person[$platform])) {
                            TextPlotter::PrintSidePanelDataField(
                                    '<a href="' . htmlspecialchars($person[$platform]) . '" target="_blank">
                <img class="iconfooter" src="img/icons/' . htmlspecialchars($data["icon"]) . '" alt="' . htmlspecialchars($data["label"]) . '">
            </a>',
                                    '<a href="' . htmlspecialchars($person[$platform]) . '" target="_blank">' . htmlspecialchars($data["label"]) . '</a>'
                            );
                        }
                    }

                    echo '</div>';
                    ?>

                </div>
            </div>
        </div>
        <br class="hidden_desktop hidden_notebook">
        <br class="hidden_desktop">
    </div>
</section>