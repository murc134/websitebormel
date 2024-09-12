<?php
$person = getPersonalData();
?>
<section id="cookieInfoSection" class="section-padding ">
    <div id="info" class="">

        <div class="container">
            <div class="row">
                <div id="cookieContent">
                    <div class="col-xs-12 col-sm-12 pageContent">
                        <?php
                                        echo '<h2 class="title-text text-left">';
                echo Translation::getFileContents("cookies_title");
                echo '</h2>';
                        
                        echo Translation::getFileContents("cookies_text");
                        echo '<br><br>';
                        echo Translation::getFileContents("cookies_text_detail");
                        echo '<br><br>';
                        echo "<h3 class='pad-bt15'>" . Translation::getFileContents("cookies_essential_title") . "</h3>";
                        echo Translation::getFileContents("cookies_essential_text");
                        echo '<br><br>';
                        echo "<h3 class='pad-bt15'>" . Translation::getFileContents("cookies_tracking_title") . "</h3>";
                        echo Translation::getFileContents("cookies_tracking_text");
                        echo '<br><br>';
                        echo "<h3 class='pad-bt15'>" . Translation::getFileContents("cookies_ip_tracking_title") . "</h3>";
                        echo Translation::getFileContents("cookies_ip_tracking_text");
                        echo '<br><br>';
                        
                        
                        $page = $_SESSION["page"];
                        $url = getUri() . "?page=index";
                        echo "<div class='text-center'>";
                        echo "<h3 class='pad-bt15'>" . Translation::getFileContents("cookie_selection") . "</h3>";
                        include_once dirname(__FILE__) . '/forms/setupCookies.php';
                        echo "</div>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>