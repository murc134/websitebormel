<?php
// Check if the user is logged in as an admin
if (!empty(UserHandler::isAdminLogged())) {
?>
<section id="preferencesSection">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <!-- Section Header -->
                <h2 class="pad-bt15"><?php T("Projects"); ?></h2>
                <?php
                // Get the available languages for translations
                $languages = Translation::getLanguages();
                // Define the URL for form submission
                $url = getUri() . "?page=setupProjects";

                // Initialize variable to control project filtering
                $showResults = "-1";

                // Handle filtering based on POST or GET request
                if (isset($_POST['filterBtn'])) {
                    $showResults = $_POST['filter'];
                } else if (isset($_GET["filter"])) {
                    $showResults = $_GET["filter"];
                }

                // Initialize project array
                $project = array();

                // Load project data for editing
                if (isset($_POST['editBtn'])) {
                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">' . "Edit: " . $_POST['id_project'] . " - " . $_POST['name_key'] . ' was loaded for edit:<br>';

                    // Load project info into the array
                    $project["id_project"] = $_POST["id_project"];
                    $projectInfo = ProjectHandler::getProject($project["id_project"]);

                    $project["name_key"] = $projectInfo["name_key"];
                    $project["time_from"] = $projectInfo["time_from"];
                    $project["time_until"] = $projectInfo["time_until"];

                    // Load translations for each language
                    foreach ($languages as $language) {
                        $project["title_" . $language["iso"]] = Translation::getFileContents("project_" . $_POST["name_key"] . "_title", $language["iso"]);
                        $project["text_" . $language["iso"]] = Translation::getFileContents("project_" . $_POST["name_key"] . "_text", $language["iso"]);
                        $project["shortdescription_" . $language["iso"]] = Translation::getFileContents("project_" . $_POST["name_key"] . "_shortdescription", $language["iso"]);
                    }
                    echo "</div>";
                } else {
                    // Initialize empty project array for new project creation
                    $project["id_project"] = 0;
                    $project["name_key"] = "";
                    $project["time_from"] = "";
                    $project["time_until"] = "";

                    // Initialize empty translations for each language
                    foreach ($languages as $language) {
                        $project["title_" . $language["iso"]] = "";
                        $project["text_" . $language["iso"]] = "";
                        $project["shortdescription_" . $language["iso"]] = "";
                    }
                }

                // Handle project deletion
                if (isset($_POST['deleteBtn'])) {
                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">Project #' . $_POST['id_project'] . ": " . $_POST['name_key'] . ' was deleted</div>';
                    ProjectHandler::deleteProjectByName($_POST['name_key']);
                }

                // Function to update project translations
                function updateProjectTranslations() {
                    global $languages;

                    foreach ($languages as $lang) {
                        $iso = $lang["iso"];
                        $title = $_POST[$iso . "_title"];
                        $text = $_POST[$iso . "_text"];
                        $shortdesc = $_POST[$iso . "_shortdescription"];

                        // Update translations for title, text, and short description
                        Translation::updateTranslation("project_" . $_POST["name_key"] . "_title", $iso, $title);
                        Translation::updateTranslation("project_" . $_POST["name_key"] . "_text", $iso, $text);
                        Translation::updateTranslation("project_" . $_POST["name_key"] . "_shortdescription", $iso, $shortdesc);
                    }
                }

                // Function to update project dependencies
                function updateProjectDependencies() {
                    $id_project = ProjectHandler::GetProjectIDByName($_POST["name_key"]);
                    ProjectHandler::removeProjectDependencies($id_project);

                    // Update URLs, Keywords, Files, Skills, Work, Hobby, School, Platform, Image, Video, and YouTube dependencies
                    foreach (UrlHandler::getUrls() as $url) {
                        if (isset($_POST["url_" . $url["id_url"]])) {
                            ProjectHandler::addUrlDependency($id_project, $url["id_url"]);
                        }
                    }

                    foreach (KeywordHandler::getKeywords() as $keyword) {
                        if (isset($_POST["keyword_" . $keyword["id_keyword"]])) {
                            ProjectHandler::addKeywordDependency($id_project, $keyword["id_keyword"]);
                        }
                    }

                    foreach (FileHandler::getFiles() as $file) {
                        if (isset($_POST["file_" . $file["id_file"]])) {
                            ProjectHandler::addFileDependency($id_project, $file["id_file"]);
                        }
                    }

                    foreach (SkilldataHandler::getOtherSkillDataDB(-1) as $skill) {
                        if (isset($_POST["oskill_" . $skill["id_skill"]])) {
                            ProjectHandler::addOtherskillDependency($id_project, $skill["id_skill"]);
                        }
                    }

                    foreach (SkilldataHandler::getDatabaseSkillDataDB(-1) as $skill) {
                        if (isset($_POST["dbskill_" . $skill["id_skill"]])) {
                            ProjectHandler::addDatabaseSkillDependency($id_project, $skill["id_skill"]);
                        }
                    }

                    foreach (SkilldataHandler::getProgrammingSkillDataDB(-1) as $skill) {
                        if (isset($_POST["pskill_" . $skill["id_skill"]])) {
                            ProjectHandler::addProgrammingSkillDependency($id_project, $skill["id_skill"]);
                        }
                    }

                    foreach (ExperienceHandler::getWorkExperience(-1, -1) as $job) {
                        if (isset($_POST["job_" . $job["id_job"]])) {
                            ProjectHandler::addEmployerDependency($id_project, $job["id_job"]);
                        }
                    }

                    foreach (ExperienceHandler::getHobbies(-1, -1) as $job) {
                        if (isset($_POST["hobby_" . $job["id_job"]])) {
                            ProjectHandler::addHobbyDependency($id_project, $job["id_job"]);
                        }
                    }

                    foreach (ExperienceHandler::getSchoolEducation(-1, -1) as $school) {
                        if (isset($_POST["school_" . $school["id_school"]])) {
                            ProjectHandler::addSchoolDependency($id_project, $school["id_school"]);
                        }
                    }

                    foreach (getPlatforms() as $platform) {
                        if (isset($_POST["platform_" . $platform["id_platform"]])) {
                            ProjectHandler::addPlatformDependency($id_project, $platform["id_platform"]);
                        }
                    }

                    foreach (ImageHandler::getImages() as $image) {
                        if (isset($_POST["image_" . $image["id_image"]])) {
                            ProjectHandler::addImageDependency($id_project, $image["id_image"]);
                        }
                    }

                    foreach (VideoHandler::getVideos() as $video) {
                        if (isset($_POST["video_" . $video["id_video"]])) {
                            ProjectHandler::addVideoDependency($id_project, $video["id_video"]);
                        }
                    }

                    foreach (YoutubeVideoHandler::getVideos() as $youtube) {
                        if (isset($_POST["youtube_" . $youtube["id_video"]])) {
                            ProjectHandler::addYouTubeDependency($id_project, $youtube["id_video"]);
                        }
                    }
                }

                // Handle project update
                if (isset($_POST['updateBtn'])) {
                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">' . "Update: " . $_POST['id_project'] . " - " . $_POST['name_key'] . ' was updated:<br>';
                    ProjectHandler::updateProject($_POST["name_key"], $_POST["date_start"], isset($_POST["date_end"]) ? $_POST["date_end"] : "");
                    updateProjectTranslations();
                    updateProjectDependencies();
                    $showResults = $_POST["name_key"];
                    echo "</div>";
                }

                // Handle project addition
                if (isset($_POST['addBtn'])) {
                    if (isset($_POST["name_key"]) && !empty($_POST["name_key"])) {
                        $existingProjects = ProjectHandler::getProjectByName($_POST["name_key"]);

                        if (count($existingProjects) > 0) {
                            echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">' . "Project: " . $_POST['name_key'] . ' already exists<br>';
                        } else {
                            echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">' . "Added: " . $_POST['name_key'] . '<br>';
                            ProjectHandler::addProject($_POST["name_key"], $_POST["date_start"], isset($_POST["date_end"]) ? $_POST["date_end"] : "");
                            updateProjectTranslations();
                            updateProjectDependencies();
                            $showResults = $_POST["name_key"];
                        }
                    }
                    echo "</div>";
                }
                ?>
                <!-- Form for adding or editing projects -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 addDataBlock">
                    <h3 class="pad-bt15"><?php
                        // Determine if we're adding or editing a project
                        if (empty($project['id_project'])) {
                            echo Translation::getFileContents("add_project");
                        } else {
                            echo Translation::getFileContents("edit_project");
                            echo " #" . $project['id_project'];
                        }
                        ?></h3>
                    <?php
                    echo '<form method="POST" action="' . $url . '">';
                    echo '
                        <label for="name_key">' . Translation::getFileContents("label_name_key") . '</label>
                        <input type="hidden" class="textfield" value="' . $project['id_project'] . '" id="id_project" name="id_project">
                        <input value="' . $project['name_key'] . '" required type="text" class="textfield" id="name_key" name="name_key"><br>';

                    echo '<label for="date_start">' . Translation::getFileContents("label_start_date") . '</label><input required type="date" value="' . $project["time_from"] . '" class="textfield" id="date_start" name="date_start"><br>';
                    echo '<label for="date_end">' . Translation::getFileContents("label_end_date") . '</label><input type="date" value="' . $project["time_until"] . '" class="textfield" id="date_end" name="date_end"><br>';

                    // Render fields for each language
                    foreach ($languages as $language) {
                        $title = $project["title_" . $language["iso"]];
                        $text = $project["text_" . $language["iso"]];
                        $desc = $project["shortdescription_" . $language["iso"]];

                        echo '<label for="' . $language['iso'] . '_title">' . Translation::getFileContents("label_name_" . $language['iso']) . '</label>'
                        . '<input value="' . $title . '" required type="text" class="textfield" id="' . $language['iso'] . '_title" name="' . $language['iso'] . '_title"><br>';

                        echo '<label for="' . $language['iso'] . '_text">' . Translation::getFileContents("label_description_" . $language['iso']) . '</label>'
                        . '<textarea class="textfield" id="' . $language['iso'] . '_text" name="' . $language['iso'] . '_text">' . $text . '</textarea><br>';

                        echo '<label for="' . $language['iso'] . '_shortdescription">' . Translation::getFileContents("label_shortdescription_" . $language['iso']) . '</label>'
                        . '<textarea class="textfield" id="' . $language['iso'] . '_shortdescription" name="' . $language['iso'] . '_shortdescription">' . $desc . '</textarea><br>';
                    }
                    ?>
                    <!-- Script to auto-fill translations based on the name key -->
                    <script type="text/javascript">
                        $("#name_key").change(function () {
                            <?php foreach ($languages as $language) { ?>
                                if ($("#<?php echo $language['iso']; ?>_title").val() === "") {
                                    $("#<?php echo $language['iso']; ?>_title").val($("#name_key").val());
                                }
                            <?php } ?>
                        });

                        $("#en").change(function () {
                            <?php foreach ($languages as $language) { 
                                if ($language['iso'] != "en" && $language['iso'] != "de") { ?>
                                    if ($("#<?php echo $language['iso']; ?>_title").val() === "" || $("#<?php echo $language['iso']; ?>_title").val() === $("#name_key").val()) {
                                        $("#<?php echo $language['iso']; ?>_title").val($("#en_title").val());
                                    }
                                    if ($("#<?php echo $language['iso']; ?>_text").val() === "") {
                                        $("#<?php echo $language['iso']; ?>_text").val($("#en_text").val());
                                    }
                                    if ($("#<?php echo $language['iso']; ?>_shortdescription").val() === "") {
                                        $("#<?php echo $language['iso']; ?>_shortdescription").val($("#en_shortdescription").val());
                                    }
                            <?php } } ?>
                        });
                    </script>

                    <?php
                    // Render sections for managing URLs, Keywords, Files, Skills, Employers, Hobbies, Schools, Platforms, Images, Videos, and YouTube videos
                    echo '<h4 class="pad-bt15">' . Translation::getFileContents("label_manage_urls") . '</h4>';
                    foreach (UrlHandler::getUrls() as $keyword) {
                        $checked = !empty(ProjectHandler::hasUrlDependency($project["id_project"], $keyword["id_url"])) ? "checked" : "";
                        echo '<div>
                                <input ' . $checked . ' type="checkbox" id="url_' . $keyword["id_url"] . '" name="url_' . $keyword["id_url"] . '">
                                <label for="url_' . $keyword["id_url"] . '">' . $keyword["name_key"] . ': '.Translation::getFileContents("url_".$keyword["name_key"]).'</label>
                              </div>';
                    }

                    // Render Keywords management section
                    echo '<h4 class="pad-bt15">' . Translation::getFileContents("label_manage_keywords") . '</h4>';
                    foreach (KeywordHandler::getKeywords() as $keyword) {
                        $checked = !empty(ProjectHandler::hasKeywordDependency($project["id_project"], $keyword["id_keyword"])) ? "checked" : "";
                        echo '<div>
                                <input ' . $checked . ' type="checkbox" id="keyword_' . $keyword["id_keyword"] . '" name="keyword_' . $keyword["id_keyword"] . '">
                                <label for="keyword_' . $keyword["id_keyword"] . '">' . Translation::getFileContents($keyword["name_key"]) . '</label>
                              </div>';
                    }

                    // Render Files management section
                    echo '<h4 class="pad-bt15">' . Translation::getFileContents("label_manage_files") . '</h4>';
                    foreach (FileHandler::getFiles() as $file) {
                        $checked = !empty(ProjectHandler::hasFileDependency($project["id_project"], $file["id_file"])) ? "checked" : "";
                        echo '<div>
                                <input ' . $checked . ' type="checkbox" id="file_' . $file["id_file"] . '" name="file_' . $file["id_file"] . '">
                                <label for="file_' . $file["id_file"] . '">' . Translation::getFileContents("FILENAME_" . $file["title_key"]) . ' => ' . $file["filename"] . '.' . $file["filetype"] . '</label>
                              </div>';
                    }

                    // Render Database Skills management section
                    echo '<h4 class="pad-bt15">' . Translation::getFileContents("label_manage_database_skills") . '</h4>';
                    foreach (SkilldataHandler::getDatabaseSkillDataDB(-1) as $skill) {
                        $checked = !empty(ProjectHandler::hasDatabaseSkillDependency($project["id_project"], $skill["id_skill"])) ? "checked" : "";
                        echo '<div>
                                <input ' . $checked . ' type="checkbox" id="dbskill_' . $skill["id_skill"] . '" name="dbskill_' . $skill["id_skill"] . '">
                                <label for="dbskill_' . $skill["id_skill"] . '">' . $skill["name_key"] . '</label>
                              </div>';
                    }

                    // Render Programming Skills management section
                    echo '<h4 class="pad-bt15">' . Translation::getFileContents("label_manage_programming_skills") . '</h4>';
                    foreach (SkilldataHandler::getProgrammingSkillDataDB(-1) as $skill) {
                        $checked = !empty(ProjectHandler::hasProgrammingSkillDependency($project["id_project"], $skill["id_skill"])) ? "checked" : "";
                        echo '<div>
                                <input ' . $checked . ' type="checkbox" id="pskill_' . $skill["id_skill"] . '" name="pskill_' . $skill["id_skill"] . '">
                                <label for="pskill_' . $skill["id_skill"] . '">' . $skill["name_key"] . '</label>
                              </div>';
                    }

                    // Render Other Skills management section
                    echo '<h4 class="pad-bt15">' . Translation::getFileContents("label_manage_other_skills") . '</h4>';
                    foreach (SkilldataHandler::getOtherSkillDataDB(-1) as $skill) {
                        $checked = !empty(ProjectHandler::hasOtherskillDependency($project["id_project"], $skill["id_skill"])) ? "checked" : "";
                        echo '<div>
                                <input ' . $checked . ' type="checkbox" id="oskill_' . $skill["id_skill"] . '" name="oskill_' . $skill["id_skill"] . '">
                                <label for="oskill_' . $skill["id_skill"] . '">' . $skill["name_key"] . '</label>
                              </div>';
                    }

                    // Render Employers management section
                    echo '<h4 class="pad-bt15">' . Translation::getFileContents("label_manage_employers") . '</h4>';
                    foreach (ExperienceHandler::getWorkExperience(-1, -1) as $job) {
                        $checked = !empty(ProjectHandler::hasEmployerDependency($project["id_project"], $job["id_job"])) ? "checked" : "";
                        echo '<div>
                                <input ' . $checked . ' type="checkbox" id="job_' . $job["id_job"] . '" name="job_' . $job["id_job"] . '">
                                <label for="job_' . $job["id_job"] . '">' . $job["company_name"] . " => " . Translation::getFileContents($job["key_jobname"] . "_title") . '</label>
                              </div>';
                    }

                    // Render Hobbies management section
                    echo '<h4 class="pad-bt15">' . Translation::getFileContents("label_manage_hobbies") . '</h4>';
                    foreach (ExperienceHandler::getHobbies(-1, -1) as $job) {
                        $checked = !empty(ProjectHandler::hasHobbyDependency($project["id_project"], $job["id_job"])) ? "checked" : "";
                        echo '<div>
                                <input ' . $checked . ' type="checkbox" id="hobby_' . $job["id_job"] . '" name="hobby_' . $job["id_job"] . '">
                                <label for="hobby_' . $job["id_job"] . '">' . Translation::getFileContents($job["key_jobname"] . "_title") . '</label>
                              </div>';
                    }

                    // Render Schools management section
                    echo '<h4 class="pad-bt15">' . Translation::getFileContents("label_manage_schools") . '</h4>';
                    foreach (ExperienceHandler::getSchoolEducation(-1, -1) as $school) {
                        $checked = !empty(ProjectHandler::hasSchoolDependency($project["id_project"], $school["id_school"])) ? "checked" : "";
                        echo '<div>
                                <input ' . $checked . ' type="checkbox" id="school_' . $school["id_school"] . '" name="school_' . $school["id_school"] . '">
                                <label for="school_' . $school["id_school"] . '">' . Translation::getFileContents($school["key_school"] . "_institution_name") . " => " . Translation::getFileContents($school["key_school"] . "_title") . '</label>
                              </div>';
                    }

                    // Render Platforms management section
                    echo '<h4 class="pad-bt15">' . Translation::getFileContents("label_manage_platforms") . '</h4>';
                    foreach (getPlatforms() as $platform) {
                        $checked = !empty(ProjectHandler::hasPlatformDependency($project["id_project"], $platform["id_platform"])) ? "checked" : "";
                        echo '<div>
                                <input ' . $checked . ' type="checkbox" id="platform_' . $platform["id_platform"] . '" name="platform_' . $platform["id_platform"] . '">
                                <label for="platform_' . $platform["id_platform"] . '">' . $platform["name_key"] . '</label>
                              </div>';
                    }

                    // Render Images management section
                    echo '<h4 class="pad-bt15">' . Translation::getFileContents("label_manage_images") . '</h4>';
                    foreach (ImageHandler::getImages() as $image) {
                        $checked = !empty(ProjectHandler::hasImageDependency($project["id_project"], $image["id_image"])) ? "checked" : "";
                        echo '<div>
                                <input ' . $checked . ' type="checkbox" id="image_' . $image["id_image"] . '" name="image_' . $image["id_image"] . '">
                                <label for="image_' . $image["id_image"] . '">' . $image["name_key"] . '</label>
                              </div>';
                    }

                    // Render Videos management section
                    echo '<h4 class="pad-bt15">' . Translation::getFileContents("label_manage_videos") . '</h4>';
                    foreach (VideoHandler::getVideos() as $video) {
                        $checked = !empty(ProjectHandler::hasVideoDependency($project["id_project"], $video["id_video"])) ? "checked" : "";
                        echo '<div>
                                <input ' . $checked . ' type="checkbox" id="video_' . $video["id_video"] . '" name="video_' . $video["id_video"] . '">
                                <label for="video_' . $video["id_video"] . '">' . $video["name_key"] . '</label>
                              </div>';
                    }

                    // Render YouTube Videos management section
                    echo '<h4 class="pad-bt15">' . Translation::getFileContents("label_manage_youtubevideos") . '</h4>';
                    foreach (YoutubeVideoHandler::getVideos() as $youtube) {
                        $checked = !empty(ProjectHandler::hasYoutubeVideoDependency($project["id_project"], $youtube["id_video"])) ? "checked" : "";
                        echo '<div>
                                <input ' . $checked . ' type="checkbox" id="youtube_' . $youtube["id_video"] . '" name="youtube_' . $youtube["id_video"] . '">
                                <label for="youtube_' . $youtube["id_video"] . '">' . $youtube["name_key"] . '</label>
                              </div>';
                    }
                    ?>

                    <div class="btn-group">
                        <?php
                        // Render update or add button based on the state of the project
                        if (!empty($project["id_project"])) {
                            echo '<button type="submit" name="updateBtn" class="btn btn-sm btn-add">' . Translation::getFileContents("update") . '</button>';
                        }
                        ?>
                        <button type="submit" name="addBtn" class="btn btn-sm btn-add"><?php T("add") ?></button>
                    </div>
                    </form>
                </div>
            </div>
            <!-- Section to list and filter existing projects -->
            <div class="col-xs-12 col-sm-12">
                <h3 class="pad-bt15"><?php T("existing_projects"); ?></h3>

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

                // List existing projects based on the filter
                if ($showResults != "-1") {
                    $projects = ProjectHandler::getProjects();
                    foreach ($projects as $project) {
                        $continue = false;

                        // Filter projects based on the filter input
                        if (!empty($showResults)) {
                            $continue = true;
                            $pos = strpos(strtolower($project['name_key']), strtolower($showResults));
                            if (!empty($pos) || $pos === 0) {
                                $continue = false;
                            } else {
                                foreach ($languages as $language) {
                                    $translation = Translation::getFileContents($project['name_key'], $language['iso']);
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

                        // Render each project with options to edit or delete
                        echo '<form method="POST" action="' . $url . '">';
                        echo '<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 addDataBlock">';
                        echo '<label class="title">' . $project['name_key'] . '</label><br>';
                        echo '<input type="hidden" class="textfield" value="' . $project['id_project'] . '" id="id_project" name="id_project">';
                        echo '<input type="hidden" class="textfield" value="' . $project['name_key'] . '" id="name_key" name="name_key">';

                        echo '<label for="date_start">' . Translation::getFileContents("label_start_date") . '</label><input disabled type="date" value="' . $project['time_from'] . '" class="textfield" id="date_start" name="date_start"><br>';
                        echo '<label for="date_end">' . Translation::getFileContents("label_end_date") . '</label><input disabled type="date" value="' . $project['time_until'] . '" class="textfield" id="date_end" name="date_end"><br>';

                        echo '<div class="btn-group">
                                    <button type="submit" name="deleteBtn" class="btn btn-sm btn-delete">' . Translation::getFileContents("delete") . '</button>
                                    <button type="submit" name="editBtn" class="btn btn-sm btn-update">' . Translation::getFileContents("edit") . '</button>
                                 </div>';

                        echo '</div>';
                        echo '</form>';
                    }
                }
                ?>
            </div>
            <!-- JavaScript to update filter button text -->
            <script type="text/javascript">
                $("#filter").change(function () {
                    if ($("#filter").val() !== "") {
                        $("#filterBtn").text("<?php echo Translation::getFileContents("filter_results"); ?>");
                    } else {
                        $("#filterBtn").text("<?php echo Translation::getFileContents("show_all"); ?>");
                    }
                });
            </script>
        </div>  
    </div>  
    <!-- JavaScript to automatically select text fields when focused -->
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
