<div class = "topnav" id = "navigation">
<?php 

    function get_navlink($page, $label)
    {
        echo '<a href="?page=';
        echo $page; echo '"';
        if ($_SESSION["page"] == $page) { echo 'class="selected"'; }
        echo '>';
        echo Translation::get($label).'</a>';
    }

    //$pages_old = ["index" => "start", "about" => "about", "portfolio" => "portfolio", "workexperience" => "workexperience", "downloads" => "Downloads"];
    $pages = ["index" => "start", "about" => "about", "workexperience" => "workexperience", "downloads" => "Downloads"];
    
    foreach ($pages as $page => $label) { 
        get_navlink($page, $label);
    } ?>
    
    
    
    <a href = "javascript:void(0);" class = "icon" onclick = "showMenu()">
    <?php
    //&#9776; 
    echo SVG::renderMenuSymbol(25,25,1);
    ?>
    </a>
</div>

<script>
    /* Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon */
    function showMenu() {
        var x = document.getElementById("navigation");
        if (x.className === "topnav") {
            x.className += " responsive";
        } else {
            x.className = "topnav";
        }
    }
</script>