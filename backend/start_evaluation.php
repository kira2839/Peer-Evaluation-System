<?php

include_once('session.php');

// We get the created session instance if already
$sessionObj = Session::getInstance();

if (!isset($_SESSION['email_id']) OR ($_SESSION['Agent'] != sha1($_SERVER['HTTP_USER_AGENT']) )) {
    echo "Session is invalid";
    $sessionObj->destroy();
    //Utility::redirectUser("index.html");
    return;
}

echo "Validated your details. Soon the evaluation system will be ready for next step. Try Later";
$sessionObj->destroy();