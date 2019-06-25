<?php

class Email
{
    const CONFIRMATION_CODE_SIZE = 10;

    private $emailAddress;

    function __construct($emailAddress)
    {
        //Remove the white spaces and tab if it contains
        $this->emailAddress = trim($emailAddress);
    }

    public function isStudentPartOfEvaluationSystem()
    {
        return StudentModel::getInstance()->checkStudentEmail($this->emailAddress);
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

    public function sendCodeMail($confirmationCode)
    {
        //Form the link for start evaluation and send as part of message
        $actualLink = "https://$_SERVER[HTTP_HOST]";
        $requestURI = $_SERVER['REQUEST_URI'];
        $requestURI = strstr($requestURI, "backend/send_code.php", true);
        if ($requestURI !== false) {
            $actualLink = $actualLink . $requestURI . "index.html#tabs-2";
        } else {
            $actualLink = $actualLink . "index.html#tabs-2";
        }

        $subject = "Confirmation code for the evaluation";
        //Construct email body to be sent
        $msg = "Dear Student,\r\n\r\nThank you for initiating evaluation process.\r\n" .
            "Use confirmation code: " . $confirmationCode .
            " to begin your evaluation.\r\nTo start evaluation go to " . $actualLink . " and enter your details.\r\nP.S. " .
            "The code will be valid for next 15 minutes only. You can re-generate if necessary.\r\n\r\nRegards,\r\nInstructor/TA";
        try {
            //send message with code to student
            ob_start();
            $return = mail($this->emailAddress, $subject, $msg,
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

    public function sendAckMailOnEvaluation($evaluation)
    {
        //Form the link for home page and send as part of message
        $actualLink = "https://$_SERVER[HTTP_HOST]";
        $requestURI = $_SERVER['REQUEST_URI'];
        $requestURI = strstr($requestURI, "backend", true);
        $actualLink = $actualLink . $requestURI;

        $subject = "Evaluation confirmation";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From:no-reply@buffalo.com' . "\r\n";
        $headers .= 'Reply-To: no-reply@buffalo.com';

        //Construct email body to be sent
        ob_start();
        echo <<< EOC
Dear Student, <br> <br>
Thank you for submitting evaluation. This is a confirmation message and a copy of your evaluation is given below for future reference. <br><br>
$evaluation<br>
You can edit or view at <a href="$actualLink">site</a> by logging in again.<br><br>Regards,<br>Instructor/TA
EOC;
        $msg = ob_get_contents();
        ob_end_clean();

        try {
            //send message with code to student
            ob_start();
            $return = mail($this->emailAddress, $subject, $msg, $headers);
            ob_end_clean();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

