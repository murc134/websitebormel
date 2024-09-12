<section id="cableplugsection" class="" style="">
    <?php 
    echo SVG::renderCableboxOn(1000, 420, 1, 'class="footerimg" id="svgElectricityOn"');
    echo SVG::renderCableboxOff(1000, 420, 1, 'class="footerimg hidden" id="svgElectricityOff"');
    ?>
    <script type="text/javascript">
        var on = true;
        $("#cableplugsection").click(function () {
            on = !on;

            var colors = <?php echo getColorArrayJS(); ?>;
            var i = Math.floor(Math.random() * (colors.length));

            //console.log(colors);
            //console.log(i);
            if (on) {
                //$("#electric")[0].play();
                $("#svgElectricityOff").addClass('hidden');
                $("#svgElectricityOn").removeClass('hidden');
                $("#colorStylesheet").attr('href', 'css/colors/' + colors[i] + '.css');
                $("#mapimage").attr('src', 'img/maps/' + colors[i] + '.jpg');
            }
            else
            {
                $("#svgElectricityOn").addClass('hidden');
                $("#svgElectricityOff").removeClass('hidden');
                $("#colorStylesheet").attr('href', 'css/colors/off.css');
                $("#mapimage").attr('src', 'img/maps/off.jpg');
            }
        });
    </script>
</section>
<script type="text/javascript">
    var repairCSize = function () {
        var height = $("#svgElectricityOn").height();
        $("#cableplugsection").css("height",height);
    }
    $(document).ready(function () {
        repairCSize();
    });
    $(window).on('resize', function () {
        repairCSize();
    });
</script>