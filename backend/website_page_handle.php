<?php
/* Use static function as a counter */

class WebSitePageHandle
{
    public static function includeSiteHeader() {
        echo <<<EOC
<!DOCTYPE html>
<html lang="us">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/humanity/jquery-ui.css">
    <link href="../main_site.css" type="text/css" rel="stylesheet">
    <link href="../start-evaluation-table.css" type="text/css" rel="stylesheet">
    <title>Peer Evaluations</title>
</head>
<div id="nav-bar">
    <ul class="nav_bar">
        <li class="bar_li">
            <a href="../index.html" aria-label="UB logo"><i class="icon icon-ub-logo"></i>
                <img class="logo-black" src="../ub_logo.png" alt="University at Buffalo print logo">
                <span class="ub-logo">Peer Evaluations</span>
            </a></li>
        <li style="float:right" class="bar_li"><a class="about orange" href="../about_us.html">About Us</a></li>
    </ul>
</div>
<br> <br>
EOC;
    }
    public static function redirectUser($page = 'start_evaluation.php')
    {
        // Start defining the URL...
        // URL is http:// plus the host name plus the current directory:
        $url = 'http://' . $_SERVER
            ['HTTP_HOST'] . dirname
            ($_SERVER['PHP_SELF']);

        // Remove any trailing slashes:
        $url = rtrim($url, '/\\');

        // Add the page:
        $url .= '/' . $page;

        // Redirect the user:
        header("Location: $url");
        exit();
    }
}