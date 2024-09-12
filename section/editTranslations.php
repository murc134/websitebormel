<section id="underconstructionsection" class="section-padding" style="">

    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="wrap-item text-center">
                    <div id="info" class="">

                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="wrap-item text-center">

                                        <?php
                                        
                                        // languages
                                        $languages = array();
                                        if (isset($_GET["languages"]) && !empty($_GET["languages"])) {
                                            
                                            $token = strtok($_GET["languages"], ";");
                                            while ($token !== false) {
                                                if (Translation::isValidLanguage($token)) {
                                                    $languages[] = Translation::getLanguageByIso($token);
                                                }
                                                $token = strtok(";");
                                            }
                                        }
                                        if (count($languages) == 0) {
                                            $languages = Translation::getLanguages();

                                        }
                                        //var_dump($languages);
                                        if (isset($_GET["find"]) && !empty($_GET["find"])) {
                                            echo Translation::renderTranslationForm($languages, $_GET["find"]);
                                        } else {
                                            echo Translation::renderTranslationForm($languages);
                                        }
                                        ?>

                                        
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>