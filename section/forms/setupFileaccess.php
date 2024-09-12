<?php
if(!empty(UserHandler::isAdminLogged()))
{
?>
<section id="preferencesSection">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <h2 class="pad-bt15"><?php T("Fileaccess"); ?></h2>
                <?php
                
                $languages = Translation::getLanguages();
                $url = getUri() . "?page=setupFileaccess";

                $showResults = "";

                // Used to apply filters
                if (isset($_POST['filterBtn'])) {
                    $showResults = $_POST['filter'];
                }
                else if(isset($_GET["filter"]))
                {
                    $showResults = $_GET["filter"];
                }
                // Delete Fileaccess
                if (isset($_POST['deleteBtn'])) {
                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">'
                    . '</div>';

                }
                // Update Fileaccess
                if (isset($_POST['updateBtn'])) {
                    $id_user = POST("id_user");

                    $files = POST("files");

                    $empty = !empty($id_user) && !empty($files); 
                    
                    if ($empty) 
                    {

                        UserHandler::delete_file_permissions($id_user);

                        foreach($files as $id_file){
                            echo $id_file . "!";
                            UserHandler::insert_file_permission($id_user, $id_file);
                        }
                        $showResults = POST("username");
                        echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">Success</div>';
                    } else {
                        echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">Oops, something went wrong</div>';
                    }
                    
                }

                ?>

                <div class="col-xs-12 col-sm-12">
                    <h3 class="pad-bt15"><?php T("existing_users"); ?></h3>

                    <?php
                    echo '<form method="POST" action="' . $url . '">';
                    echo '<div class="col-xs-12 col-sm-12">';
                    echo '<label class="title">Filter</label><br>';
                    echo '<input type="text" class="textfield" value="' . (!empty($showResults) && $showResults != "-1" ? $showResults : "") . '" id="filter" name="filter">';
                    echo '<div class="btn-group">
                            <button type="submit" id="filterBtn" name="filterBtn" class="btn btn-sm btn-add">' . Translation::getFileContents("show_all") . '</button>
                         </div>';
                    echo '</div>';
                    echo '</form>';
                       
                        $users = UserHandler::getUsers($showResults);
                        
                        foreach ($users as $user) {

                            echo '<form method="POST" action="' . $url . '">';
                            echo '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 addDataBlock">';
                            echo '<label>' . Translation::getFileContents("label_username") . '</label>: ';
                            echo $user['username'] . '<br>';
                            echo '<input type="hidden" class="textfield" id="id_user" name="id_user" value="'.$user["id_user"].'">';
                            echo '<input type="hidden" class="textfield" id="username" name="username" value="'.$user["username"].'">';
                            
                            foreach(FileHandler::getFiles() as $file){
                                echo "
                                <li> 
                                    <label>
                                      <input ".(UserHandler::hasFileAccess($file["id_file"], $user["id_user"]) ? "selected checked" : "")." type='checkbox' name='files[]' value='".$file["id_file"]."'>
                                      ".$file["filename"]."
                                    </label>
                                </li>
                                ";
                            }
                            echo '<div class="btn-group">
                                    <button type="submit" name="updateBtn" class="btn btn-sm btn-add">' . Translation::getFileContents("update") . '</button>
                                 </div>';

                            echo '</div>';
                            echo '</form>';
                        }
                    
                    ?>
                </div>
                <script type="text/javascript">
                    $("#filter").change(function () {
                        if ($("#filter").val() !== "")
                        {
                            $("#filterBtn").text("<?php echo Translation::getFileContents("filter_results"); ?>");
                        }
                        else
                        {
                            $("#filterBtn").text("<?php echo Translation::getFileContents("show_all"); ?>");
                        }
                    });
                </script>
            </div>
        </div>  
    </div>  
    <script type="text/javascript">
        $("input[type=text]").focus(function () {
            var save_this = $(this);
            window.setTimeout(function () {
                save_this.select();
            }, 100);
        });
    </script>
</div>
</section>

<?php
}
?>