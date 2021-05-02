<?php

function login_django_https($username, $password, $url) {
	/*
	Log in to Tournesol Django using an https request.

	Args:
		$username: username of the user trying to log in
		$password: password of the user trying to log in in plain text
		$url: the url to send requests to

	Returns:
	    [authorized==true if the login was successful, false on authentication failure
	    id is a unique identified]
	    throws exceptions in case if connection failed/database does not exist/user does not exist
	*/

    // will do a PATCH request
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // sending json and receiving json
    $headers = array(
       "Content-Type: application/json",
       "Accept: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    // username and password as JSON
    $data = json_encode(['username' => $username, 'password' => $password]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    // executing the request
    $resp = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // by-default, unauthorized
    $authorized = false;

    // resulting ID is invalid
    $id_db = -1;

    // on success, return the ID
    if($code === 200) {
        $authorized = true;
        $resp_decoded = json_decode($resp, true);
        $id_db = $resp_decoded['user__id'];
    }

	return ['authorized' => $authorized, 'id' => $id_db];
}

?>
