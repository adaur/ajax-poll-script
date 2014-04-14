<?php
require("db.php");

if (isset($_POST['action']))
	$action = mysql_real_escape_string($_POST['action']);

if (isset($_POST['pollAnswerID']))
	$pollAnswerID = mysql_real_escape_string($_POST['pollAnswerID']); 

function getPoll($pollID){
	$query  = "SELECT * FROM polls LEFT JOIN poll_answers ON polls.pollID = poll_answers.pollID WHERE polls.pollID = " . $pollID . " ORDER By pollAnswerListing ASC";
	$result = mysql_query($query);
	
	$pollStartHtml = '';
	$pollanswersHtml = '';
	
	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$pollQuestion 		= $row['pollQuestion'];	
		$pollAnswerID 		= $row['pollAnswerID'];	
		$pollAnswerValue	= $row['pollAnswerValue'];
		
		if ($pollStartHtml == '')
		{
			$pollStartHtml 	= '<div id="pollWrap"><form name="pollForm" method="post" action="inc/functions.php?action=vote"><h3>' . $pollQuestion .'</h3><ul>';
			$pollEndHtml 	= '</ul><input type="submit" name="pollSubmit" id="pollSubmit" value="Vote" /> <span id="pollMessage"></span><img src="ajaxLoader.gif" alt="Ajax Loader" id="pollAjaxLoader" /></form><input style="display:none;" type="text" name="pollIDResult" value="'.$pollID.'"><input type="submit" name="showResults" id="showResults" value="Résultats" /></div>';	
		}
		$pollanswersHtml	= $pollanswersHtml . '<li><input name="pollAnswerID" id="pollRadioButton' . $pollAnswerID . '" type="radio" value="' . $pollAnswerID . '" /> ' . $pollAnswerValue .'<span id="pollAnswer' . $pollAnswerID . '"></span></li>';
		$pollanswersHtml	= $pollanswersHtml . '<li class="pollChart pollChart' . $pollAnswerID . '"></li>';
	}
	echo $pollStartHtml . $pollanswersHtml . $pollEndHtml;
}

function getPollID($pollAnswerID){
	$query  = "SELECT pollID FROM poll_answers WHERE pollAnswerID = ".$pollAnswerID." LIMIT 1";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	
	return $row['pollID'];	
}

function getPollResults($pollID){
	$pollResults = '';
	$colorArray = array(1 => "#ffcc00", "#00ff00", "#cc0000", "#0066cc", "#ff0099", "#ffcc00", "#00ff00", "#cc0000", "#0066cc", "#ff0099");
	$colorCounter = 1;
	$query  = "SELECT pollAnswerID, pollAnswerPoints FROM poll_answers WHERE pollID = ".$pollID."";
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result))
	{
		if ($pollResults == '')
			$pollResults = $row['pollAnswerID'] . "|" . $row['pollAnswerPoints'] . "|" . $colorArray[$colorCounter];
		else
			$pollResults = $pollResults . "-" . $row['pollAnswerID'] . "|" . $row['pollAnswerPoints'] . "|" . $colorArray[$colorCounter];
			
		$colorCounter = $colorCounter + 1;
	}
	$query  = "SELECT SUM(pollAnswerPoints) FROM poll_answers WHERE pollID = ".$pollID."";
	$result = mysql_query($query);
	$row = mysql_fetch_array( $result );
	$pollResults = $pollResults . "-" . $row['SUM(pollAnswerPoints)'];
	echo $pollResults;	
}

if (isset($action))
{
	if ($action == "vote")
	{
		$poll_id = getPollID($pollAnswerID);
		
		$check_ip  = mysql_query("SELECT 1 FROM poll_ip WHERE ip = '".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."' AND poll_id = ".$poll_id);
		
		if ((isset($_COOKIE["poll" . $poll_id])) || (mysql_num_rows($check_ip) != 0))
			echo "voted";
		else
		{
			$query  = "UPDATE poll_answers SET pollAnswerPoints = pollAnswerPoints + 1 WHERE pollAnswerID = ".$pollAnswerID."";
			mysql_query($query) or die('Error, insert query failed');
			
			$add_ip = "INSERT INTO poll_ip (ip, poll_id) VALUES ('".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."', ".$poll_id.")";
			mysql_query($add_ip) or die('Error, insert query failed');
			
			setcookie("poll" . $poll_id, 1, time()+31556926, "/", html_special_chars($_SERVER['REMOTE_ADDR']));
			getPollResults($poll_id);
		}
	}
	
	if ($action == "getResults")
		getPollResults($pollAnswerID);
}
