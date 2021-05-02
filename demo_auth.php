<?php

require 'https_django_auth.php';

// command-line interface
if ('cli' === PHP_SAPI) {
    $options_default = [
        "username" => "",
        "password" => "",
        "url" => "",
    ];
    $options = getopt('', ["username:", "password:",
                           "url:"]);
    // var_dump($options);
    $options = $options + $options_default;
    // var_dump($options);

    if(!$options['username'] || !$options['password'] || !$options['url']) {
        $fn = $_SERVER['SCRIPT_FILENAME'];
        echo "Usage: php $fn --username=LOGIN_USERNAME --password=LOGIN_PASSWORD ";
        echo "--url=LOGIN_URL";
        echo "\n";
        exit(1);
    }

    // logging in
    try {
	$result_arr = call_user_func_array("login_django_https", $options);
	$result = $result_arr['authorized'];
	$id = $result_arr['id'];
        $error = "Wrong password";
    } catch (Exception $e) {
        $result = false;
        $error = $e->getMessage();
    }


    if($result) {
        echo "Login successful $id\n";
        exit(0);
    }
    else {
        echo "Login failed: $error\n";
        exit(1);
    }
}

?>
