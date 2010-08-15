<?php
require_once('phplastfmapi-0.7.1/lastfmapi/lastfmapi.php');

$playlist = '5 Star';

$info = pathinfo(__FILE__);
exec($info['dirname']."/../playlist.sh '$playlist'", $json);

$json = implode($json);
$json = str_replace('{', '[', $json);
$json = str_replace('}', ']', $json);

$data = json_decode($json);

$file = fopen('auth.txt', 'r');
$authVars = array(
	'apiKey' => trim(fgets($file)),
	'secret' => trim(fgets($file)),
	'username' => trim(fgets($file)),
	'sessionKey' => trim(fgets($file)),
	'subscriber' => trim(fgets($file))
);
$config = array(
	'enabled' => true,
	'path' => 'phplastfmapi-0.7.1/lastfmapi/',
	'cache_length' => 1800
);
// Pass the array to the auth class to eturn a valid auth
$auth = new lastfmApiAuth('setsession', $authVars);

$apiClass = new lastfmApi();
$trackClass = $apiClass->getPackage($auth, 'track', $config);

$count = sizeof($data[0]);
for ($i=0; $i < $count; $i++) {
	$id     = $data[0][$i];
	$track  = $data[1][$i];
	$artist = $data[2][$i];
	$album  = $data[3][$i];

    // Setup the variables
    $methodVars = array(
    	'artist' => $artist,
    	'track' => $track
    );

    if ( $trackClass->love($methodVars) ) {
    	echo "<p><b>Done!</b> - $track - $artist</p>";
    } else {
    	echo ('<li><b>Error '.$trackClass->error['code'].' - </b><i>'.$trackClass->error['desc'].'</i></li>');
    }

}

?>
