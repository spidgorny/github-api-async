<?php

/**
 * @param int $length
 * @return string
 * http://php.net/manual/en/function.random-bytes.php#118932
 */
function RandomToken($length = 32){
	if(!isset($length) || intval($length) <= 8 ){
		$length = 32;
	}
	if (function_exists('random_bytes')) {
		return bin2hex(random_bytes($length));
	}
	if (function_exists('mcrypt_create_iv')) {
		return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
	}
	if (function_exists('openssl_random_pseudo_bytes')) {
		return bin2hex(openssl_random_pseudo_bytes($length));
	}
}

sleep(1);
echo RandomToken();
