<?php

class Email
{
    const CONFIRMATION_CODE_SIZE = 10;
    const SUBJECT = "Confirmation code for the evaluation";

    private $emailAddress;

    function __construct($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    public function validateEmailAddress()
    {
        $ubItName = stristr($this->emailAddress, "@buffalo.edu", true);
        if ($ubItName === false) {
            return false;
        }

        return true;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function sendMail($confirmationCode)
    {
        //Form the link for start evaluation and send as part of message
        $actualLink = "http://$_SERVER[HTTP_HOST]";
        $requestURI = $_SERVER['REQUEST_URI'];
        $requestURI = strstr($requestURI, "backend/send_code.php", true);
        if ($requestURI !== false) {
            $actualLink = $actualLink . $requestURI . "index.html#tabs-2";
        } else {
            $actualLink = $actualLink . "index.html#tabs-2";
        }

        //Construct email body to be sent
        $msg = "Dear Student,\r\n\r\nThank you for initiating evaluation process.\r\n" .
            "Use confirmation code: " . $confirmationCode .
            " to begin your evaluation.\r\nTo start evaluation go to " . $actualLink . " and enter your details.\r\nP.S. " .
            "The code will be valid for next 15 minutes only. You can re-generate if necessary.\r\n\r\nRegards,\r\nInstructor/TA";
        try {
            //send message with code to student
            ob_start();
            $return = mail($this->emailAddress, self::SUBJECT, $msg,
                "From:no-reply@buffalo.com\r\nReply-To: no-reply@buffalo.com");
            ob_end_clean();

            if ($return == true) {
                print "Thank you for contacting us!\n";
                print "The confirmation code is on its way. Check your email: " . $this->emailAddress;
            } else {
                print "Failed to send confirmation code. Try again after sometime";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

