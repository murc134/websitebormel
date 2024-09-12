<section id="projectsection">
    <div class="container">
        <div class="row pageContent">
            <?php
            $id_project = 0;
            $active_filter = 1;
            $scriptIncluded = 0;

            function create_slider($name, $classes) {
                global $scriptIncluded;

                echo '<div class="zSliderSection ' . $classes . '">';
                echo '<div class="inner-wrap">';
                echo '<div class="z-slide-wrap slider" id="' . $name . '">';
                echo '<ul class="z-slide-content">';

                $args = func_get_args();

                for ($i = 2; $i < count($args); $i++) {
                    echo '<li class="z-slide-item">';
                    echo '<a href="#image1" data-uuid="1">';
                    echo $args[$i];
                    echo '</a>';
                    echo '</li>';
                }

                echo '</ul>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

                if (empty($scriptIncluded)) {
                    $scriptIncluded = 1;
                    echo '<script src="js/zSlider.js"></script>';
                    ;
                }

                echo '<script type="text/javascript">
                    var ' . $name . 'Dom = document.getElementById("' . $name . '");
                    var ' . $name . ' = new Slider(' . $name . 'Dom, ".z-slide-item", {
                        interval: 3,
                        minPercentToSlide: 0.2
                    });
                </script>';
            }

            function create_slider_content($content) {
                $args = func_get_args();

                for ($i = 0; $i < count($args); $i++) {
                    echo '<li class="z-slide-item">';
                    echo $args[$i];
                    echo '</li>';
                }
            }

            function create_slider_start($name, $classes) {
                echo '<div class="zSliderSection ' . $classes . '">';
                echo '<div class="inner-wrap">';
                echo '<div class="z-slide-wrap slider" id="' . $name . '">';
                echo '<ul class="z-slide-content">';
            }

            function create_slider_end($name) {
                global $scriptIncluded;
                echo '</ul>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

                if (empty($scriptIncluded)) {
                    $scriptIncluded = 1;
                    echo '<script src="js/zSlider.js"></script>';
                }

                echo '<script type="text/javascript">
                    var ' . $name . 'Dom = document.getElementById("' . $name . '");
                    var ' . $name . ' = new Slider(' . $name . 'Dom, ".z-slide-item", {
                        interval: 3,
                        minPercentToSlide: 0.2
                    });
                </script>';
            }

            // Check for active filter
            if (isset($_POST['activefilter'])) {
                $active_filter = $_POST['activefilter'];
            } else if (isset($_GET["activefilter"])) {
                $active_filter = $_GET["activefilter"];
            }

            // Check for project ID
            if (isset($_POST['id_project'])) {
                $id_project = $_POST['id_project'];
            } else if (isset($_GET["id_project"])) {
                $id_project = $_GET["id_project"];
            }

            function drawTitle($text) {
                echo '<h2 class="title-text text-left">';
                echo $text;
                echo '</h2>';
            }

            $class = $classesfullscreendiv = 'col-xs-12 col-sm-12 col-md-12 col-lg-12';
            $classeshalfscreendiv = 'col-xs-12 col-sm-12 col-md-6 col-lg-6';

            if (!empty($id_project)) {
                // Show project info extended
                // Load ProjectInfo
                $project = ProjectHandler::getProjectInfo($id_project);

                // Draw Title
                echo '<div class="' . $classesfullscreendiv . '">';
                drawTitle($project["title"]);
                echo '</div>';

                // Draw images and videos if existing

                $count = $project["imagescount"] + $project["videoscount"] + $project["youtubevideoscount"];

                if (!empty($count)) {

                    if (is_array($project["images"]) && count($project["images"]) > 0) {
                        $slidername = "Slider";
                        create_slider_start($slidername, $classeshalfscreendiv);
                        foreach ($project["images"] as $imgdata) {
                            $image = ImageHandler::getImageInfo($imgdata["id_image"]);
                            create_slider_content('<img class="z-slide-item-inner" src="' . $image["url"] . '" />');
                        }
                        create_slider_end($slidername);
                        $class = $classeshalfscreendiv;
                    }

                    // Text
                    echo '<div class="' . $class . '">';
                    echo $project["text"];
                    echo '</div>';

                    $classVideo = 'col-xs-12 col-sm-6 col-md-4 col-lg-3';
                    $countVideo = count($project["videos"]) + count($project["youtubevideos"]);

                    echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 portfolioAdditionalContent">';
                    if ($countVideo > 0) {

                        if ($countVideo == 3) {
                            $classVideo = 'col-xs-12 col-sm-4 col-md-4 col-lg-4';
                        } else if ($countVideo % 3 == 0) {
                            $classVideo = 'col-xs-12 col-sm-4 col-md-4 col-lg-4';
                        } else if ($countVideo == 2) {
                            $classVideo = 'col-xs-12 col-sm-6 col-md-6 col-lg-6';
                        } else if ($countVideo % 2 == 0) {
                            $classVideo = 'col-xs-12 col-sm-6 col-md-4 col-lg-3';
                        } else if ($countVideo == 1) {
                            $classVideo = 'col-xs-12 col-sm-12 col-md-12 col-lg-12';
                        }

                        foreach ($project["videos"] as $videodata) {
                            $video = VideoHandler::getVideoInfo($videodata["id_video"]);
                            echo '<div class="' . $classVideo . '">';
                            echo "<br><h3>" . $video["name"] . "</h3>";
                            echo $video["desc"] . "<br>";
                            echo ($video["embedded"]);
                            echo '</div>';
                        }

                        foreach ($project["youtubevideos"] as $videodata) {
                            $video = YoutubeVideoHandler::getVideoInfo($videodata["id_video"]);
                            echo '<div class="' . $classVideo . '">';
                            echo "<br><h3>" . $video["name"] . "</h3>";
                            echo $video["desc"] . "<br>";
                            echo ($video["embedded"]);
                            echo '</div>';
                        }
                    }
                    echo "</div>";
                } else {
                    echo '<div class="' . $class . '">';
                    echo $project["text"];
                    echo '</div>';
                }
            } else {
                // Show default portfolio text
                echo '<div class="' . $classesfullscreendiv . '">';

                drawTitle(Translation::getFileContents("portfolio_title"));
                echo Translation::getFileContents("portfolio_text");
                echo '<br><br>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <div class="container">
        <div class="row pageContent">
            <?php
            $projects = ProjectHandler::getProjects($active_filter);

            if (count($projects) > 0) {
                echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
                echo '<h2>' . Translation::getFileContents("further_projects") . '</h2>';

                foreach ($projects as $projectdata) {
                    $project = ProjectHandler::getProjectInfo($projectdata["id_project"]);

                    echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 portfolioProjectBox">';
                    echo '<div class="portfolioProjectBoxInner">';
                    echo "<a href='?page=portfolio&id_project=" . $projectdata["id_project"] . "' target='_SELF'>";
                    echo '<h3 class="text-left card-title">' . $project["title"] . '</h3>';

                    if ($project["imagescount"] > 0) {
                        $images = $project["images"];
                        $image = ImageHandler::getImageInfo($images[0]["id_image"]);
                        echo '<img class="previewImage" src="' . $image["url"] . '" />';
                        echo "</a>";
                    } else if ($project["videoscount"] > 0) {
                        echo "</a>";
                        $videos = $project["videos"];
                        $video = VideoHandler::getVideoInfo($videos[0]["id_video"]);
                        echo ($video["embedded"]);
                    } else if ($project["youtubevideoscount"] > 0) {
                        echo "</a>";
                        $videos = $project["youtubevideos"];
                        $video = YoutubeVideoHandler::getVideoInfo($videos[0]["id_video"]);
                        echo ($video["embedded"]);
                    }

                    echo '<div class="card-text-body">';
                    echo '<p class="card-text">';
                    echo $project["shortdescription"];
                    echo '</p>';
                    echo '</div>';
                    echo '<br>';
                    echo "<a href='?page=portfolio&id_project=" . $projectdata["id_project"] . "' target='_SELF'>";
                    echo '<button type="button" class="btn">';
                    echo Translation::getFileContents("more_info");
                    echo '</button>';
                    echo '</a>';
                    echo '</div>';   
                    echo '</div>';                
                }
                echo '</div>';
            }
            ?>







        </div>
    </div>

</section>
