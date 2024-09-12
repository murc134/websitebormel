<?php
        
    echo '<form method="POST" action="' . $url . '">';
    echo '<div>
                            <input value="1" type="checkbox" id="allow_cookies" name="allow_cookies" checked>
                            <label for="allow_cookies">' . Translation::getFileContents("cookies_essential_title") . '</label>
                          </div>';
    echo '<div>
                            <input value="1" onclick="checkboxcheck()" type="checkbox" id="allow_tracking" name="allow_tracking" checked>
                            <label for="allow_tracking">' . Translation::getFileContents("cookies_tracking_title") . '</label>
                          </div>';
    echo '<div>
                            <input value="1" type="checkbox" id="allow_ip_tracking" name="allow_ip_tracking" checked>
                            <label for="allow_ip_tracking">' . Translation::getFileContents("cookies_ip_tracking_title") . '</label>
                          </div>';
    echo '<input type="hidden" class="textfield" id="resetcookiepreferences" name="resetcookiepreferences" value="1">';
    
    $btnClasses1 = "col-xs-12 col-sm-6 col-md-6 col-lg-6";
    $btnClasses2 = "col-xs-12 col-sm-12 col-md-12 col-lg-12";
    
    echo '<div class="btn-group">
                        <div class="'.$btnClasses1.'">
                            <button value="1" type="submit" name="decline_privacy_settings_btn" id="decline_privacy_settings_btn" class="btn btn-sm btn-delete">' . Translation::getFileContents("decline_all") . '</button>
                        </div>
                        <div class="'.$btnClasses1.'">
                            <button value="1" type="submit" name="accept_privacy_settings_btn" id="accept_privacy_settings_btn" class="btn btn-sm btn-update">' . Translation::getFileContents("accept_selected") . '</button>
                        </div>
                        <div class="'.$btnClasses2.'">
                            <button value="1" type="submit" name="accept_all_privacy_settings_btn" id="accept_all_privacy_settings_btn" class="btn btn-sm btn-update">' . Translation::getFileContents("accept_all") . '</button>
                        </div>
                        </div>';
    echo "</form>"
    ?>
                        <script type="text/javascript">
                            function checkboxcheck()
                            {
                                if (!$('#allow_tracking').is(':checked'))
                                {
                                    $("#allow_ip_tracking").prop("checked", false);
                                    $("#allow_ip_tracking").prop("disabled", true);
                                }
                                else
                                {
                                    $("#allow_ip_tracking").prop("disabled", false);
                                }
                            }
                        </script>