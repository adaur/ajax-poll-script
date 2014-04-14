<?php 
require("inc/db.php");
require("inc/functions.php"); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ajax Poll Script - Demo</title>
<script src="http://code.jquery.com/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="inc/poll.js" type="text/javascript"></script>
<style>
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 1em;
}
#pollWrap{
	width: 250px;
}
#pollWrap h3 {
	font-size: 1em;
	margin-bottom: 5px;
}
#pollWrap ul {
	margin: 0;
	padding: 0 0 0 5px;
	list-style:none;
}
#pollWrap li {
	padding: 0;
	overflow:hidden; /*for our lovely friend IE6 to behave nicely*/
	font-size: 0.8em;
}
#pollWrap li span {
	font-size: 0.7em;
}
.pollChart {
	margin-left: 25px;
	height: 5px;
	width:1px;
	/*Adding rounded corners to the graphs - Optional - START*/
	-moz-border-radius-topright: 4px;
	-moz-border-radius-bottomright: 4px;
	-webkit-border-top-right-radius: 4px;
	-webkit-border-bottom-right-radius: 4px;
	/*Adding rounded corners to the graphs - Optional - END*/
}
#pollSubmit {
	margin-top: 5px;
}
#pollMessage {
	color:#C00;
	font-size: 0.8em;
	font-weight: bold;
}
</style>
</head>

<body>
	<?php getPoll(1);?>
	<?php getPoll(2);?>
</body>
</html>