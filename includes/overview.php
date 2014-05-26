<?php

	session_start(); //this must be on each page the $_SESSION will be set or accessed.

	//header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	//header("Cache-Control: post-check=0, pre-check=0", false);
	//header("Pragma: no-cache");
	//ini_set('display_errors', 1);
	//error_reporting(E_ALL & ~E_NOTICE);
	////error_reporting(E_ERROR);
	//
	//
	if (!strpos(getcwd(),'includes')) {
	   include dirname(__FILE__) . '/includes/settings.php';
	   include dirname(__FILE__) . '/includes/header.php';
	}else{
	   include dirname(__FILE__) . '/settings.php';
	   include dirname(__FILE__) . '/header.php';
	}
	$output = '<div class="install">settings and header included!<br><br>';
	
	$output .=  '$_SESSION["user_id"] is: '. $_SESSION["user_id"] .'
	<-- <br>and $_SESSION["username"] is: '. $_SESSION["username"] .'<-- <br><br>';
	
	if (isset($prefix)) {
		$qryString = "SELECT ji.interviewID, ji.interviewDate, ji.interviewTime,
						jc.companyName, jcon.contactName, jcon.contactPhone
					FROM ".$prefix."jobInterviews as ji
					LEFT JOIN jobCompanies as jc
					ON jc.userID = ji.userID
					LEFT JOIN jobContact as jcon
					ON jc.companyID = jcon.companyID
					WHERE ji.userID='?';";
	}else{
		$qryString = "SELECT ji.interviewID, ji.interviewDate, ji.interviewTime,
						jc.companyName, jcon.contactName, jcon.contactPhone
					FROM jobInterviews as ji
					LEFT JOIN jobCompanies as jc
					ON jc.userID = ji.userID
					LEFT JOIN jobContact as jcon
					ON jc.companyID = jcon.companyID
					WHERE ji.userID='?';";
	}
	
	$output .= '$qryString is: '. $qryString .'<--<br><br>';
	
	$dbconn = new mysqli($host, $serveruser, $password, $defaultdb);
	////$dbconn = new mysqli('sql210.byethost22.com', 'b22_14818348', 'L0rd0fall1', 'b22_14818348_jobtrack');
	
	if ($dbconn->connect_errno) {
		die("Problem connecting to the database, The error returned is: " .
			$dbconn->connect_errno . ": " . $dbconn->connect_error);		
	}else $output .= "<h4>DB Connection established....</h4>";
	
	if ($stmt = $dbconn->prepare($qryString)) {
		
		$stmt->bind_param("i", $_SESSION["user_id"]);
		$output .= '<h4>stmt->prepare() successful!</h4>';
		
		//if ($result = $dbconn->query($usrQryStmt)) {
		if ($stmt->execute()) {
			
			if ($stmt->bind_result($intvID, $intvDate, $intvTime, $companyName, $contactName, $contactPh)) {
				$output .= '<h4>stmt->bind_result() successful!</h4>';
			}else $output .= '<h4>stmt->bind_result() failed!</h4>';
			
			$data = array(); //This will hold the data we will send back to the
							 //JSON request
			$result = $stmt->get_result();
			while ($row = $result->fetch_array(MYSQLI_NUM))
			{
				foreach ($row as $r)
				{
					$output .= "<br>$r <br>";
		//			$row_data = array(
		//				'intvID' => $r['intvID'], 
		//				'intvDate' => $r['intvDate'],
		//				'intvTime' => $r['intvTime'],
		//				'companyName' => $r['companyName'],
		//				'contactName' => $r['contactName'],
		//				'contactPh' => $r['contactPh'],
		//			);
		//			array_push($data, $row_data);
				}					
		//		echo json_encode($data);
			}
			$output .= '<h4>stmt->execute() successful!</h4>';
		}else{
			$output .= '<h4>stmt->execute()  failed! With the error: </h4>' .
				$dbconn->connect_errno . ': ' . $dbconn->connect_error;
		}
	}else $output .= '<h4>stmt->prepare()  failed! With the error: </h4>' .
				$dbconn->connect_errno . ': ' . $dbconn->connect_error;
	
	echo $output . '</div>';

