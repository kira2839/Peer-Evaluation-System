<?php

require('student_model.php');

class ConfirmationCode
{
    const CONFIRMATION_CODE_SIZE = 10;
    //900 seconds i.e. 15 minutes
    const TIME_FOR_CODE_EXPIRY = 900;

    private $studentModel;

    function __construct()
    {
        $this->studentModel = new StudentModel();
    }

    public function generateCode($email)
    {
        //Encrypt the email id + current timestamp
        $encryptMethod = "AES-256-CBC";
        $secretKey = '190oasidjo*123n-dj';
        $secretIV = 'djo*djhoasiwdkjegfwen334';
        $key = hash('sha256', $secretKey);

        $timeInSecs = time();
        $currentTime = date('Y-m-d H:i:s T', $timeInSecs);
        if ($currentTime === false) {
            return false;
        }

        $msgToBeEncrypted = $email . $currentTime;
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secretIV), 0, 16);
        $encryptedMsg = openssl_encrypt($msgToBeEncrypted, $encryptMethod, $key, 0, $iv);
        if ($encryptedMsg === false) {
            return false;
        }
        return base64_encode($encryptedMsg);
    }

    public function validateCode($confirmationCode, $emailAddress)
    {
        //Decrypt the email id + current timestamp
        $encryptMethod = "AES-256-CBC";
        $secretKey = '190oasidjo*123n-dj';
        $secretIV = 'djo*djhoasiwdkjegfwen334';
        $key = hash('sha256', $secretKey);

        $iv = substr(hash('sha256', $secretIV), 0, 16);
        $decryptedMessage = openssl_decrypt(base64_decode($confirmationCode), $encryptMethod, $key, 0, $iv);
        if ($decryptedMessage === false) {
            echo "Invalid Email Address/Confirmation code";
            return;
        }

        //Get the timestamp
        $timestampFromConfirmationCode = stristr($decryptedMessage, "@buffalo.edu", false);
        //Replace the @buffalo.edu string to get only timestamp
        $timestampFromConfirmationCode = substr_replace($timestampFromConfirmationCode, "", 0, 12);

        if ($timestampFromConfirmationCode === false) {
            echo "Invalid Email Address/Confirmation code";
            return;
        }

        //Get the mail ID from confirmation code
        $mailIDFromConfirmationCode = stristr($decryptedMessage, "@buffalo.edu", true);
        if ($mailIDFromConfirmationCode === false) {
            echo "Invalid Email Address/Confirmation code";
            return;
        }

        //If the mail id and confirmation code doesn't match
        if ($mailIDFromConfirmationCode . "@buffalo.edu" !== $emailAddress) {
            echo "Invalid Email Address/Confirmation code";
            return;
        }

        $timeInSecs = time();

        if ($timeInSecs !== false) {
            $timestampFromConfirmationCode = DateTime::createFromFormat('Y-m-d H:i:s T', $timestampFromConfirmationCode);
            $sinceConfirmationCodeGenerated = $timeInSecs - $timestampFromConfirmationCode->getTimestamp();
            if ($sinceConfirmationCodeGenerated >= self::TIME_FOR_CODE_EXPIRY) {
                echo "The confirmation code is expired. Please get another code";
                return;
            } else if ($sinceConfirmationCodeGenerated < 0) {
                echo "Invalid Email Address/Confirmation code";
                return;
            }
            echo "Validated your details. Soon the evaluation system will be ready for next step. Try Later";
        } else {
            echo "We are facing issue while verifying confirmation code. Please contact course Instructor";
            return;
        }
    }
}