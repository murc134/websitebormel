<?php
if(!empty(UserHandler::isAdminLogged()))
{
?>
<section id="preferencesSection">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <h2 class="pad-bt15"><?php T("Translations"); ?></h2>
                <?php
                
                $languages = Translation::getLanguages();
                $url = getUri() . "?page=setupTranslations";

                $showResults = "-1";

                // Used to apply filters
                if (isset($_POST['filterBtn'])) {
                    $showResults = $_POST['filter'];
                }
                else if(isset($_GET["filter"]))
                {
                    $showResults = $_GET["filter"];
                }

                // Delete Keyword
                if (isset($_POST['deleteBtn'])) {
                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">Translation "' . $_POST['name_key'] . '" was deleted</div>';
                    if (isset($_POST["name_key"]) && !empty($_POST["name_key"])) {
                        Translation::deleteTranslation($_POST["name_key"]);
                    }
                }
                // Update Keyword
                if (isset($_POST['updateBtn'])) {

                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">' . "Update: " . $_POST['name_key'] . ' was updated:<br>';

                    if (isset($_POST["name_key"]) && !empty($_POST["name_key"])) {
                        foreach ($languages as $lang) {
                            $iso = $lang["iso"];
                            $value = $_POST[$iso];
                            echo $iso . ": " . $value . "<br>";
                            Translation::updateTranslation($_POST["name_key"], $iso, $value);
                        }
                    }

                    echo "</div>";
                }
                if (isset($_POST['addBtn'])) {
                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">' . "Add: " . $_POST['name_key'] . ' was added:<br>';

                    if (isset($_POST["name_key"]) && !empty($_POST["name_key"])) {
                        Translation::set($_POST["name_key"]);
                        foreach ($languages as $lang) {
                            $iso = $lang["iso"];
                            $value = $_POST[$iso];
                            echo $iso . ": " . $value . "<br>";
                            Translation::updateTranslation($_POST["name_key"], $iso, $value);
                        }
                    }

                    echo "</div>";
                }
                ?>
                <div class="col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 addDataBlock">
                        <h3 class="pad-bt15"><?php T("add_translation"); ?></h3>
<?php
echo '<form method="POST" action="' . $url . '">';
echo '
                            <label for="name_key">' . Translation::getFileContents("label_name_key") . '</label>
                            <input required type="text" class="textfield" id="name_key" name="name_key"><br>';
foreach ($languages as $language) {
    echo '<label for="' . $language['iso'] . '">' . $language['iso'] . '</label>'
    . '<textarea class="textfield" id="' . $language['iso'] . '" name="' . $language['iso'] . '"></textarea><br>';
}
?>
<script type="text/javascript">
                            $( "#name_key" ).change(function() {
                                <?php foreach ($languages as $language) { ?>
                                if($( "#<?php echo $language['iso']; ?>" ).val() === "")
                                {
                                    $( "#<?php echo $language['iso']; ?>" ).val($( "#name_key" ).val());
                                }
                                <?php } ?>
                            });
                            $( "#en" ).change(function() {
                                <?php foreach ($languages as $language) {
                                    if($language['iso'] != "en" && $language['iso'] != "de") {
                                    ?>
                                if($( "#<?php echo $language['iso']; ?>" ).val() === "" || $( "#<?php echo $language['iso']; ?>" ).val() === $( "#name_key" ).val())
                                {
                                    $( "#<?php echo $language['iso']; ?>" ).val($( "#en" ).val());
                                }
                                
                                <?php }} ?>
                            });
                            
                            
                            
                        </script>
                        <div class="btn-group">
                            <button type="submit" name="addBtn" class="btn btn-sm btn-add"><?php T("add") ?></button>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12">
                    <h3 class="pad-bt15"><?php T("existing_translations"); ?></h3>

<?php
echo '<form method="POST" action="' . $url . '">';
echo '<div class="col-xs-12 col-sm-12">';
echo '<label class="title">Filter</label><br>';
echo '<input type="text" class="textfield" value="' . (!empty($showResults) && $showResults != "-1" ? $showResults : "") . '" id="filter" name="filter">';
echo '<div class="btn-group">
                                <button type="submit" id="filterBtn" name="filterBtn" class="btn btn-sm btn-add">' . Translation::getFileContents("show_all") . '</button>
                            </div>';
echo '</div>';
echo '</form>';

if ($showResults != "-1") {
    $translations = Translation::getTranslationKeys();
    foreach ($translations as $translation) {

        $continue = false;

        if (!empty(strpos($translation, ".php"))) {
            //echo "contains PHP: " . $translation . "<br>";
            continue;
        }
        if (!empty($showResults)) {
            $continue = true;
            $pos = strpos(strtolower($translation), strtolower($showResults));
            if (!empty($pos) || $pos === 0) {
                $continue = false;
                //echo "key contains filter: " . strpos(strtolower($translation), strtolower($showResults)) . " / " . $translation . "<br>";
            } else {
                foreach ($languages as $language) {
                    $t = Translation::getFileContents($translation, $language['iso']);
                    $pos = strpos(strtolower($t), strtolower($showResults));
                    if (!empty($pos) || $pos === 0) {
                        $continue = false;
                        //echo "translation contains filter: " . strpos(strtolower($t), strtolower($showResults)) . " / " . $translation . " | " . $t . "<br>";
                        break;
                    }
                }
            }
        }

        if ($continue) {
            //echo "hide ".$translation." / " . $t . "<br>";
            continue;
        }


        echo '<form method="POST" action="' . $url . '">';
        echo '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 addDataBlock">';
        echo '<label class="title">' . $translation . '</label><br>';
        echo '<input type="hidden" class="textfield" value="' . $translation . '" id="name_key" name="name_key">';
        foreach ($languages as $language) {
            $t = Translation::getFileContents($translation, $language['iso']);
            echo '<label for="' . $language['iso'] . '">' . $language['iso'] . '</label>'
            . '<textarea class="textfield" id="' . $language['iso'] . '" name="' . $language['iso'] . '">' . $t . '</textarea><br>';
        }

        echo '<div class="btn-group">
                                <button type="submit" name="deleteBtn" class="btn btn-sm btn-delete">' . Translation::getFileContents("delete") . '</button>
                                <button type="submit" name="updateBtn" class="btn btn-sm btn-update">' . Translation::getFileContents("update") . '</button>
                            </div>';

        echo '</div>';
        echo '</form>';
    }
}
?>
                </div>
                <script type="text/javascript">
                    $( "#filter" ).change(function() {
                        if($( "#filter" ).val() !== "")
                        {
                            $( "#filterBtn" ).text("<?php echo Translation::getFileContents("filter_results"); ?>");
                        }
                        else
                        {
                            $( "#filterBtn" ).text("<?php echo Translation::getFileContents("show_all"); ?>");
                        }
                    });
                    </script>
            </div>
        </div>  
    </div>  
    <script type="text/javascript">
                                $("input[type=text]").focus(function() { 
                                    var save_this = $(this);
                                    window.setTimeout (function(){ 
                                       save_this.select(); 
                                    },100);
                                });
    </script>
</div>
</section>
<?php
}
?>