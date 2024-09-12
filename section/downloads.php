<section id="cv-tab" >
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 pageContent">
                <?php
                
                $sql = "select * from file where (color = '" . $_SESSION["color"] . "' or color = 'none')";
                $db = new DBConnector();
                $encrypted_files_db = $db->executeQuery($sql . " and encrypted = 1 order by created desc;");
                $public_files_db = $db->executeQuery($sql . " and encrypted = 0 order by created desc;");

                $encrypted_files = array();
                $public_files = array();

                foreach ($encrypted_files_db as $file) {
                    $filename = replaceColorInText($file["filename"] . "." . $file["filetype"]);
                    if ($file["filetype"] == "php") {
                        continue;
                    }
                    if (!in_array($file, $encrypted_files)) {
                        if (UserHandler::hasFileAccess($file["id_file"])) {
                            $file["filepath"] = str_replace("download", "download/" . UserHandler::getUniqueIdentifier(), $file["filepath"]);
                            $file["fullpath"] = $file["filepath"] . $file["filename"] . "." . $file["filetype"];
                            $encrypted_files[] = $file;
                        }
                    }
                }

                foreach ($public_files_db as $file) {
                    $filename = replaceColorInText($file["filename"] . "." . $file["filetype"]);
                    if ($file["filetype"] == "php") {
                        continue;
                    }
                    if (!in_array($file, $public_files)) {
                        if (UserHandler::hasFileAccess($file["id_file"])) {
                            $public_files[] = $file;
                        }
                    }
                }
                $files = array_merge($public_files, $encrypted_files);
                ?>
                <h2 class="text-left title-text"><?php T("credentials_title"); ?></h2>
                <div id="credentials_content" class="col-xs-12 col-sm-12 white_bg">
                    <div class="wrap-item studies-wrap referenceText">
                        <span><?php T("credentials_text"); ?></span><br><br>
                        <?php
                        $isLogged = UserHandler::isLogged();
                        $emp = empty($isLogged);
                        if ($emp) {
                            ?>
                            <span><?php T("notice_must_be_logged_in_to_see_more"); ?></span><br><br>
                        <?php } ?>

                        <h3><?php T("export_cv_title"); ?></h3>
                        <span><?php T("export_cv_text"); ?></span><br><br>
                        <?php
                        include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cv_generator.php';
                        if (!empty($files) && count($files) > 0) {
                            echo '<h3 class="text-left">' . Translation::getFileContents("Downloads") . '</h3><br>';
                        }
                        ?>
                        <div id="work-experience" >
                            <ul class="item-block item-list">

                                <?php
                                foreach ($files as $file) {
                                    ?>
                                    <li>

                                        <div class="editor-content">
                                            <div class="cv-entry clfx edit-mode toggle-box ">
                                                <div class="duration">
                                                    <div class="circle"></div>
                                                    <div class="label">
                                                        <div>
                                                            <a itemprop="name" target="_blank" href="<?php echo($file["fullpath"]); ?>">
    <?php
    $funcname = "renderFileSymbol" . strtoupper($file["filetype"]);
    echo SVG::$funcname("40", "50");
    ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="details">
                                                    <div class="title">
                                                        <a itemprop="name" target="_blank" href="<?php echo($file["fullpath"]); ?>"><h3 itemprop="filename" class="job-title text-left"><?php T("FILENAME_" . $file["title_key"]); ?> <?php
                                                            $language = Translation::getLanguageByID($file["id_lang"]);
                                                            if ($language["iso"] !== $_SESSION["lang_iso"]) {
                                                                echo "(" . Translation::getFileContents($language["iso"]) . ")";
                                                            }
    ?></h3></a>
                                                                <?php
                                                                $creator = Translation::getFileContents("FILECREATOR_" . $file["title_key"]);
                                                                if (!empty($creator)) {
                                                                    echo "<h4 class='text-left'>" . $creator . "</h4>";
                                                                }
                                                                ?>
                                                    </div>
                                                        <?php
                                                        $desc = Translation::getFileContents("FILEDESCRIPTION_" . $file["title_key"]);
                                                        if (!empty($desc)) {
                                                            ?>
                                                        <div class="file-details">
                                                            <div>
        <?php echo $desc; ?>
                                                            </div>
                                                        </div>
    <?php } ?>
                                                    <div class="additional text-left">
                                                        <div>
                                                            <br>
                                                            <a itemprop="name" target="_blank" href="<?php echo($file["fullpath"]); ?>">
                                                                <button class="btn pdf-download-btn" type="button" id="generateBtn"><?php echo Translation::getFileContents("Download"); ?></button>
                                                            </a><br>
                                                            <br>
    <?php
    if ($file["color"] != "none") {
        echo Translation::getFileContents("Color") . ": " . ucfirst(Translation::getFileContents($file["color"])) . "<br>";
    }
    ?>
                                                            <?php echo Translation::getFileContents("Language"); ?>: <?php echo Translation::getFileContents($language["iso"]); ?><br>
                                                            <?php echo Translation::getFileContents("Created"); ?>: <?php echo date(getDateFormat(), strtotime($file["created"])); ?><br>
                                                            <?php echo Translation::getFileContents("Filesize"); ?>: <?php echo number_format($file["size"] / 1024 / 1024, 2); ?> MB<br>
                                                            <?php echo Translation::getFileContents("Filetype"); ?>: <?php echo strtoupper($file["filetype"]); ?><br>
                                                            MD5 Hash: <?php echo $file["hash"]; ?><br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
<?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
