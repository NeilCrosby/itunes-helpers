on run argv
	set tracks_list to {}
	
	tell application "iTunes"
		if (count of argv) > 0 then
		    set old_delim to AppleScript's text item delimiters
            set AppleScript's text item delimiters to " "
            set playlist_name to argv as text
            set AppleScript's text item delimiters to old_delim
            
		    
			set my_playlist to user playlist playlist_name
		else
			set my_playlist to current playlist
		end if
		
		tell my_playlist
			tell tracks
				set tracks_list to get {persistent ID, name, artist, album}
			end tell
		end tell
		
		set tracks_list to tracks_list & {persistent ID of current track}
	end tell
	
	get tracks_list
end run