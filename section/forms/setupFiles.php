<?php
if(!empty(UserHandler::isAdminLogged()))
{
?>
<section id="preferencesSection">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <h2 class="pad-bt15"><?php T("Files"); ?></h2>
                <?php
                $languages = Translation::getLanguages();
                $url = getUri() . "?page=setupFiles";

                $showResults = "-1";

                // Used to apply filters
                if (isset($_POST['filterBtn'])) {
                    $showResults = $_POST['filter'];
                } else if (isset($_GET["filter"])) {
                    $showResults = $_GET["filter"];
                }
                // Delete File
                if (isset($_POST['deleteBtn'])) {
                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">File #' . $_POST['id_file'] . ": " . $_POST['title_key'] . ' was deleted</div>';
                    FileHandler::deleteFileByName($_POST['title_key']);
                }
                // Update File
                if (isset($_POST['updateBtn'])) {
                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">' . "Update: " . $_POST['id_file'] . " - " . $_POST['title_key'] . ' was updated:<br>';

                    if (isset($_POST["title_key"]) && !empty($_POST["title_key"])) {
                        //$created = $_POST["filedate"];
                        $key = str_replace(" ", "_", $_POST["title_key"]);
                        foreach ($languages as $language) 
                        {
                            Translation::updateTranslation("FILEDESCRIPTION_" . $key, $language["iso"], $_POST["filedescription_" . $language["iso"]]);
                            Translation::updateTranslation("FILECREATOR_" . $key, $language["iso"], $_POST["filecreator_" . $language["iso"]]);
                            Translation::updateTranslation("FILENAME_" . $key, $language["iso"], $_POST["displayname" . $language["iso"]]);
                        }
                    }
//                    if (isset($_POST["title_key"]) && !empty($_POST["title_key"])) {
//                        foreach ($languages as $lang) {
//                            $iso = $lang["iso"];
//                            $value = $_POST[$iso];
//                            echo $iso . ": " . $value . "<br>";
//                            Translation::updateTranslation($_POST["title_key"], $iso, $value);
//                        }
//                    }

                    echo "</div>";
                }

                if (isset($_POST['addBtn'])) {

                    if (empty($_FILES["source"])) {
                        echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">';
                        echo "Error: no file was uploaded!";
                        echo '</div>';
                    } else {

                        $encrypted = !empty($_POST["encrypted"]);
                        $key = str_replace(" ", "_", $_POST["title_key"]);
                        $id_lang = $_POST["language"];
                        $created = $_POST["filedate"];

                        $files = array();

                        // Upload all files to download folder
                        // If encrypted, upload to temp folder

                        $fileupload;
                        $targetfilename = $_POST["filename"];

                        if (!empty($targetfilename)) {
                            $fileupload = FileHandler::uploadFile("source", $targetfilename);
                        } else {
                            $fileupload = FileHandler::uploadFile("source");
                        }

                        if (empty($fileupload[0])) {
                            echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">';
                            echo "Error during fileupload!";
                        } else {
                            echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">';

                            $dir = $fileupload[1];
                            $filename = $fileupload[2];

                            $data = array();

                            if ($encrypted) {

                                $data = FileHandler::encryptFile($filename, $dir);
                                $data["title_key"] = $key;
                                $data["color"] = "none";

                                $data["filepath"] = $dir;
                                $data["created"] = $created;
                                $data["id_lang"] = $id_lang;
                                $data["encrypted"] = 1;

                                unlink($dir . $filename);
                                $filename.= ".encrypted";
                                $data["fullpath"] = $dir . $filename;
                                // Perform encryption and move to folder
                            } else {
                                $data["title_key"] = $key;
                                $data["color"] = "none";
                                $data["fullpath"] = $dir . $filename;
                                $data["filepath"] = $dir;
                                $data["created"] = $created;
                                $data["id_lang"] = $id_lang;
                                $data["encrypted"] = 0;

                                $content = getFileContent($dir . $filename);

                                $data["filename"] = strtok($filename, ".");
                                $data["filetype"] = strtok(".");
                                $data["hash"] = md5($content);
                                $data["size"] = filesize($dir . $filename);
                            }
                            Translation::set("FILECREATOR_" . $key);
                            Translation::set("FILEDESCRIPTION_" . $key);
                            Translation::set("FILENAME_" . $key);

                            foreach ($languages as $language) {
                                Translation::updateTranslation("FILEDESCRIPTION_" . $key, $language["iso"], $_POST["filedescription_" . $language["iso"]]);
                                Translation::updateTranslation("FILECREATOR_" . $key, $language["iso"], $_POST["filecreator_" . $language["iso"]]);
                                Translation::updateTranslation("FILENAME_" . $key, $language["iso"], $_POST["displayname" . $language["iso"]]);
                            }

                            echo FileHandler::addFileByArrayData($data);
                        }
                        echo "</div>";
                    }
                    // Make Add
                }
                ?>
                <div class="col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 addDataBlock">
                        <h3 class="pad-bt15"><?php T("add_file"); ?></h3>
                <?php
                echo '<form enctype="multipart/form-data" method="POST" action="' . $url . '">';
                echo '
                                                    <label for="title_key">' . Translation::getFileContents("label_name_key") . '</label>
                                                    <input required type="text" class="textfield" id="title_key" name="title_key"><br>
                                                    <label for="encrypted">' . Translation::getFileContents("Encryption") . '</label><br><input type="checkbox" id="encrypted" name="encrypted">&nbsp;<label id="label_encrypted" for="encrypted">' . Translation::getFileContents("public") . '</label><br>
                                                <script type="text/javascript">
                                                $("#encrypted").change(function () {
                                                    if ($("#encrypted").is(":checked")) 
                                                    {
                                                        $("#label_encrypted").text("' . Translation::getFileContents("encrypted") . '");
                                                    }
                                                    else
                                                    {
                                                        $("#label_encrypted").text("' . Translation::getFileContents("public") . '");
                                                    }
                                                });
                                                </script>';
                echo '<label for="language">' . Translation::getFileContents("Language_original") . '</label><br>
                                                <select class="textfield" name="language" id="language">';
                foreach ($languages as $language) {
                    echo '<option value="' . $language['id_lang'] . '">' . Translation::getFileContents($language['iso']) . '</option>';
                }
                echo '</select><br><br>';
                echo '<label for="filedate">' . Translation::getFileContents("label_creation_date") . '</label><input type="date" value="' . date('Y-m-d') . '" class="textfield" id="filedate" name="filedate"><br>';
                echo '<br>';
                foreach ($languages as $language) {
                    echo '<label required for="displayname' . $language['iso'] . '">' . Translation::getFileContents("label_displayname_" . $language['iso']) . '</label>'
                    . '<input type="text" class="textfield" id="displayname' . $language['iso'] . '" name="displayname' . $language['iso'] . '"><br>';
                }
                ?>
                        <script type="text/javascript">
                            $("#title_key").change(function () {
                        <?php foreach ($languages as $language) { ?>
                                    if ($("#displayname<?php echo $language['iso']; ?>").val() === "")
                                    {
                                        $("#displayname<?php echo $language['iso']; ?>").val($("#title_key").val());
                                    }
<?php } ?>
                            });
                            $("#displaynameen").change(function () {
<?php
foreach ($languages as $language) {
    if ($language['iso'] != "en" && $language['iso'] != "de") {
        ?>
                                        if ($("#displayname<?php echo $language['iso']; ?>").val() === "" || $("#displayname<?php echo $language['iso']; ?>").val() === $("#title_key").val())
                                        {
                                            $("#displayname<?php echo $language['iso']; ?>").val($("#displaynameen").val());
                                        }
        <?php
    }
}
?>
                            });
                        </script>
                        <br>
<?php
echo '<label class="labelsource" id="labelsource" name="labelsource" for="source">' . Translation::getFileContents("label_source") . '</label>';
echo '<input required type="file" class="textfield " id="source" name="source">';

echo '<label id="labelfilename" for="filename">' . Translation::getFileContents("label_filename") . '</label>'
 . '<input type="text" class="textfield" id="filename" name="filename">';
?>
                        <br><br>

                        <?php
                        foreach ($languages as $language) {
                            echo '<label for="filecreator_' . $language['iso'] . '">' . Translation::getFileContents("label_filecreator_" . $language['iso']) . '</label>'
                            . '<input type="text" class="textfield" id="filecreator_' . $language['iso'] . '" name="filecreator_' . $language['iso'] . '"><br>';
                        }
                        ?>

                        <script type="text/javascript">
                            function on_change_english_filecreator()
                            {
                        <?php
                        foreach ($languages as $language) {
                            if ($language['iso'] != "en" && $language['iso'] != "de") {
                                ?>
                                        if ($("#filecreator_<?php echo $language['iso']; ?>").val() === "" || $("#filecreator_<?php echo $language['iso']; ?>").val() === $("#filecreator_de").val())
                                        {
                                            $("#filecreator_<?php echo $language['iso']; ?>").val($("#filecreator_en").val());
                                        }

        <?php
    }
}
?>
                            }
                            $("#filecreator_de").change(function () {
                                if (!$("#filecreator_en").val())
                                {
                                    $("#filecreator_en").val($("#filecreator_de").val());
                                    on_change_english_filecreator();
                                }
                            });
                            $("#filecreator_en").change(function () {
                                on_change_english_filecreator();
                            });
                        </script>
<?php
foreach ($languages as $language) {
    echo '<label for="filedescription_' . $language['iso'] . '">' . Translation::getFileContents("label_filedescription_" . $language['iso']) . '</label>'
    . '<textarea class="textfield" id="filedescription_' . $language['iso'] . '" name="filedescription_' . $language['iso'] . '"></textarea><br>';
}
?>
                        <script type="text/javascript">
                            $("#filedescription_en").change(function () {
                        <?php
                        foreach ($languages as $language) {
                            if ($language['iso'] != "en" && $language['iso'] != "de") {
                                ?>
                                        if ($("#filedescription_<?php echo $language['iso']; ?>").text() === "")
                                        {
                                            $("#filedescription_<?php echo $language['iso']; ?>").text($("#filedescription_en").text());
                                        }

        <?php
    }
}
?>
                            });
                        </script>

                        <div class="btn-group">
                            <button type="submit" name="addBtn" class="btn btn-sm btn-add"><?php T("add") ?></button>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12">
                    <h3 class="pad-bt15"><?php T("existing_files"); ?></h3>

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
    $files = FileHandler::getFiles();
    foreach ($files as $file) {
        $continue = false;
        if (!empty($showResults)) {
            $continue = true;
            $pos = strpos(strtolower($file['title_key']), strtolower($showResults));
            if (!empty($pos) || $pos === 0) {
                $continue = false;
            } else {
                foreach ($languages as $language) {
                    $translation = Translation::getFileContents("FILENAME_" . $file['title_key'], $language['iso']);
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

        echo '<form method="POST" enctype="multipart/form-data" action="' . $url . '">';
        echo '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 addDataBlock">';
        echo '<label class="title">' . $file['title_key'] . '</label><br>';
        echo '<input type="hidden" class="textfield" value="' . $file['id_file'] . '" id="id_file" name="id_file">';
        echo '<input type="hidden" class="textfield" value="' . $file['title_key'] . '" id="title_key" name="title_key">';
        if (empty($file['encrypted'])) {
            echo '<a target="_BLANK" href="' . $file['fullpath'] . '">';
            echo '<button type="button" id="downloadBtn" name="downloadBtn" class="btn btn-sm btn-download">' . Translation::getFileContents("Download") . '</button>';
            echo '</a>';
        } else {
            // TODO: Sowieso nur möglich wenn angemeldet. Im Falle von Login
            echo '<button type="button" id="decryptBtn" name="decryptBtn" class="btn btn-sm btn-decrypt">TODO: ' . Translation::getFileContents("Decrypt_and_Download") . '</button>';
        }
        echo '<br><br>';
        echo '<label>' . Translation::getFileContents("Filepath") . '</label>: ';
        echo $file['fullpath'] . '<br>';
        echo '<label>' . Translation::getFileContents("Filetype") . '</label>: ';
        echo $file['filetype'] . '<br>';
        echo '<label>' . Translation::getFileContents("Filesize") . '</label>: ';
        echo number_format($file['size'] / 1024 / 1024, 2) . ' MB<br>';
        $created = substr($file['created'], 0, strpos($file['created'], " "));
        echo '<label for="filedate">' . Translation::getFileContents("label_creation_date") . ':&nbsp;' . '</label>' . $created . '<br>';
        echo '<label for="language">' . Translation::getFileContents("Language_original") . '</label><br>
                        <select disabled class="textfield" name="language" id="language">';
        foreach ($languages as $language) {
            $selected = $language['id_lang'] === $file['id_lang'] ? "selected" : "";
            echo '<option ' . $selected . ' value="' . $language['id_lang'] . '">' . Translation::getFileContents($language['iso']) . '</option>';
        }
        echo '</select><br><br>';

        if (empty($file['encrypted'])) {
            echo '<input disabled type="checkbox" id="encrypted_' . $file['title_key'] . '" name="encrypted_' . $file['title_key'] . '">&nbsp;<label id="label_encrypted" for="encrypted_' . $file['title_key'] . '">' . Translation::getFileContents("public") . '</label><br>';
        } else {
            echo '<input disabled checked type="checkbox" id="encrypted_' . $file['title_key'] . '" name="encrypted_' . $file['title_key'] . '">&nbsp;<label id="label_encrypted" for="encrypted_' . $file['title_key'] . '">' . Translation::getFileContents("encrypted") . '</label><br>';
        }

//        echo '<label class="labelsource" id="labelsource" name="labelsource" for="source">' . Translation::getFileContents("label_source") . '</label>';
//        echo '<input type="file" class="textfield " id="source" name="source">';
//                            
//        echo '<label id="labelfilename" for="filename">' . Translation::getFileContents("label_filename") . '</label>'
//           . '<input type="text" class="textfield" id="filename" name="filename">';

        foreach ($languages as $language) {
            $translation = Translation::getFileContents('FILENAME_' . $file['title_key'], $language['iso']);
            echo '<label for="displayname' . $language['iso'] . '">' . Translation::getFileContents("label_displayname_" . $language['iso']) . '</label>'
            . '<textarea class="textfield" id="displayname' . $language['iso'] . '" name="displayname' . $language['iso'] . '">' . $translation . '</textarea>';
        }

        foreach ($languages as $language) {
            $translation = Translation::getFileContents('FILECREATOR_' . $file['title_key'], $language['iso']);
            echo '<label for="filecreator_' . $language['iso'] . '">' . Translation::getFileContents("label_filecreator_" . $language['iso']) . '</label>'
            . '<textarea class="textfield" id="filecreator_' . $language['iso'] . '" name="filecreator_' . $language['iso'] . '">' . $translation . '</textarea>';
        }

        foreach ($languages as $language) {
            $translation = Translation::getFileContents('FILEDESCRIPTION_' . $file['title_key'], $language['iso']);
            echo '<label for="filedescription_' . $language['iso'] . '">' . Translation::getFileContents("label_filedescription_" . $language['iso']) . '</label>'
            . '<textarea class="textfield" id="filedescription_' . $language['iso'] . '" name="filedescription_' . $language['iso'] . '">' . $translation . '</textarea>';
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