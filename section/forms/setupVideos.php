<?php
if(!empty(UserHandler::isAdminLogged()))
{
?>
<section id="preferencesSection">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <h2 class="pad-bt15"><?php T("Videos"); ?></h2>

                <?php
                $languages = Translation::getLanguages();
                $url = getUri() . "?page=setupVideos";

                $showResults = "-1";

                function upload_videos_by_source() {
                    global $languages;

                    $key = VideoHandler::addVideo($_POST["name_key"], $_POST["language"]);

                    switch ($_POST["multilingual"]) {
                        case "singlelingual":
                            $videodata = VideoHandler::uploadVideoFile("sourcede");

                            if ($videodata[0]) {
                                foreach ($languages as $language) {
                                    Translation::updateTranslation($key, $language["iso"], $_POST[$language["iso"]]);
                                    Translation::updateTranslation($key . "_url", $language["iso"], $videodata[1]);
                                }
                                echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">' . $videodata[2] . '</div>';
                            } else {
                                echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">' . $videodata[2] . '</div>';
                            }

                            break;
                        default: // // Multilingual

                            $videodatadefault = VideoHandler::uploadVideoFile("sourcede");

                            if ($videodatadefault[0]) {
                                foreach ($languages as $language) {
                                    $videodata = VideoHandler::uploadVideoFile("source" . $language["iso"]);
                                    if ($videodata[0]) {
                                        if ($language["iso"] === "en") {
                                            $videodatadefault = $videodata;
                                        }
                                        Translation::updateTranslation($key, $language["iso"], $_POST[$language["iso"]]);
                                        Translation::updateTranslation($key . "_url", $language["iso"], $videodata[1]);
                                        echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">' . $videodata[2] . '</div>';
                                    } else {
                                        Translation::updateTranslation($key, $language["iso"], $_POST[$language["iso"]]);
                                        Translation::updateTranslation($key . "_url", $language["iso"], $videodatadefault[1]);
                                        echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">' . $videodatadefault[2] . '</div>';
                                    }
                                }
                            } else {
                                echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">' . $videodatadefault[2] . '</div>';
                            }
                            break;
                    }
                }

                function upload_videos_by_url() {
                    global $languages;

                    $key = VideoHandler::addVideo($_POST["name_key"], $_POST["language"]);
                    switch ($_POST["multilingual"]) {
                        case "singlelingual":
                            $url = $_POST["urlde"];
                            if (!empty($url)) {
                                foreach ($languages as $language) {
                                    Translation::updateTranslation($key, $language["iso"], $_POST[$language["iso"]]);
                                    Translation::updateTranslation($key . "_url", $language["iso"], $url);
                                }
                                echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">Successful Video Upload:<br>' . $url . '</div>';
                            } else {
                                echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">Failed Video Upload: No URL given</div>';
                            }
                            break;
                        default: // // Multilingual
                            $urldefault = $_POST["urlde"];
                            if (!empty($urldefault)) {
                                foreach ($languages as $language) {
                                    $url = $_POST["url" . $language["iso"]];
                                    if (!empty($url)) {

                                        Translation::updateTranslation($key, $language["iso"], $_POST[$language["iso"]]);
                                        Translation::updateTranslation($key . "_url", $language["iso"], $url);
                                    } else {
                                        Translation::updateTranslation($key, $language["iso"], $_POST[$language["iso"]]);
                                        Translation::updateTranslation($key . "_url", $language["iso"], $urldefault);
                                    }
                                }
                                echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">Successful Video Upload:<br>' . $url . '</div>';
                            } else {
                                echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">Failed Video Upload: No URL given</div>';
                            }
                            break;
                    }
                }

                // Used to apply filters
                if (isset($_POST['filterBtn'])) {
                    $showResults = $_POST['filter'];
                }else if(isset($_GET["filter"]))
                {
                    $showResults = $_GET["filter"];
                }

                // Delete Video
                if (isset($_POST['deleteBtn'])) {
                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">Video #' . $_POST['id_video'] . ": " . $_POST['name_key'] . ' was deleted</div>';
                    VideoHandler::deleteVideoByName($_POST['name_key']);
                    Translation::deleteTranslation("video_" . $_POST['name_key']);
                    unlink(Translation::getFileContents("video_" . $_POST['name_key'] . "_url"));
                    Translation::deleteTranslation("video_" . $_POST['name_key'] . "_url");
                }

                if (isset($_POST['addBtn'])) {
                    switch ($_POST["source"]) {
                        case "Source":
                            upload_videos_by_source();
                            break;
                        default: // URL
                            upload_videos_by_url();
                            break;
                    }
//                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">' . "Added: " . $_POST['name_key'] . '<br>';
//
//                    if (isset($_POST["name_key"]) && !empty($_POST["name_key"])) {
//                        //VideoHandler::addVideo($_POST["name_key"]);
//                        foreach ($languages as $lang) {
//                            $iso = $lang["iso"];
//                            $value = $_POST[$iso];
//                            echo $iso . ": " . $value . "<br>";
//                            //Translation::updateTranslation($_POST["name_key"], $iso, $value);
//                        }
//                    }
//                    echo "</div>";
                    // Make Add
                }
                ?>
                <div class="col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 addDataBlock">
                        <h3 class="pad-bt15"><?php T("add_video"); ?></h3>
                        <div class="tab">
                            <button class="tablinks" onclick="openTab('Source')"><?php T("file"); ?></button>
                            <button class="tablinks" onclick="openTab('URL')"><?php T("url"); ?></button>
                        </div>
                        <br>
                        <?php
                        echo '<form enctype="multipart/form-data" method="POST" action="' . $url . '">';
                        ?>
                        <fieldset>
                            <input onclick="setLanguagePreferences('singlelingual')" checked="1" type="radio" id="single_lingual" name="multilingual" value="singlelingual">
                            <label class="text-normal-font-weight" for="single_lingual"><?php T("single_lingual"); ?></label> 
                            <br>
                            <input onclick="setLanguagePreferences('multilingual')" type="radio" id="multi_lingual" name="multilingual" value="multilingual">
                            <label class="text-normal-font-weight" for="multi_lingual"><?php T("multi_lingual"); ?></label>
                        </fieldset>
                        <br>
                        <?php
                        echo '
                            <label for="name_key">' . Translation::getFileContents("label_name_key") . '</label>
                            <input type="hidden" class="textfield" id="source" name="source" value="Source">
                            <input required type="text" class="textfield" id="name_key" name="name_key"><br><br>';
                        
                        echo '<label for="language">'.Translation::getFileContents("Language_original").'</label><br>
                        <select class="textfield" name="language" id="language">';
                        foreach ($languages as $language) {
                        echo '<option value="'.$language['id_lang'].'">'.Translation::getFileContents($language['iso']).'</option>';
                        }
                        echo '</select><br><br>';

                        foreach ($languages as $language) {
                            echo '<label class="labelsource" id="labelsource' . $language['iso'] . '" name="labelsource' . $language['iso'] . '" for="source' . $language['iso'] . '">' . Translation::getFileContents("label_source_" . $language['iso']) . '</label>';
                            echo '<input type="file" class="textfield " id="source' . $language['iso'] . '" name="source' . $language['iso'] . '"><br>';
                            echo '<label class="" id="labelurl' . $language['iso'] . '" name="labelurl' . $language['iso'] . '" for="url' . $language['iso'] . '">' . Translation::getFileContents("label_url_" . $language['iso']) . '</label>';
                            echo '<input type="text" class="textfield " id="url' . $language['iso'] . '" name="url' . $language['iso'] . '"><br>';
                            echo '<label for="' . $language['iso'] . '">' . Translation::getFileContents("label_name_" . $language['iso']) . '</label>';
                            echo '<input type="text" class="textfield" id="' . $language['iso'] . '" name="' . $language['iso'] . '">';
                        }
                        ?>
                        <script type="text/javascript">
                            function setLanguagePreferences(type = "singlelingual")
                            {
                                if (type === "singlelingual")
                                {
<?php

function printsinglelingualuplad($iso) {
    if ($iso == "de") {
        return;
    }
    echo 'if(!$( "#labelsource' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#labelsource' . $iso . '").addClass("hidden");
                                            }
                                            if(!$( "#source' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#source' . $iso . '").addClass("hidden");
                                            }
                                            if(!$( "#labelurl' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#labelurl' . $iso . '").addClass("hidden");
                                            }
                                            if(!$( "#url' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#url' . $iso . '").addClass("hidden");
                                            }';
}

foreach ($languages as $language) {
    printsinglelingualuplad($language['iso']);
}
?>
                                    $("#labelsourcede").text("<?php T("label_source_international"); ?>");
                                    $("#labelurlde").text("<?php T("label_url_international"); ?>");
                                }
                                else // Multilingual
                                {
<?php

function printmultilingualuplad($iso) {
    if ($iso == "de") {
        return;
    }
    echo 'if($( "#labelsource' . $iso . '" ).hasClass( "hidden" ))
                                        {
                                            $("#labelsource' . $iso . '").removeClass("hidden");
                                        }
                                        if($( "#source' . $iso . '" ).hasClass( "hidden" ))
                                        {
                                            $("#source' . $iso . '").removeClass("hidden");
                                        }
                                        if($( "#labelurl' . $iso . '" ).hasClass( "hidden" ))
                                        {
                                            $("#labelurl' . $iso . '").removeClass("hidden");
                                        }
                                        if($( "#url' . $iso . '" ).hasClass( "hidden" ))
                                        {
                                            $("#url' . $iso . '").removeClass("hidden");
                                        }';
}

foreach ($languages as $language) {
    printmultilingualuplad($language['iso']);
}
?>
                                    $("#labelsourcede").text("<?php T("label_source_de"); ?>");
                                    $("#labelurlde").text("<?php T("label_url_de"); ?>");

                                }
                                setSourcePreferences();
                            }
                            function setSourcePreferences()
                            {
                                if ($("#source").val() === "Source")
                                {
<?php

function printsourceuplad($iso) {
    if ($iso == "de") {
        echo '
                                            if($( "#labelsource' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#labelsource' . $iso . '").removeClass("hidden");
                                            }
                                            if($( "#source' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#source' . $iso . '").removeClass("hidden");
                                            }
                                            if(!$( "#labelurl' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#labelurl' . $iso . '").addClass("hidden");
                                            }
                                            if(!$( "#url' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#url' . $iso . '").addClass("hidden");
                                            }';
        return;
    }
    echo '
                                            if(!$( "#labelurl' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#labelurl' . $iso . '").addClass("hidden");
                                            }
                                            if(!$( "#url' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#url' . $iso . '").addClass("hidden");
                                            }';
}

foreach ($languages as $language) {
    printsourceuplad($language['iso']);
}
?>
                                }
                                else // URL
                                {
<?php

function printurluplad($iso) {
    if ($iso == "de") {
        echo '
                                            if($( "#labelurl' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#labelurl' . $iso . '").removeClass("hidden");
                                            }
                                            if($( "#url' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#url' . $iso . '").removeClass("hidden");
                                            }
                                            if(!$( "#labelsource' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#labelsource' . $iso . '").addClass("hidden");
                                            }
                                            if(!$( "#source' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#source' . $iso . '").addClass("hidden");
                                            }';
        return;
    }
    echo 'if(!$( "#labelsource' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#labelsource' . $iso . '").addClass("hidden");
                                            }
                                            if(!$( "#source' . $iso . '" ).hasClass( "hidden" ))
                                            {
                                                $("#source' . $iso . '").addClass("hidden");
                                            }';
}

foreach ($languages as $language) {
    printurluplad($language['iso']);
}
?>
                                }
                            }
                            function openTab(type)
                            {
                                $("#source").val(type);
                                if ($('#multi_lingual').is(':checked'))
                                {
                                    setLanguagePreferences("multilingual");
                                }
                                else
                                {
                                    setLanguagePreferences("singlelingual");
                                }
                            }
                            $(document).ready(function () {
                                setLanguagePreferences();
                            });
                            $("#name_key").change(function () {
<?php foreach ($languages as $language) { ?>
                                    if ($("#<?php echo $language['iso']; ?>").val() === "")
                                    {
                                        $("#<?php echo $language['iso']; ?>").val($("#name_key").val());
                                    }
<?php } ?>
                            });
                            $("#en").change(function () {
<?php
foreach ($languages as $language) {
    if ($language['iso'] != "en" && $language['iso'] != "de") {
        ?>
                                        if ($("#<?php echo $language['iso']; ?>").val() === "" || $("#<?php echo $language['iso']; ?>").val() === $("#name_key").val())
                                        {
                                            $("#<?php echo $language['iso']; ?>").val($("#en").val());
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
                    <h3 class="pad-bt15"><?php T("existing_videos"); ?></h3>

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
                        $videos = VideoHandler::getVideos();
                        foreach ($videos as $video) {
                            $continue = false;
                            if (!empty($showResults)) {
                                $continue = true;
                                $pos = strpos(strtolower($video['name_key']), strtolower($showResults));
                                if (!empty($pos) || $pos === 0) {
                                    $continue = false;
                                } else {
                                    foreach ($languages as $language) {
                                        $translation = Translation::getFileContents($video['name_key'], $language['iso']);
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
                            echo '<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 addDataBlock">';
                            echo '<label class="title">' . $video['name_key'] . '</label><br>';
                            echo '<input type="hidden" class="textfield" value="' . $video['id_video'] . '" id="id_video" name="id_video">';
                            echo '<input type="hidden" class="textfield" value="' . $video['name_key'] . '" id="name_key" name="name_key">';
                            
                            $key = "video_" . str_replace(" ", "_", $video['name_key']). "_url";
                            $fileendingpos = strrpos(Translation::getFileContents($key), ".") + 1;
                            $fileending = substr(Translation::getFileContents($key), $fileendingpos);
                            
                            if(strlen($fileending)>4){
                                $fileending = "mp4";
                            }
                            
                            echo '<video controls id="videoPlayer_'.str_replace(" ", "_", $video['name_key']).'" class="previewImage">
                                    <source src="'.Translation::getFileContents($key).'" type="video/'.$fileending.'">
                                    Your browser does not support the video tag.
                                  </video>';

                            echo '<div class="btn-group">
        <button type="submit" name="deleteBtn" class="btn btn-sm btn-delete" style="width: 100%;">' . Translation::getFileContents("delete") . '</button>
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