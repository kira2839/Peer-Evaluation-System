<?php

if (isset($_POST['email_id'])) {

    $email = $_POST['email_id'];
    $subject = "Confirmation code for the evaluation";

    $ubItName = stristr($email, "@buffalo.edu", true);
    if ($ubItName === false) {
        echo "Please enter buffalo mail id only.";
        return;
    }

    //Encrypt the email id + current timestamp
    $encryptMethod = "AES-256-CBC";
    $secretKey = '190oasidjo*123n-dj';
    $secretIV = 'djo*djhoasiwdkjegfwen334';
    $key = hash('sha256', $secretKey);

    $timeInSecs = time();
    $currentTime = date('Y-m-d H:i:s T', $timeInSecs);
    if ($currentTime === false) {
        echo "We are facing issue while sending confirmation code. Please contact course Instructor.";
        return;
    }

    $msgToBeEncrypted = $email . $currentTime;
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secretIV), 0, 16);
    $encryptedMsg = openssl_encrypt($msgToBeEncrypted, $encryptMethod, $key, 0, $iv);
    if ($encryptedMsg === false) {
        echo "We are facing issue while sending confirmation code. Please contact course Instructor.";
        return;
    }
    $confirmationCode = base64_encode($encryptedMsg);

    $decryptedMessage = openssl_decrypt(base64_decode($confirmationCode), $encryptMethod, $key, 0, $iv);
    $timestampFromConfirmationCode = stristr($decryptedMessage, "@buffalo.edu", false);
    $timestampFromConfirmationCode = substr_replace($timestampFromConfirmationCode, "", 0, 12);
    if ($timestampFromConfirmationCode === false) {
        echo "Invalid confirmation code";
        return;
    }
    $mailIDFromConfirmationCode = stristr($decryptedMessage, "@buffalo.edu", true);
    if ($mailIDFromConfirmationCode === false) {
        echo "Invalid confirmation code";
        return;
    }
    if ($mailIDFromConfirmationCode . "@buffalo.edu" !== $email) {
        echo "Invalid confirmation code";
        return;
    }

    $timeInSecs = time();
    $currentTime = date('Y-m-d H:i:s T', $timeInSecs);

    if ($timeInSecs !== false) {
        $timestampFromConfirmationCode = DateTime::createFromFormat('Y-m-d H:i:s T', $timestampFromConfirmationCode);
        $sinceConfirmationCodeGenerated = $timeInSecs - $timestampFromConfirmationCode->getTimestamp();
        if ($sinceConfirmationCodeGenerated >= 900) {
            echo "the Code is expired";
            return;
        } else if ($sinceConfirmationCodeGenerated < 0) {
            echo "Invalid confirmation code";
            return;
        }
    }

    $actualLink = "http://$_SERVER[HTTP_HOST]";
    $requestURI = $_SERVER['REQUEST_URI'];
    $requestURI = strstr($requestURI, "send_mail.php", true);
    if ($requestURI !== false) {
        $actualLink = $actualLink . $requestURI . "index.html#tabs-2";
    } else {
        $actualLink = $actualLink . "index.html#tabs-2";
    }

    //Construct email body to be sent
    $msg = "Dear Student,\r\n\r\nThank you for initiating evaluation process.\r\n" .
        "Use confirmation code: " . $confirmationCode .
        " to begin the evaluation.\r\n To start evaluation go to " . $actualLink . " link.\r\nP.S. " .
        "The code will be valid for 15 minutes only.\r\n\r\nRegards,\r\nInstructor/TA";

    try {
        //send message with code to student
        ob_start();
        $return = mail($email, "$subject", $msg, "From:no-reply@buffalo.com\r\nReply-To: no-reply@buffalo.com");
        ob_end_clean();

        if ($return == true) {
            print "Thank you for contacting us!\n";
            print "The confirmation code is on its way. Check your email: " . $email;
        } else {
            print "Thank you for contacting us!\n";
            print "The confirmation code is on its way. Try again if you don't receive shortly.";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else {
    echo "Student Email is not set";
}