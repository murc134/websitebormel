<section id="login_panel">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <h2 class="pad-bt15"><?php T("login_title"); ?></h2>
                <div class="wrap-item referenceText">
                    <?php
                    $isLogged = UserHandler::isLogged();
                    $emp = empty($isLogged);
                    if (isset($_GET["user"])) {
                        $username = $_GET["user"];
                    } else {
                        $username = "";
                    }
                    if ($emp) {
                        ?>

                        <div class="main-login main-center">
                            <?php T("login_text"); ?><br><br>
                            <form class="form-horizontal" method="post" action="?page=login">
                                <div class="form-group">
                                    <label for="username" class="col-sm-3 control-label"><?php echo Translation::getFileContents("username"); ?></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                                            <input type="text" class="form-control" name="username" value="<?php echo $username; ?>" id="username" placeholder="<?php Translation::getFileContents("enter_username"); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-sm-3 control-label"><?php echo Translation::getFileContents("password"); ?></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="<?php echo Translation::getFileContents("enter_password"); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label  class="col-sm-3 control-label"></label>
                                    <div class="col-sm-8">
                                        <button name="loginButton" id="loginButton" value="1" type="submit" class="btn btn-primary btn-lg btn-block login-button button"><?php T("login_title"); ?></button>
                                    </div>

                                </div>
                            </form>
                            <br><br>
                        </div>
                    <?php } else { ?>
                        <span><?php
                            T("login_text_already_logged_in");
                            $log = UserHandler::isLogged();
                            $data = UserHandler::getUserdata($log);
                            $data2 = getPersonalData();
                            if (!empty($data)) {
                                $key = $data["redirect_key"];
                                if (!empty($key)) {
                                    $url = $data2->website . "?page=" . $key;
                                    ?>
                                    <script type="text/javascript">
                                        window.location = "<?php echo "$url"; ?>";
                                    </script>
                                    <?php
                                }
                            }
                            ?></span><br><br>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
