<?php
require_once('phplastfmapi-0.7.1/lastfmapi/lastfmapi.php');

set_time_limit(0);

$maxTags = 5;
$playlist = isset($_GET['playlist']) ? $_GET['playlist'] : '';

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

    $tags = $trackClass->getTopTags($methodVars);
    if ( $tags ) {
        $ourTags = array();
        
        foreach ($tags['tags'] as $tag) {
            if ($tag['count'] > 0) {
                $ourTags[] = $tag['name'];
                if (sizeof($ourTags) >= $maxTags) {
                    break;
                }
            }
        }
        
        array_walk($ourTags, 'normaliseTag');
        
    	echo "<p><b>Done!</b> - $id - $track - $artist</p>";
    	
        $commentData = '';
        exec($info['dirname']."/localtags.sh get $id", $commentData);

        $previousInfo = '';
        foreach ($commentData as $commentItem) {
            $previousInfo .= trim($commentItem, '"')."\n";
        }
        $previousInfo = trim($previousInfo);
        
        echo "<p>Previous: $previousInfo</p>";
        
        if ( preg_match('/^(.*?)\[lfm:[^]]*](.*)$/', $previousInfo, $matches) ) {
            $previousInfo = $matches[1].' '.$matches[2];
            echo "<p>Filtered: $previousInfo</p>";
        }
        
        $newInfo = trim($previousInfo). ' [lfm: '.implode(' ', $ourTags).' ]';
        echo "<p>New: $newInfo</p>";
        exec($info['dirname']."/localtags.sh set $id $newInfo", $commentData);
        
    } else {
    	echo ("<li><b>Error $track - $artist: ".$trackClass->error['code'].' - </b><i>'.$trackClass->error['desc'].'</i></li>');
    }

}

function normaliseTag(&$tag, $key) {
    $tag = strtolower($tag);
    $tag = str_replace(' ', '-', $tag);
    $tag = str_replace('[', '-', $tag);
    $tag = str_replace(']', '-', $tag);
}

?>
