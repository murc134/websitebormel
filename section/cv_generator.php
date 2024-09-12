
<form action="tools/tcpdf/custom/PDFCreator.php" method="get">
    <input id='type' name='type' type="hidden" value='cv'>
    <select id="color">
        <option <?php
        if ($_SESSION["color"] == "blue") {
            echo "selected";
        }
        ?> value="5b79a1"><?php echo ucfirst(Translation::getFileContents("blue")); ?></option>
        <!--                                <option <?php
        if ($_SESSION["color"] == "green") {
            echo "selected";
        }
        ?> value="4d8550"><?php echo ucfirst(Translation::getFileContents("green")); ?></option>
                                        <option <?php
        if ($_SESSION["color"] == "red") {
            echo "selected";
        }
        ?> value="ac3e3e"><?php echo ucfirst(Translation::getFileContents("red")); ?></option>-->
        <option value="other"><?php T("custom"); ?></option>
    </select>

    <?php
    $col = "#5b79a1";
    if ($_SESSION["color"] == "Green") {
        $col = "#4d8550";
    } else if ($_SESSION["color"] == "Red") {
        $col = "#ac3e3e";
    }
    ?>
    <input type="hidden" id="setcolor" name="color" value="<?php echo $col; ?>">
    <input type="color" id="color_chosen" value="<?php echo $col; ?>"><br>
    <select id="lang" name="lang">
        <?php foreach (Translation::getLanguages(1) as $lang) { ?>
            <option value="<?php echo $lang["iso"]; ?>"><?php echo ucfirst(Translation::getFileContents($lang["iso"])); ?></option>
        <?php } ?>
    </select>
    <button type="submit" id='generateBtn'><?php T("GenerateCV"); ?></button>
</form>
</div>

<script>
    $('#generateBtn').click(function () {
        $('.fancybox').fancybox();
        $("#inline1").fancybox({
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            hideOnOverlayClick: false,
            hideOnContentClick: false
        }).trigger("click");

    });
</script>
<div id="inline1" style="display: none;">
    <?php
    include 'section/snake.php';
    ?>
</div>
<script type="text/javascript">
    var btn = $('#generateBtn');
    var col = $('#color');
    var setcol = $('#setcolor')
    var lang = $('#language');
    var col_chosen = $('#color_chosen');
    $(document).ready(function () {
        col_chosen.val("#" + col.val());
        setcol.val(col_chosen.val().substr(1, 6));
    });
    col.change(function () {
        //alert();
        if (col.val() !== "other") {
            col_chosen.val("#" + col.val());
            setcol.val(col_chosen.val().substr(1, 6));
        }
    });
    col_chosen.change(function () {
        col.val("other"); // 
        setcol.val(col_chosen.val().substr(1, 6));
    });
</script>