<?php
if(!empty(UserHandler::isAdminLogged()))
{
?>
<section id="preferencesSection">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <h2 class="pad-bt15"><?php T("Users"); ?></h2>
                <?php
                
                $languages = Translation::getLanguages();
                $activelanguages = Translation::getLanguages(1);
                
                $url = getUri() . "?page=setupUsers";

                $showResults = "-1";

                // Used to apply filters
                if (isset($_POST['filterBtn'])) 
                {
                    $showResults = $_POST['filter'];
                }
                else if(isset($_GET["filter"]))
                {
                    $showResults = $_GET["filter"];
                }
                // Delete User
                if (isset($_POST['deleteBtn'])) 
                {
                    echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">'
                        . UserHandler::deleteUser($_POST['id_user'])
                        . '</div>';

                }
                // Update User
                if (isset($_POST['updateBtn'])) 
                {
                    $feedback = UserHandler::updateUser();
                    
                    if($feedback[0])
                    {
                        echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">'
                        . $feedback[2]
                        . '</div>';
                        
                        $showResults = UserHandler::getUsername($feedback[1]);
                    }
                    else
                    {
                        echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">'
                        . $feedback[2]
                        . '</div>';
                    }
                }

                if (isset($_POST['addBtn'])) 
                {
                    
                    $feedback = UserHandler::createUser();
                    
                    if($feedback[0])
                    {
                        echo '<div class="col-xs-12 col-sm-12 feedback feedbackUpdate">'
                        . $feedback[2]
                        . '</div>';
                        
                        $showResults = UserHandler::getUsername($feedback[1]);
                    }
                    else
                    {
                        echo '<div class="col-xs-12 col-sm-12 feedback feedbackDelete">'
                        . $feedback[2]
                        . '</div>';
                    }
                    

                    // Make Add
                }
                ?>
                <div class="col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 addDataBlock">
                        <?php
                        
                        echo '<h3 class="pad-bt15">'.Translation::getFileContents("add_user").'</h3>';
                        
                        echo '<form method="POST" action="' . $url . '">';

                        echo '
                            <label for="company">' . Translation::getFileContents("label_company") . '</label>
                            <input class="textfield" required type="text" id="company" name="company" value="" /><br>';
                        
                        echo '
                            <label for="title">' . Translation::getFileContents("label_title") . '</label>
                            <input class="textfield" type="text" id="title" name="title" value="" /><br>';
                        
                        echo '
                            <label for="firstname">' . Translation::getFileContents("label_firstname") . '</label>
                            <input class="textfield" required type="text" id="firstname" name="firstname" value="" /><br>';
                        
                        echo '
                            <label for="lastname">' . Translation::getFileContents("label_lastname") . '</label>
                            <input class="textfield" required type="text" id="lastname" name="lastname" value="" /><br>';
                        
                        echo '<label for="birthday">' . Translation::getFileContents("label_birthday") . '</label><input type="date" value="' . "0000-00-00" . '" class="textfield" id="birthday" name="birthday"><br>';
                        
                        echo '
                            <label for="gender">' . Translation::getFileContents("label_gender") . '</label>
                            <select class="textfield" required id="gender" name="gender">
                                <option value="m">'. Translation::getFileContents("male") .'</option>
                                <option value="f">'. Translation::getFileContents("female") .'</option>
                            </select><br>';
                        
                        echo '<label for="language">' . Translation::getFileContents("label_language") . '</label>';
                        echo '<select class="textfield" required id="language" name="language">';
                        foreach ($activelanguages as $language) {
                            echo '<option value="'.$language["iso"].'">'.Translation::getFileContents($language["iso"]).'</option>';
                        }
                        echo '</select>';
                        echo '<br>';
                        
                        echo '
                            <label for="email">' . Translation::getFileContents("label_email") . '</label>
                            <input class="textfield" type="email" id="email" name="email" value="" /><br>';
                        
                        echo '
                            <label for="username">' . Translation::getFileContents("label_username") . '</label>
                            <input class="textfield" required type="text" id="username" name="username" value="" /><br>';
                        
                        echo '
                            <label for="passwd">' . Translation::getFileContents("label_password") . '</label>
                            <input class="textfield" required type="text" id="passwd" name="passwd" value="" /><br>';
                        
                        echo '
                            <label for="phone">' . Translation::getFileContents("label_phone") . '</label>
                            <input class="textfield" type="text" id="phone" name="phone" value="" /><br>';
                        
                        echo '
                            <label for="street">' . Translation::getFileContents("label_street") . '</label>
                            <input class="textfield" type="text" id="street" name="street" value="" /><br>';
                        
                        echo '
                            <label for="zip">' . Translation::getFileContents("label_zip") . '</label>
                            <input class="textfield" type="text" id="zip" name="zip" value="" /><br>';
                        
                        echo '
                            <label for="city">' . Translation::getFileContents("label_city") . '</label>
                            <input class="textfield" type="text" id="city" name="city" value="" /><br>';
                        
                        echo '
                            <label for="specialIndexText">' . Translation::getFileContents("label_specialIndexText") . '</label>
                            <input class="textfield" type="text" id="specialIndexText" name="specialIndexText" value="index_text" /><br>';
                        
                        echo '
                            <label for="redirectKey">' . Translation::getFileContents("label_redirectKey") . '</label>
                            <input class="textfield" type="text" id="redirectKey" name="redirectKey" value="index" /><br>';

                        
                        ?>
                        
                        <div class="btn-group">
                            <button type="submit" name="addBtn" class="btn btn-sm btn-add"><?php T("add") ?></button>
                        </div>
                        </form>
                    </div>
                </div>
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

                    if ($showResults != "-1") {
                        
                        $users = UserHandler::getUsers($showResults);
                        
                        foreach ($users as $user) {
                            echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 addDataBlock">';
                            echo '<form method="POST" action="' . $url . '">';
                            
                            echo '<input type="hidden" class="textfield" id="id_user" name="id_user" value="'.$user["id_user"].'">';
                            
                            echo '
                                <label for="company">' . Translation::getFileContents("label_company") . '</label>
                                <input class="textfield" required type="text" id="company" name="company" value="'.$user["company"].'" /><br>';

                            echo '
                                <label for="title">' . Translation::getFileContents("label_title") . '</label>
                                <input class="textfield" type="text" id="title" name="title" value="'.$user["title"].'" /><br>';

                            echo '
                                <label for="firstname">' . Translation::getFileContents("label_firstname") . '</label>
                                <input class="textfield" required type="text" id="firstname" name="firstname" value="'.$user["firstname"].'" /><br>';

                            echo '
                                <label for="lastname">' . Translation::getFileContents("label_lastname") . '</label>
                                <input class="textfield" required type="text" id="lastname" name="lastname" value="'.$user["lastname"].'" /><br>';
                            
                            echo '<label for="birthday">' . Translation::getFileContents("label_birthday") . '</label><input type="date" value="'.$user["birthday"].'" class="textfield" id="birthday" name="birthday"><br>';
                            
                            echo '
                                <label for="gender">' . Translation::getFileContents("label_gender") . '</label>
                                <select class="textfield" required id="gender" name="gender">
                                    <option '.($user["gender"] === "m" ? "selected" : "").' value="m">'. Translation::getFileContents("male") .'</option>
                                    <option '.($user["gender"] !== "m" ? "selected" : "").' value="w">'. Translation::getFileContents("female") .'</option>
                                </select><br>';

//                            echo '<label for="language">' . Translation::getFileContents("label_language") . '</label>';
//                            echo '<select class="textfield" required id="language" name="language">';
//                            foreach ($activelanguages as $language) {
//                                echo '<option value="'.$language["iso"].'">'.Translation::getFileContents($language["iso"]).'</option>';
//                            }
//                            echo '</select>';
//                            echo '<br>';

                            echo '
                                <label for="email">' . Translation::getFileContents("label_email") . '</label>
                                <input class="textfield" type="email" id="email" name="email" value="'.$user["email"].'" /><br>';

                            echo '
                                <label for="username">' . Translation::getFileContents("label_username") . '</label>
                                <input class="textfield" required type="text" id="username" name="username" value="'.$user["username"].'" /><br>';

                            echo '
                                <label for="passwd">' . Translation::getFileContents("label_password") . '</label>
                                <input class="textfield" type="text" id="passwd" name="passwd" value="" /><br>';

                            echo '
                                <label for="phone">' . Translation::getFileContents("label_phone") . '</label>
                                <input class="textfield" type="text" id="phone" name="phone" value="'.$user["phone"].'" /><br>';

                            echo '
                                <label for="street">' . Translation::getFileContents("label_street") . '</label>
                                <input class="textfield" type="text" id="street" name="street" value="'.$user["street"].'" /><br>';

                            echo '
                                <label for="zip">' . Translation::getFileContents("label_zip") . '</label>
                                <input class="textfield" type="text" id="zip" name="zip" value="'.$user["zip"].'" /><br>';

                            echo '
                                <label for="city">' . Translation::getFileContents("label_city") . '</label>
                                <input class="textfield" type="text" id="city" name="city" value="'.$user["city"].'" /><br>';

                            echo '
                                <label for="specialIndexText">' . Translation::getFileContents("label_specialIndexText") . '</label>
                                <input class="textfield" type="text" id="specialIndexText" name="specialIndexText" value="'.(empty($user["specialIndexText"]) ? "index_text" : $user["specialIndexText"]).'" /><br>';
                            
                            echo '
                                <label for="redirectKey">' . Translation::getFileContents("label_redirectKey") . '</label>
                                <input class="textfield" type="text" id="redirectKey" name="redirectKey" value="'.(empty($user["redirect_key"]) ? "index" : $user["redirect_key"]).'" /><br>';


                            echo '<div class="btn-group">
                                        <button type="submit" name="deleteBtn" class="btn btn-sm btn-delete">' . Translation::getFileContents("delete") . '</button>
                                        <button type="submit" name="updateBtn" class="btn btn-sm btn-update">' . Translation::getFileContents("update") . '</button>
                                     </div>';
                            echo '</form>';
                            echo '<a href="'.getUri().'?page=setupFileaccess&filter='.$user["username"].'"><button class="btn btn-sm btn-add" type="button">'.Translation::getFileContents("Fileaccess").'</button>';
                            echo '</div>';
                            
                        }
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