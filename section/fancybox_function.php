
<script>
    $(document).ready(function () {
        $('.fancybox').fancybox();
        <?php 
        if (!getMessageSeen("under_construction")) {
        echo '$("#inline1").fancybox().trigger("click")';
        }
        ?>
    });
</script>
<a class="fancybox" href="#inline1" title="Lorem ipsum dolor sit amet">Inline</a>
<div id="inline1" style="display: none;">
    <?php
    if (!getMessageSeen("under_construction")) {
        include 'section/underconstruction.php';
        setMessageSeen("under_construction");
    }
    ?>
</div>