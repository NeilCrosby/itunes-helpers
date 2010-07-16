<?php

# before running this you'll need to create a file called auth.txt in this
# directory which contains your last.fm apikey and secret on seprate lines
# You'll also need to make sure your last.fm API key callback URL is set to
# this page.
# A bit of a shambles codewise (it's a c&p from the php api examples), but it
# works and only needs to be run once.

require_once('phplastfmapi-0.7.1/lastfmapi/lastfmapi.php');

if ( !empty($_GET['token']) ) {
	$vars = array(
		'apiKey' => 'yourkey',
		'secret' => 'yoursecret',
		'token' => $_GET['token']
	);
	
	$auth = new lastfmApiAuth('getsession', $vars);

	$file = fopen('auth.txt', 'w');
	$contents = $auth->apiKey."\n".$auth->secret."\n".$auth->username."\n".$auth->sessionKey."\n".$auth->subscriber;
	fwrite($file, $contents, strlen($contents));
	fclose($file);
	
	echo 'New key has been generated and saved to auth.txt<br /><br />';
	echo '<a href="'.$_SERVER['PHP_SELF'].'">Reload</a>';
}
else {
	$file = fopen('auth.txt', 'r');
	$vars = array(
		'apiKey' => trim(fgets($file)),
		'secret' => trim(fgets($file)),
		'username' => trim(fgets($file)),
		'sessionKey' => trim(fgets($file)),
		'subscriber' => trim(fgets($file))
	);
	$auth = new lastfmApiAuth('setsession', $vars);
	
	echo '<b>API Key:</b> '.$auth->apiKey.'<br />';
	echo '<b>Secret:</b> '.$auth->secret.'<br />';
	echo '<b>Username:</b> '.$auth->username.'<br />';
	echo '<b>Session Key:</b> '.$auth->sessionKey.'<br />';
	echo '<b>Subscriber:</b> '.$auth->subscriber.'<br /><br />';

	echo '<a href="http://www.last.fm/api/auth/?api_key='.$auth->apiKey.'">Get New Key</a>';
}

?>