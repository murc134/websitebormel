<?php 
include_once "tools/functions.php";
include_once "tools/translation.php";
// Ensure no output before this point
$languages = Translation::getLanguages(1, "asc");
$currentLang = $_SESSION['lang_iso']; // Get the current language from session
if(count($languages) > 1){
?>
<div id="languageSelector" class="custom-dropdown">
    <button id="selectedLanguage" class="language-button">
        <img src="img/flags/<?php echo $currentLang; ?>.jpg" alt="Selected Language">
        <span class="language-text">
            <?php 
                foreach ($languages as $lang) {
                    if ($lang["iso"] == $currentLang) {
                        echo $lang['native_name'];
                        break;
                    }
                }
            ?>
        </span>
        <span class="arrow">&#9662;</span>
    </button>
    <ul id="languageDropdown" class="lang-dropdown-menu">
        <?php foreach ($languages as $lang) { ?>
            <li data-lang="<?php echo $lang['iso']; ?>">
                <img src="img/flags/<?php echo $lang['iso']; ?>.jpg" alt="<?php echo $lang['native_name']; ?>">
                <span><?php echo $lang['native_name']; if($_SESSION["lang_iso"] == $lang['iso']) {echo " &#10003;";} ?></span>
            </li>
        <?php } ?>
    </ul>
</div>
<script>
    document.getElementById('selectedLanguage').addEventListener('click', function () {
    document.getElementById('languageDropdown').classList.toggle('show');
});

document.querySelectorAll('.lang-dropdown-menu li').forEach(function (item) {
    item.addEventListener('click', function () {
        var selectedLang = this.getAttribute('data-lang');
        var imgSrc = this.querySelector('img').getAttribute('src');
        var langName = this.querySelector('span').textContent;

        document.getElementById('selectedLanguage').querySelector('img').setAttribute('src', imgSrc);
        document.getElementById('selectedLanguage').querySelector('.language-text').textContent = langName;

        var currentUrl = window.location.href.split('?')[0];
        var newUrl = currentUrl + "?lang=" + selectedLang + "&page=<?php echo $_SESSION['page']; ?>";
        window.location.href = newUrl;
    });
});

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.closest('.language-button')) {
        var dropdowns = document.getElementsByClassName("lang-dropdown-menu");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
};
    </script>
<?php } ?>
