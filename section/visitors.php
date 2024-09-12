<section id="visitors" class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="wrap-item text-center">
                    <b><?php T("Visitors"); 
                    $today = VisitorHandler::get_today(true);
                    $week = VisitorHandler::get_week(true);
                    $month = VisitorHandler::get_month(true);
                    $total = VisitorHandler::get_total(true);
                    ?></b><br><br>
                    <?php if($today < $week) {T("Today"); echo ": ".$today."<br>"; } ?>
                    <?php if($week < $month) { T("Week"); echo ": ".$week."<br>"; } ?>
                    <?php if($month < $total) { T("Month"); echo ": ".$month."<br>"; } ?>
                    <?php T("Total"); echo ": ".$total."<br>"; ?>
                </div>
            </div>
            <div class="wrap-item text-center">
                <a id="impressum_link" href="index.php?page=imprint" target="_BLANK"><?php T("impressum");?></a>
            </div>
        </div>
    </div>
</section>