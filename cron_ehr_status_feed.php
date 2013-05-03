<?php
	
	DEFINE('DB_USER', '3ngineer');
	DEFINE('DB_PASSWORD', 'gd6xCF6rHdbu');
	DEFINE('DB_HOST', '192.168.1.87');
	DEFINE('DB_NAME', 'develop_webservices');

	$rssfeed = '<?xml version="1.0" encoding="UTF-8"?>';
	$rssfeed .= '<rss version="2.0">';
	$rssfeed .= "<channel>";
	$rssfeed .= "<title>Practice Fusion System Status</title>";
	$rssfeed .= "<link>status.practicefusion.com/status/feed/></link>";
	$rssfeed .= "<description>This feed shows the system status of Practice Fusion</description>";
	$rssfeed .= "<language>en-us</language>";

	$db_connection = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Could not connect to the database");

	@mysql_select_db(DB_NAME) or die("Could not connect the database");

	$query = "SELECT message, datetime FROM status_feed ORDER BY id desc LIMIT 10";

	$result = mysql_query($query) or die("Could not get data from the table");

	while($row = mysql_fetch_array($result)) {
		extract($row);

		$rssfeed .= '<item>';
		$rssfeed .= "<title>System Update</title>";
		$rssfeed .= '<description>'. unicode_escape_sequences($message) .'</description>';
		$rssfeed .= '<pubDate>'. date("D, d M Y H:i:s", strtotime($datetime)) .' PST</pubDate>';
		$rssfeed .= "</item>";
	}

	function unicode_escape_sequences($str){
		$working = json_encode($str);
		$working = preg_replace('/\\\u([0-9a-z]{4})/', '&#x$1;', $working);
		return json_decode($working);
	}

	$rssfeed .= '</channel>';
	$rssfeed .= '</rss>';

	$filename = "feed/index.xml";
	file_put_contents($filename, $rssfeed);
?>