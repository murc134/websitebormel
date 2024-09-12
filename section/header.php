<?php
if (defined('_DEBUG_MODE_') && !empty(_DEBUG_MODE_)) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

function get_header_info() {
    $_SESSION["page"] = "index";
    if (isset($_GET["page"])) {
        $_SESSION["page"] = $_GET["page"];
    } else if (isset($_POST["page"])) {
        $_SESSION["page"] = $_POST["page"];
    }

    if (!Translation::exists("META_TITLE_" . $_SESSION["page"])) {
        $_SESSION["page"] = "index";
    }
}

//error_reporting(E_ALL);

include_once dirname(__FILE__) . '/../config/defines.php';
include_once dirname(__FILE__) . '/../config/encryption.php';
include_once dirname(__FILE__) . '/../tools/mobiledetect/Mobile_Detect.php';
include_once dirname(__FILE__) . '/../tools/translation.php';
include_once dirname(__FILE__) . '/../tools/functions.php';
include_once dirname(__FILE__) . '/../tools/sessioncontrol.php';

get_header_info();

UserHandler::trackVisitor();

$detect = new Mobile_Detect();

$_SESSION["color"] = getThemeColor();
$color = $_SESSION["color"];

handleLogin();
?>

<!--Meta Data-->
<meta charset="utf-8"/>
<title><?php T("META_TITLE_" . $_SESSION["page"]); ?></title>
<meta name="author" content="Christoph Brucksch">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="keywords" content="<?php T("META_Keywords_" . $_SESSION["page"]); ?>">
<meta name="description" content="<?php T("META_Description_" . $_SESSION["page"]); ?>">
<meta name="date" content="2021-03-01T00:00:37+02:00">
<link id="favicon" rel="icon" type="image/png" href="<?php echo getUri() . "img/favicon.png"; ?>" />

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<!--JS Includes-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!--Custom CSS-->
<link rel="stylesheet" href="css/shared.css">
<link rel="stylesheet" href="css/languageNav.css">
<link rel="stylesheet" href="css/battery.css">
<link rel="stylesheet" href="css/languageSkills.css">
<!--    <link rel="stylesheet" href="css/nicepage.css">-->
<!--<link rel="stylesheet" href="css/blue.css">-->
<!--CSS Includes-->

<?php
include_once dirname(__FILE__) . '/fancybox_header.php';

switch ($_SESSION["page"]) {
    case "portfolio":

        break;
    case "administration":
    case "setupImages":
    case "setupVideos":
    case "setupYoutubeVideos":
    case "setupKeywords":
    case "setupFiles":
    case "setupProjects";
    case "setupUrls":
    case "setupUsers":
    case "setupFileaccess":
    case "setupTranslations":
        echo '<meta name="robots" content="noindex, nofollow" />
              <meta name="googlebot" content="noindex, nofollow" />
              <meta name="googlebot-news" content="noindex, nofollow" />';
        break;
    default:
        break;
}
?>