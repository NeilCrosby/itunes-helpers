set tracks_list to {}

tell application "iTunes"
	tell current playlist
		tell tracks
			set tracks_list to get {persistent ID, name, artist, album}
		end tell
	end tell
	
	set tracks_list to tracks_list & {persistent ID of current track}
end tell

get tracks_list