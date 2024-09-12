<section id="cv-tab">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pageContent">
                <?php
                echo '<h2 class="title-text text-left">';
                echo Translation::getFileContents("hobbies_title");
                echo '</h2>';
                echo '<br><br>'
                ?>
                <div class="foundation-row">
                    <div id="work-experience">
                        <ul class="item-block item-list">
                            <?php
                            $jobExperiences = ExperienceHandler::getHobbies();
                            foreach ($jobExperiences as $exp) {
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
                                                <div class="label">
                                                    <div>
                                                        <?php
                                                        ///*
                                                        if ($date_diff[0] > 1) {
                                                            echo sprintf(Translation::getFileContents("ProzentSyears"), $date_diff[0]);
                                                        } else if ($date_diff[0] == 1) {
                                                            echo sprintf(Translation::getFileContents("ProzentSyear"), $date_diff[0]);
                                                        }
                                                        if ($date_diff[1] > 1) {
                                                            if ($date_diff[0] > 1 || $date_diff[0] == 1) {
                                                                echo "<br>";
                                                            }
                                                            echo sprintf(Translation::getFileContents("ProzentSmonths"), $date_diff[1]);
                                                        } else if ($date_diff[1] == 1) {
                                                            if ($date_diff[0] > 1 || $date_diff[0] == 1) {
                                                                echo "<br>";
                                                            }
                                                            echo sprintf(Translation::getFileContents("ProzentSmonth"), $date_diff[1]);
                                                        }
                                                        //*/
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                <?php
                                    printJobDetailPanel
                                    (
                                        Translation::getFileContents($exp["key_jobname"] . "_title"),
                                        "",
                                        Translation::getFileContents($exp["key_jobwebsite"]),
                                        $date_since,
                                        Translation::getFileContents($exp["key_jobname"] . "_txt"),
                                        Translation::getFileContents($exp["key_jobname"] . "_tags"),
                                        ""
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