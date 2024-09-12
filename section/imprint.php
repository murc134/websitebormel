<?php
$person = getPersonalData();
?>
<section id="impressum" class="section-padding " style="">
    <div id="info" class="">

        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="wrap-item text-left">

                        <div class='impressum'>
                            <h2><?php T("impressum"); ?></h2>
                            <?php if(!empty($person["company_name"])){ echo $person["company_name"];} ?><br />
                            <p><?php echo $person["firstname"] . " " . $person["lastname"]; ?><br />
                            <?php if(!empty(UserHandler::isLogged())) { ?>
                            <?php echo $person["street"] ?><br /><?php echo $person["zip"] . " " . $person["city"]; ?></p>
                            <p><?php T("phone"); ?>: <?php echo $person["phone"] ?><br />
                                <?php T("mail"); ?>: <a href="mailto:<?php echo $person["email"]; ?>"><?php echo $person["email"]; ?></a><br />
                            </p>
                            <p><strong><?php T("Umsatzsteuer-ID"); ?>: </strong>
                            <br />
                            <?php T("imprint_text_ust_id"); ?>:<br /><?php echo $person["tax_number"] ?></p>
                            <?php } ?>
                            <?php T("Berufsbezeichnung"); ?>: <?php T($person["job_name"]); ?>
                            <br /><p><?php T("imprint_responsible_for_content"); ?>:<br /><?php echo $person["firstname"]; ?>, <?php echo $person["lastname"]; ?>, <?php echo $person["street"]; ?> in <?php echo $person["zip"] . " " . $person["city"]; ?></p>
                            <p><strong><?php T("external_sources"); ?>:</strong><br />
                            <?php include 'section/external_sources.php'; ?>
                            </p>
                            <br /><br />
                            <?php T("full_disclaimer_content") ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>