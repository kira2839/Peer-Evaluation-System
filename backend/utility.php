<?php
/* Use static function as a counter */

class Utility
{
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