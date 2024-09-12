<section id="aboutmesection" class="" style="">
    <div class="col-xs-12 col-sm-4 sideinfo">
        <div class="col-xs-12 col-sm-12 nopadding">
            <img class="portrait_image" src="./img/portrait/about/portrait2.png" alt=""/>

        </div>
        <div class="col-xs-12 col-sm-12 nopadding text-center">
            <h3 class="conditionalCentering">Christoph Brucksch</h3>
            <h4 class="conditionalCentering"><?php T("medieninformatiker"); ?></h4>
            <span class="conditionalCentering additional">Bachelor of Science</span>
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
    <div class="col-xs-12 col-sm-8 maininfo studies ">
        <h2 class="text-left"><?php T("about"); ?></h2>
        <span><?php T("aboutme_text"); ?></span>
        <?php //echo renderBatterie("300", "auto","30",4)?>
        <br><br>
    </div>
</section>    
<script type="text/javascript">
    var repairSize = function () {
        var sideheight = $(".sideinfo").css("height");
        var mainheight = $(".maininfo").css("height");
        
        $(".maininfo").removeAttr("style");
        
        if (Number(sideheight.replace("px", "")) > Number(mainheight.replace("px", ""))) {
            console.log("sideBigger " + sideheight.replace("px", "") + " - " + mainheight.replace("px", ""));
            $(".sideinfo").css("height", sideheight);
            $(".maininfo").css("height", sideheight);
        }
        <?php if(empty($_SESSION["isMobile"])) { ?>
        else if (Number(mainheight.replace("px", "")) > Number(sideheight.replace("px", ""))) {
            console.log("mainBigger " + Number(mainheight.replace("px", "")) + " - " + Number(sideheight.replace("px", "")));
            $(".maininfo").css("height", mainheight);
            $(".sideinfo").css("height", mainheight);
        }
        <?php } ?>
    }
    $(document).ready(function () {
        setTimeout(repairSize(), 100);
        
    });
    $(window).on('resize', function () {
        repairSize();
    });
</script>