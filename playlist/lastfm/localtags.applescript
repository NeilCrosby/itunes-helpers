on run argv
	set tracks_list to {}
	
	tell application "iTunes"
		if (count of argv) > 1 then
			set command_type to item 1 of argv
			set argv to rest of argv
			
			set track_id to item 1 of argv
			set argv to rest of argv
			
			set old_delim to AppleScript's text item delimiters
			set AppleScript's text item delimiters to " "
			set tag_info to argv as text
			set AppleScript's text item delimiters to old_delim
		else
			return
		end if
		
		-- now that we've done the setup to get track_id and tag_info we need
		-- to see if this track already has any tag info
		
		set track_reference to first track of playlist 1 whose persistent ID is track_id
		
		-- if we're doing a get command then return the track's comment
		if (command_type is equal to "get") then
			return comment of track_reference
		end if
		
		-- if we get here then we're doing a set - lets set the data
		set comment of track_reference to tag_info
	end tell
	
end run