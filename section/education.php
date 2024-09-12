<section id="cv-tab">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pageContent">
                <?php
                echo '<h2 class="title-text text-left">';
                echo Translation::getFileContents("schoolEducation");
                echo '</h2>';
                echo '<br><br>'
                ?>
                <div class="foundation-row">
                    <div id="work-experience">
                        <ul class="item-block item-list">
                            <?php
                            $educationExperiences = ExperienceHandler::getSchoolEducation();
                            foreach ($educationExperiences as $exp) {
                                $month_since = date("M", strtotime($exp["date_start"]));
                                $year_since = date("Y", strtotime($exp["date_start"]));
                                $date_since = Translation::getFileContents($month_since) . " " . $year_since;

                                if (isset($exp["date_end"]) && !empty($exp["date_end"])) {
                                    $month_until = date("M", strtotime($exp["date_end"]));
                                    $year_until = date("Y", strtotime($exp["date_end"]));
                                    $date_since .= " - " . Translation::getFileContents($month_until) . " " . $year_until;
                                } else {
                                    $date_since = sprintf(Translation::getFileContents("SinceProzentS"), $date_since);
                                }
                                $date_diff = getTimeDifference($exp["date_start"], $exp["date_end"]);
                                ?>
                                <li>
                                    <div class="editor-content">
                                        <div class="cv-entry clfx edit-mode toggle-box ">
                                            <div class="duration">
                                                <div class="circle"></div>
                                                <div class="label"><?php
                                                    $function = "render" . $exp["symbol"] . "Symbol";
                                                    echo SVG::$function(($exp["symbol"] == "school" ? 60 : 70), ($exp["symbol"] == "school" ? 60 : 70));
                                                    ?> </div>
                                            </div>
                                            <?php
                                            printJobDetailPanel
                                            (
                                                Translation::getFileContents($exp["key_school"] . "_institution_name"),  
                                                Translation::getFileContents(($exp["key_school"] . "_title")),
                                                Translation::getFileContents(($exp["key_school"] . "_website")),
                                                $date_since,
                                                Translation::getFileContents($exp["key_school"] . "_text"),
                                                "", // keywords
                                                Translation::getFileContents($exp["key_abschluss"])
                                            );
                                            ?>
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
