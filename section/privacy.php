<?php
$person = getPersonalData();
?>
<section id="privacy" class="section-padding " style="">
    <div id="info" class="">

        <div class="container">
            <div class="row">
                <div class="col-xs-12 pageContent">
                    <div class="wrap-item text-left">

                        <div class='privacy'>
                            <h2><?php T("datenschutzerklaerung_title"); ?></h2>
                            <?php if (!empty($person["company_name"])) {
                                echo $person["company_name"];
                            } ?><br />
                            <p><?php echo $person["firstname"] . " " . $person["lastname"]; ?><br />
                                <?php echo $person["street"] ?><br /><?php echo $person["zip"] . " " . $person["city"]; ?></p>
                            <p><?php T("phone"); ?>: <?php echo $person["phone"] ?><br />
<?php T("mail"); ?>: <a href="mailto:<?php echo $person["email"]; ?>"><?php echo $person["email"]; ?></a><br />
                            </p>
                            <?php
                                T("datenschutzerlaerung_text");
                            ?>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>