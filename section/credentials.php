<section id="cv-tab" >
    <script type="text/javascript">
        $(document).ready(function () {
            var h = $("#credentials_content").height();
            $("#cv-tab").css("height", h);
        });

    </script>
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
            if (hasFileAccess($file["id_file"])) {
                $file["filepath"] = str_replace("download", "download/" . getCryptDirname(), $file["filepath"]);
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
            if (hasFileAccess($file["id_file"])) {
                $public_files[] = $file;
            }
        }
    }
    $files = array_merge($public_files, $encrypted_files);
    

    ?>
    <h2 class="text-left"><?php T("credentials_title"); ?></h2>
    <div id="credentials_content" class="col-xs-12 col-sm-12 white_bg">
        <div class="wrap-item studies-wrap referenceText">
            <span><?php T("credentials_text"); ?></span><br><br>
            <?php
            $isLogged = isLogged();
            $emp = empty($isLogged);
            if ($emp) {
                ?>
                <span><?php T("notice_must_be_logged_in_to_see_more"); ?></span><br><br>
            <?php } ?>
            <span><?php T("export_cv_text"); ?></span><br><br>
            <h3><?php T("export_cv_default_title"); ?></h3>
            <span><?php T("export_cv_default_text"); ?></span><br><br>
            <form action="tools/tcpdf/custom/PDFCreator.php" method="get">
                <input id='type' name='type' type="hidden" value='cv'>
                <select id="color">
                    <option <?php
                    if ($_SESSION["color"] == "blue") {
                        echo "selected";
                    }
                    ?> value="5b79a1"><?php echo ucfirst(Translation::getFileContents("blue")); ?></option>
                    <option <?php
                    if ($_SESSION["color"] == "green") {
                        echo "selected";
                    }
                    ?> value="4d8550"><?php echo ucfirst(Translation::getFileContents("green")); ?></option>
                    <option <?php
                        if ($_SESSION["color"] == "red") {
                            echo "selected";
                        }
                    ?> value="ac3e3e"><?php echo ucfirst(Translation::getFileContents("red")); ?></option>
                    <option value="other"><?php T("custom"); ?></option>
                </select>

                <?php
                $col = "#5b79a1";
                if ($_SESSION["color"] == "Green") {
                    $col = "#4d8550";
                } else if ($_SESSION["color"] == "Red") {
                    $col = "#ac3e3e";
                }
                ?>
                <input type="hidden" id="setcolor" name="color" value="<?php echo $col; ?>">
                <input type="color" id="color_chosen" value="<?php echo $col; ?>"><br>
                <select id="lang" name="lang">
<?php foreach (Translation::getLanguages(1) as $lang) { ?>
                        <option value="<?php echo $lang["iso"]; ?>"><?php echo ucfirst(Translation::getFileContents($lang["iso"])); ?></option>
<?php } ?>
                </select>
                <button type="submit" id='generateBtn'><?php T("GenerateCV"); ?></button>
            </form>
        </div>

        <script>
            $('#generateBtn').click(function () {
                $('.fancybox').fancybox();
                $("#inline1").fancybox({
                    closeClick: false,
                    openEffect: 'none',
                    closeEffect: 'none',
                    hideOnOverlayClick: false,
                    hideOnContentClick: false
                }).trigger("click");

            });
        </script>
        <a class="fancybox" href="#inline1" title="Lorem ipsum dolor sit amet">Inline</a>
        <div id="inline1" style="display: none;">
<?php
include 'section/snake.php';
?>
        </div>
        <script type="text/javascript">
            var btn = $('#generateBtn');
            var col = $('#color');
            var setcol = $('#setcolor')
            var lang = $('#language');
            var col_chosen = $('#color_chosen');
            $(document).ready(function () {
                col_chosen.val("#" + col.val());
                setcol.val(col_chosen.val().substr(1, 6));
            });
            col.change(function () {
                //alert();
                if (col.val() !== "other") {
                    col_chosen.val("#" + col.val());
                    setcol.val(col_chosen.val().substr(1, 6));
                }
            });
            col_chosen.change(function () {
                col.val("other"); // 
                setcol.val(col_chosen.val().substr(1, 6));
            });
        </script>
        <div class="col-xs-12 col-sm-12 white_bg">         <?php
                            $mobile_detect = new Mobile_Detect();
        if(!$mobile_detect->isMobile()){
            echo "<h3>";
            T("virtual_cv_title");
            echo "</h3>";
            T("virtual_cv_text");
            echo "<br><br>";
            T("virtual_cv_image");
        }
        ?></div>
        <div id="work-experience" >
            <ul class="item-block item-list">

                <?php
                if (!empty($files) && count($files) > 0) {
                    echo '<h3 class="text-left">' . Translation::getFileContents("Downloads") . '</h3>';
                }
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
                                            <a itemprop="name" target="_blank" href="<?php echo($file["fullpath"]); ?>"><?php echo Translation::getFileContents("Download"); ?></a><br>
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
</section>
