<?php
if(!empty(UserHandler::isAdminLogged()))
{
?>
<section id="preferencesSection">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <h2 class="pad-bt15"><?php T("Urls"); ?></h2>
                <?php
                $languages = Translation::getLanguages();
                $url = getUri() . "?page=setupUrls";

                $showResults = "-1";

                // Used to apply filters
                if (isset($_POST['filterBtn'])) {
                    $showResults = $_POST['filter'];
                } else if (isset($_GET["filter"])) {
                    $showResults = $_GET["filter"];
                }
                // Delete Url
                if (isset($_POST['deleteBtn'])) {
                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">Url #' . $_POST['id_url'] . ": " . $_POST['name_key'] . ' was deleted</div>';
                    UrlHandler::deleteUrlByName($_POST['name_key']);
                }
                // Update Url
                if (isset($_POST['updateBtn'])) {
                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">' . "Update: " . $_POST['id_url'] . " - " . $_POST['name_key'] . ' was updated:<br>';

                    if (isset($_POST["name_key"]) && !empty($_POST["name_key"])) {
                        foreach ($languages as $lang) {
                            $iso = $lang["iso"];
                            $value = $_POST[$iso];
                            echo $iso . ": " . $value . "<br>";
                            Translation::updateTranslation("url_" . $_POST["name_key"], $iso, $value);
                        }
                    }

                    echo "</div>";
                }

                if (isset($_POST['addBtn'])) {
                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">' . "Added: " . $_POST['name_key'] . '<br>';

                    if (isset($_POST["name_key"]) && !empty($_POST["name_key"])) {
                        UrlHandler::addUrl($_POST["name_key"]);
                        foreach ($languages as $lang) {
                            $iso = $lang["iso"];
                            $value = $_POST[$iso];
                            echo $iso . ": " . $value . "<br>";
                            Translation::updateTranslation("url_" . $_POST["name_key"], $iso, $value);
                        }
                    }
                    echo "</div>";

                    // Make Add
                }
                ?>
                <div class="col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 addDataBlock">
                        <h3 class="pad-bt15"><?php T("add_url"); ?></h3>
<?php
echo '<form method="POST" action="' . $url . '">';
echo '
                            <label for="name_key">' . Translation::getFileContents("label_name_key") . '</label>
                            <input required type="text" class="textfield" id="name_key" name="name_key"><br>';
foreach ($languages as $language) {
    echo '<label for="' . $language['iso'] . '">' . Translation::getFileContents("label_url_" . $language['iso']) . '</label>'
    . '<input required type="text" class="textfield" id="' . $language['iso'] . '" name="' . $language['iso'] . '"><br>';
}
?>
                        <script type="text/javascript">
                            function on_en_change()
                            {
<?php
foreach ($languages as $language) {
    if ($language['iso'] != "en" && $language['iso'] != "de") {
        ?>
                                        if ($("#<?php echo $language['iso']; ?>").val() === "")
                                        {
                                            $("#<?php echo $language['iso']; ?>").val($("#en").val());
                                        }

    <?php }
}
?>
                            }
                            $("#de").change(function () {
                                if ($("#en").val() === "")
                                {
                                    $("#en").val($("#de").val());
                                    on_en_change();
                                }

                            });
                            $("#en").change(function () {
                                on_en_change();
                            });
                        </script>
                        <div class="btn-group">
                            <button type="submit" name="addBtn" class="btn btn-sm btn-add"><?php T("add") ?></button>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12">
                    <h3 class="pad-bt15"><?php T("existing_urls"); ?></h3>

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
    $urls = UrlHandler::getUrls();
    foreach ($urls as $editurl) {
        $continue = false;
        if (!empty($showResults)) {
            $continue = true;
            $pos = strpos(strtolower($editurl['name_key']), strtolower($showResults));
            if (!empty($pos) || $pos === 0) {
                $continue = false;
            } else {
                foreach ($languages as $language) {
                    $translation = Translation::getFileContents("url_" . $editurl['name_key'], $language['iso']);
                    $pos = strpos(strtolower($translation), strtolower($showResults));
                    if (!empty($pos) || $pos === 0) {
                        $continue = false;
                        break;
                    }
                }
            }
        }

        if ($continue) {
            continue;
        }

        echo '<form method="POST" action="' . $url . '">';
        echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 addDataBlock">';
        echo '<label class="title">' . $editurl['name_key'] . '</label><br>';
        echo '<input type="hidden" class="textfield" value="' . $editurl['id_url'] . '" id="id_url" name="id_url">';
        echo '<input type="hidden" class="textfield" value="' . $editurl['name_key'] . '" id="name_key" name="name_key">';
        foreach ($languages as $language) {
            $translation = Translation::getFileContents("url_" . $editurl['name_key'], $language['iso']);
            echo '<label for="' . $language['iso'] . '">' . $language['iso'] . '</label>'
            . '<input type="text" value="' . $translation . '" '
            . 'class="textfield" id="' . $language['iso'] . '" name="' . $language['iso'] . '"><br>';
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
                    $("#filter").change(function () {
                        if ($("#filter").val() !== "")
                        {
                            $("#filterBtn").text("<?php echo Translation::getFileContents("filter_results"); ?>");
                        }
                        else
                        {
                            $("#filterBtn").text("<?php echo Translation::getFileContents("show_all"); ?>");
                        }
                    });
                </script>
            </div>
        </div>  
    </div>  
    <script type="text/javascript">
        $("input[type=text]").focus(function () {
            var save_this = $(this);
            window.setTimeout(function () {
                save_this.select();
            }, 100);
        });
    </script>
</div>
</section>
<?php
}
?>