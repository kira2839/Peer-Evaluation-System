<?php

if (isset($_POST['email_id'])) {

	$email = $_POST['email_id'];
    $subject = "Confirmation code for the evaluation";

    // Encrypt Script Starts Here
		$output = false;
		$encrypt_code = "";
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'SECRET_KEY';
		$secret_iv = 'RANDOM_SEED';
		// hash
		$key = hash('sha256', $secret_key);
		$timeinsecs = time();
		$currentTime = date('Y-m-d @h:i:s A', $timeinsecs);
		$email = $email . $currentTime;
		
		
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		$output = openssl_encrypt($email, $encrypt_method, $key, 0, $iv);
		$confirmationCode = base64_encode($output);
		
		// $decryptedMessage = openssl_decrypt(base64_decode($confirmationCode), $encrypt_method, $key, 0, $iv);
		// echo "Encryption Code is :" .$confirmationCode;
			
	// Encrypt Script Ends Here
	
    //Construct email body to be sent
    // $confirmationCode = "CONFIRMED";
    $msg = "Dear Student,\r\n\r\nThank you for initiating evaluation process.\r\n" .
        "Use confirmation code: " . $confirmationCode .
        " to begin the evaluation.\r\nP.S. " .
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
		
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else {
    echo "HTML Attribute 'Required' is not set in the HTML input form.";
}