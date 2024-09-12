<section id="aboutmesection">
    <div class="container">
        <div class="row">

            <div class="sideinfo col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 col-xxl-4 order-2 order-sm-2 order-md-1 order-lg-1 order-xl-1 order-xxl-1">
                <div class="col-xs-12 col-sm-12 nopadding text-center">
                    <?php 
                        echo TextPlotter::RenderPortraitImage("img/portrait/portrait1.png", "img/StandingHands.jpg");
                    ?>
                </div>
                <?php
                $person = getPersonalData();
                ?>
                <div class="col-xs-12 col-sm-12 nopadding text-center">
                    <h2 class="conditionalCentering"><?php echo $person["firstname"] . " " . $person["lastname"]; ?></h2>
                    <h4 class="conditionalCentering"><?php T($person["job_name"]); ?></h4>
                    <span class="conditionalCentering additional"><?php T($person["title"]); ?></span>
                </div>
                <div class="col-xs-12 col-sm-12 nopadding"><br>
                    <?php
                    include 'section/addressDataSide.php';
                    ?>
                </div>
                <div class="col-xs-12 col-sm-12 nopadding">
                    <?php
                    include 'section/languagesSkills.php';
                    ?>
                </div>
                <div class="col-xs-12 col-sm-12 nopadding">
                    <?php
                    include 'section/programmingSkills.php';
                    ?>
                </div>
                <div class="col-xs-12 col-sm-12 nopadding">
                    <?php
                    include 'section/databaseSkills.php';
                    ?>
                </div>
                <div class="col-xs-12 col-sm-12 nopadding">
                    <?php
                    include 'section/otherSkills.php';
                    ?>
                </div>
            </div>
            <div class="pageContent col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 col-xxl-8 order-1 order-sm-1 order-md-2 order-lg-2 order-xl-2 order-xxl-2">
                <?php
                TextPlotter::PrintDefault(
                        Translation::getFileContents("index_title"),
                        Translation::getFileContents("index_subtitle"),
                        Translation::getFileContents("website_quote"),
                        Translation::getFileContents("website_quote_author"),
                        Translation::getFileContents("index_text")
                );
                ?>

            </div>
        </div>
    </div>
</section>