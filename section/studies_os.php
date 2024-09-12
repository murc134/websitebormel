
<section id='studiessection'>
    <div class='container'>

        <div class="row">
            <div class="col-xs-12 studies">
                <div class="wrap-item studies-wrap">
                    <?php Translation::get('mein_studium'); ?>
                </div>
            </div>
        </div>
        <div class='row'>
            <div class='studies studies-modules'>
                <?php
                    include_once dirname(__FILE__).'/../tools/studiesCourseLoader.php';
                    getCoursesOS();
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 studies">
                <div class="wrap-item studies-wrap">
                    <?php Translation::get('mein_studium_weitere_infos_finden_sie'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 studies">
                <div class="wrap-item studies-wrap">
                    <?php Translation::get('mein_studium_in_schweden'); ?>
                </div>
            </div>
        </div>
        <div class='row'>
            <div class='studies studies-modules'>
                <?php
                    getCoursesSundsvall();
                ?>
            </div><!--/.services-->
        </div><!--/.row-->    
        <div class="row">
            <div class="col-xs-12 studies">
                <div class="wrap-item studies-wrap">
                    <?php Translation::get('mein_studium_weitere_infos_finden_sie_miun'); ?>
                </div>
            </div>
        </div>
    </div><!--/.container-->
</section>