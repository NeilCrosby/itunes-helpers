<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<?php
$playlist = isset($_GET['playlist']) ? $_GET['playlist'] : '';
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Neil's Playlist: <?= ($playlist) ? $playlist : 'Current'; ?></title>
	<style type="text/css">
		* {
			background: #fff;
			color: #000;
		}

		.current td {
			background: #fab;
			color: #000;
		}
	</style>
</head>
<body>
<table>
<?php

$json = <<<JSON
{{"453E2A19AEFD2AAC", "453E2A19AEFD8AB0", "453E2A19AEFD4F64", "453E2A19AEFD5482", "453E2A19AEFD2C01", "453E2A19AEFD4425", "8C9C16B2D2C70755", "453E2A19AEFD5245", "453E2A19AEFD78F6", "3C8ABC721C537E64", "05BFA94137C96F42", "453E2A19AEFD2AAE", "453E2A19AEFD2D38", "453E2A19AEFD34DB", "CE726B593088CCBB", "D793A2CB333B2C67", "453E2A19AEFD547E", "453E2A19AEFD4428", "8C9C16B2D2C70765", "453E2A19AEFD34E0", "453E2A19AEFD710E", "453E2A19AEFD89FE", "453E2A19AEFD2AB4", "453E2A19AEFD282F", "453E2A19AEFD34F2"}, {"Everyone Has AIDS", "Now You're a Man", "Mr.Hanky The Christmas Poo", "Sweet Transvestite", "I'm Super", "Kidnap The Sandy Claws", "We've Got to Do Something", "Me Julie", "Cuban Pete", "Rock Me Sexy Jesus", "Back To The Future", "America, Fuck Yeah", "Summer Nights (John Barrowman)", "''Battle Without Honor Or Humanity''", "Duel Of The Fates", "So They Say", "Science Fiction-Double Feature", "Oogie Boogie's Song", "Dracula's Lament", "''The Lonely Shepherd''", "The Imperial March (Darth Vader's Theme)", "Batman Theme", "Montage", "Be Prepared", "L Arena"}, {"Team America: World Police", "DVDA", "South Park", "Rocky Horror Picture Show", "Big Gay Al", "The Nightmare Before Christmas", "Infant Sorrow", "Shaggy/Ali G", "Jim Carrey", "The Ralph Sall Experience", "Alan Silvestri", "Team America: World Police", "Grease", "Tomoyasu Hotei", "John Williams", "Ensemble", "Rocky Horror Picture Show", "The Nightmare Before Christmas", "Jason Segel", "Zamfir", "John Williams", "Danny Elfman", "Team America: World Police", "Jeremy Irons With Whoopi Goldberg, Cheech Marin And Jim Cummings", "Ennio Morricone"}, {"Team America: World Police OST", "", "Mr. Hankey's Christmas Classic", "Rocky Horror Picture Show", "South Park: Bigger, Longer & Uncut", "The Nightmare Before Christmas", "Forgetting Sarah Marshall (Original Motion Picture Soundtrack)", "", "The Mask OST", "Rock Me Sexy Jesus (From \"Hamlet 2\") - Single", "Back To The Future", "Team America: World Police OST", "Showtime!", "Kill Bill (Volume One)", "YTMND Soundtrack - Volume 05", "Dr. Horrible's Sing-Along Blog (Soundtrack from the Motion Picture)", "Rocky Horror Picture Show", "The Nightmare Before Christmas", "Forgetting Sarah Marshall (Original Motion Picture Soundtrack)", "Kill Bill (Volume One)", "Star Wars- The Empire Strikes Back", "Batman OST", "Team America: World Police OST", "The Lion King (OST)", "Kill Bill (Volume Two)"}, "8C9C16B2D2C70755"}
JSON;

$playlist = isset($_GET['playlist']) ? $_GET['playlist'] : '';
//$playlist = filter_var($playlist, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW + FILTER_FLAG_STRIP_HIGH)

//exec("osascript -s s /Users/neilcrosby/Sites/playlist/playlist.scpt", $json);
exec("/Users/neilcrosby/Sites/itunes-helpers/playlist/playlist.sh '$playlist'", $json);

$json = implode($json);
$json = str_replace('{', '[', $json);
$json = str_replace('}', ']', $json);

$data = json_decode($json);
$count = sizeof($data[0]);

$current = $data[4];

for ($i=0; $i < $count; $i++) {
	
	$class = ($data[0][$i] == $current) ? ' class="current"' : '';
	
	echo <<<HTML
<tr id="track_{$data[0][$i]}"$class>
	<td>{$data[1][$i]}</td>
	<td>{$data[2][$i]}</td>
	<td>{$data[3][$i]}</td>
</tr>
HTML;
	
}

?>
</table>
</body>