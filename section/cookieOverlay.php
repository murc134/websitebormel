<section id="cookieOverlaySection">
    <div class="container">
        <div class="row">
            <div id="cookieContent">
                <?php
                echo "<h2 class='pad-bt15 text-center'>" . Translation::getFileContents("privacy_preferences") . "</h2>";
                ?>
                <div class="col-xs-12 col-sm-12">
                    <?php
                    echo "<h3 class='pad-bt15 text-center'>" . Translation::getFileContents("cookies_title") . "</h3>";
                    ?>
                </div>
                <div class="col-xs-12 col-sm-12 text-center">
                    <?php
                    echo Translation::getFileContents("cookies_text");

                    echo '<br><br>';
                    echo "<h3 class='pad-bt15 text-center'>" . Translation::getFileContents("selection") . "</h3>";

                    $page = $_SESSION["page"];
                    $url = getUri() . "?page=" . (empty($page) ? "index" : $page);

                    include_once dirname(__FILE__) . '/forms/setupCookies.php';
                    
                    if ($_SESSION["page"] == "login") {
                        echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
                        echo "<a href='" . getUri() . "?page=index' target='_SELF'>";  
                        echo '<button value="1" type="button" name="accept_all_privacy_settings_btn" id="accept_all_privacy_settings_btn" class="btn btn-sm btn-update">' . Translation::getFileContents("back_to_index") . '</button>';
                        echo '</a>';
                        echo '</div>';
                        echo "<br><br>";
                    }

                    echo "<br>";
                    echo Translation::getFileContents("cookies_pls_accept");
                    echo "<br>";
                    echo Translation::getFileContents("cookies_pls_accept_tracking");

                    echo "<br><br>";
                    echo "<a href='" . getUri() . "?page=cookieinfo' target='_BLANK'>" . Translation::getFileContents("cookie_info_title") . "</a>";
                    echo "&nbsp;|&nbsp;";
                    echo "<a href='" . getUri() . "?page=privacy' target='_BLANK'>" . Translation::getFileContents("privacy_policy_title") . "</a>";
                    echo "&nbsp;|&nbsp;";
                    echo "<a href='" . getUri() . "?page=imprint' target='_BLANK'>" . Translation::getFileContents("impressum") . "</a>";
                    ?>

                </div>
            </div>
        </div>  
    </div>
</section>