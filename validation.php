<?php

// get email and confirmation code

    $email = $_POST['email_id_tab2'];
    
    //$email = "gouthamt@buffalo.edu";
    
    $confirmationCode = $_POST['confirmation_code'];

    //$confirmationCode = "ZVNKWFdvd25HbGhtRDVvd3o1VjdoZ0NNM2Jva1JIcS95eG8zeXk1em4zST0=";

    $encrypt_method = "AES-256-CBC";

    $secret_key = '190oasidjo*123n-dj';
	
    $secret_iv = 'RANDOM_SEED';
		// hash
    $key = hash('sha256', $secret_key);

    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    $decryptedMessage = openssl_decrypt(base64_decode($confirmationCode), $encrypt_method, $key, 0, $iv);

    $letters = preg_replace('/[^a-zA-Z@.]/', '', $decryptedMessage);

    echo $letters;
   
    $numbers = preg_replace('/[^0-9]/', '', $decryptedMessage);

    echo $numbers;

    echo "\n";

    echo time();

    

    if ($letters == $email and time()-$numbers <= 15*60)
{   
    print "login successful\n";
}

   else
{
   print "either email or confirmation code has exceeded time limit.";
}
