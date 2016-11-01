<?php
/**
 * Functions
 * by @z1web
 */


/**
 * Function for debugging with <pre>
 *
 * @param $var
 */
function dbg($var) {
	echo '<pre id="msg" style="opacity:0.9;z-index: 99999999;position: relative;background-color: #fff;padding: 10px;margin: 10px;border: 1px #292222 solid;">';
	if (!is_string($var)) var_dump($var);
	else echo $var;
	echo '</pre>';
}

/**
 * Getting value of element of array $_REQUEST without checking on isset
 *
 * @param $reqName
 * @param string $type
 * @return null
 */
function getRequest($reqName, $type='all') {
	if ($type=='all') {
		if (isset($_REQUEST[$reqName]))
			$request = $_REQUEST[$reqName];
		else
			$request = null;
	}
	return $request;
}

/**
 * Redirect HEADER
 *
 * @param $url
 */
function redirect($url) {
	header('Location: ' . $url);
}

/**
 * Curl HTTP query function
 *
 * @param $url
 * @param $postFields array
 * @return mixed
 */
function curl($url, $postFields=[]) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	if (!empty($postFields)) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close($ch);

	return $server_output;
}