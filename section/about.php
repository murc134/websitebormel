<section id="aboutmesection">
    <div class="container">
        <div class="row">

            <?php
                include_once dirname(__FILE__) . '/sideMenu.php';
            ?>
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 pageContent">
                <?php 
                TextPlotter::PrintDefault(
                    Translation::getFileContents("about_title"), 
                    "",
                    //Translation::getFileContents("about_subtitle"), 
                    Translation::getFileContents("confucius_quote"), 
                    Translation::getFileContents("confucius_name"), 
                    Translation::getFileContents("about_text")
                );

                ?>

            </div>
        </div>
    </div>
</section>