<section id="bannersection" class="">
    <div id="banner">

        <div>
            <ul class="bxslider">
                <?php 

                $banners = getFilesFromDirectory("img/banners/".$_SESSION["page"]."/");
                
                if(count($banners) == 0){
                    $banners = getFilesFromDirectory("img/banners/index/");
                }
                
                $banners_used = array();
                
                foreach($banners as $banner){
                    if(in_array(replaceColorInText($banner), $banners_used)){
                    }
                    else
                    {
                        $banners_used[] = replaceColorInText($banner);
                    }
                }
                
                foreach($banners_used as $banner){
                    echo "<li><img class='image' src='$banner' title='Christoph Brucksch'/></li>"; 
                }
                ?>
            </ul>
        </div>

        <script>
            $('.bxslider').bxSlider({
                mode: 'fade',
                speed: 0.5 * 1000,
                randomStart: true,
                pause: 5 * 1000,
                captions: true,
                auto: true,
                pagination: false,
            });
            $(document).ready(function () {

                var mobile = '<a href="#anchor_below_banner"><img class="scrollicon" src="img//icons/touch.svg"></a>';
                var desktop = '<a href="#anchor_below_banner"><img class="scrollicon" src="img//icons/mousewheel.svg"></a>';

<?php
if ($detect->isTablet() || $detect->isMobile()) {
    echo "$('.bx-caption').html(mobile);";
} else {
    echo "$('.bx-caption').html(desktop);";
}
?>
            });
        </script>
    </div>
    <a name="anchor_below_banner"></a>
</section>
