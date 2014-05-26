<?php

  /*****************************************************************************
   *
   *                        Settings to initially set the global vars 
   *
   ****************************************************************************/

/*************
 *
 * First we setup our flow control, makes it much easier to use this file.
 *
 *************/
    //echo '<br><br><h4>Am inside the functions file $_GET["action"] is set to: '.$_GET['action'].'.</h4>';
    error_reporting(E_ALL & ~E_NOTICE);

if (isset($_GET['action']) && !empty($_GET['action'])) {
	$action = $_GET['action'];
	
	switch ($action) {
		case 'init':
			flexiInit();
		case 'login':
			flexiLogin();
		case 'logout':
			flexiLogout();
		case 'saveSettings':
			flexiSettingsSave();
		case 'quote':
			flexiQuoteReq();
		default:
			flexiInit();
	}
}elseif (isset($_POST['action']) && !empty($_POST['action'])) {
	$action = $_POST['action'];
	
	switch ($action) {
		case 'init':
			flexiInit();
		case 'login':
			flexiLogin();
		case 'logout':
			flexiLogout();
		case 'quote':
			flexiQuoteReq();
		case 'saveSettings':
			flexiSettingsSave();
	}
}else{
	// Most likely we are just at the index.php and are just including the file.
	flexiInit();
	//echo '<br><h3>No action set...cannot continue. Please contact your Systems Administrator!</h3>';
	//exit;
}

function flexiInit() {
    session_start(); //this must be on each page the $_SESSION will be set or accessed.

    //echo '<h4>Am in jc_initialize inside the functions file</h4>';

	/**
	* @file
	* Functions that need to be loaded on every It's Just Code request.
	*
	* Some of the functions were borrowed from various Drupal files (http://drupal.org)
	* (just giving credit where it's due)
	*/


	/**
	* The current system version.
	*/
	define('VERSION', '1.01');

	/**
	* The base directory that this application is served from
	*
	* Will be set to: /<base dir>/ <-- note the ending slash
	*
	*/
	$pos = strripos($_SERVER["PHP_SELF"], '/')+1;
	define ('BASE_DIR', substr($_SERVER["PHP_SELF"], 0, $pos));
	$GLOBALS["BASE_DIR"] = BASE_DIR;
        
	/**
	* The base actual file path on the server's harddrive.
	*
	* Will be set to: /home/<user>/www/<base dir> <-- note NO ending slash
	*
	*/
	define ('BASE_PATH', dirname($_SERVER["PHP_SELF"]));
	$GLOBALS["BASE_PATH"] = BASE_PATH;

	/**
	* The actual includes file path on the server's harddrive.
	*
	* Will be set to: /var/www/<base dir>/includes/ <-- note ending slash
	*
	*/
	define ('INCLUDES_PATH', BASE_PATH . "/includes/");
	$GLOBALS["INCLUDES_PATH"] = INCLUDES_PATH;

	/**
	* The the entire url up to the base directory that this application is served from
	*
	* Will be set to: http://localhost/<base dir>/ <-- note the ending slash
        * or http://localhost/ if it is the base
	*
	*/
	$pos = strripos($_SERVER["PHP_SELF"], '/')+1;
	define ('BASE_URL', "http://" . $_SERVER["SERVER_NAME"] . substr($_SERVER["PHP_SELF"], 0, $pos));

	$GLOBALS["BASE_URL"] = BASE_URL;

	/**
	* This is the entire url up to the includes directory
	*
	* Will be set to http://<host>/<base dir>/includes/ <-- note the ending slash
	*
	**/
    
	define ('INCLUDES_PATH_URL', BASE_URL . "includes/");
	$GLOBALS["INCLUDES_PATH_URL"] = INCLUDES_PATH_URL;
	
	//We will test for DB after completing this process.
    
    //If the file does not exist then the user likely hasn't manually created one
    //or this is the first time this site has been run.
    $settingsFile = dirname(__FILE__)."/settings.php";  
    //echo '<h4>The $settingsFile is set to: ' . $settingsFile . '</h4>';
  
    //echo "<h3>[in db.php->jc_db_initialize()] \$settingsFile is set to: [$settingsFile]...</h3>";
    $installFile = dirname(__FILE__)."/install.php"; 
    
    if (!file_exists($settingsFile)) {
        /* 
         * Looks like the user hasn't "created" or used the default_settings.php to give us the database settings
         * that are SUPPOSED to be in the settings.php file sooo we will run the install, since there are likely no databases either.
         * header("Location: " . BASE_URL . "includes/install.php/start");
         * 
         */
    
        //echo '<h4>...fell into the "settings.php file DOES NOT exist" path...should be redirecting to the install...</h4>';
        //settingsFile shows as: $settingsFile and exist shows as: " . file_exists($settingsFile) . "<br /><br />";
        //header('Location: install.php');
        
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname(__FILE__), '/\\');
        $uri .= '/install.php';
        //echo '<h4>cwd is: ' . getcwd() . ' and install file path is set to: '.$installFile .' and the $uri is: ' . $uri . '</h4>';
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        //header("Location: http://$host$uri");
        header('Location: ./install.php');
        echo '<br><h4 class="install"><pre>Looks like we neet to setup/install your site, no worries,
		just click on the install button and we\'ll get started.</pre><h4><br>
		<p><a class="btn btn_default install_btn" role="button"
		href="./includes/install.php">Install -></a></p>';
        exit;
    }else{
        require 'settings.php';
        
        if (!$defaultdb) { // The user might just have screwed up the settings
						   // file so we will need to redo it.
         header('Location: install.php');
         exit;
        }
    }

    /**
     * Now we will create the global variable that will contain whether the user
     * is logged in or not.
     **/
//	define ('LOGGEDIN', false);
//    $GLOBALS["logged_in"] = LOGGEDIN;

    $GLOBALS["vars_set"] = "COMPLETE";

  /************************* End environment defines *************************/
  //echo "<h3>[in functions.inc] About to TRY to return back to index...</h3>";
  return $GLOBALS["vars_set"];
}

function flex_redirect($url, $statusCode = 303) {
   header('Location: ' . $url, true, $statusCode);
   die();
}
function esc_url($url) {
 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

function flexiLogin () {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    ini_set('display_errors', 1);
    //error_reporting(E_ALL & ~E_NOTICE);
    error_reporting(E_ERROR);

    session_start(); //this must be on each page the $_SESSION will be set or accessed.
    
    if (!strpos(getcwd(),'includes')) {
	    include dirname(__FILE__) . '/includes/settings.php';
	    include dirname(__FILE__) . '/includes/header.php';
	}else{
	    include dirname(__FILE__) . '/settings.php';
	    include dirname(__FILE__) . '/header.php';
	}
        
    //echo '<br><br><h4>The raw $_POST is: </h4>';
    //print_r($_POST);
    
    if (isset($_POST['username']) && !empty($_POST['username']) &&
            isset($_POST['password']) && !empty($_POST['password']) &&
			!strpos($_POST['username'],'$' /* means we're getting passed the ACTUAL variable name not the value */)) {
        //echo "<h4>I made it into the submit === Login statement...</h4>";
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        //echo "<h4>Username and password posts are set...continuing...</h4>";
        
        //echo '<h4>settings files included...(working now...) continuing...</h4>';
        //echo '<h4>The $host is: ' . $host .' and $serveruser is: ' . $serveruser .
        //' and $password is: ' . $password . ' and lastly the $defaultdb is: ' . $defaultdb . '</h4>';
            
        //$pdo = new PDO('mysql:host=example.com;dbname=database', 'user', 'password');
        $dbconn = new mysqli($host, $serveruser, $password, $defaultdb);
        //$dbconn = new mysqli('sql210.byethost22.com', 'b22_14818348', 'L0rd0fall1', 'b22_14818348_jobtrack');

        if ($dbconn->connect_errno) {
            die("Problem connecting to the database, The error returned is: " .
                $dbconn->connect_errno . ": " . $dbconn->connect_error);
        //if ($dbconn->connect_error) {
        //    die("Problem connecting to the database, The error returned is: " .
        //        mysql_error());
            
        }//else echo "<h4>DB Connection established....</h4>";

        if (isset($prefix)) {
            $usrQryStmt = "SELECT userID, username, firstName, lastName,
                               socLastFour, email, photoPath, resumePath,
                               permission
                            FROM `".$prefix."jobUsers`
                            WHERE username = '".$username."';";
        }else{
            $usrQryStmt = "SELECT userID, username, firstName, lastName,
                               socLastFour, email, photoPath, resumePath,
                               permission
                            FROM `jobUsers`
                            WHERE username = '".$username."';";
        }
        
        //echo '<br><br><h4>the Query statement is: ' . $usrQryStmt . ' <--</h4><br>';
        
        //mysqli_report(MYSQLI_REPORT_ALL);

        if ($stmt = $dbconn->prepare($usrQryStmt)) {
            
            //$stmt->bind_param("s", $username);
            
            //if ($result = $dbconn->query($usrQryStmt)) {
            if ($stmt->execute()) {
                
                $stmt->bind_result($userId, $userName, $userFirst, $userLast,
                                   $userLast4, $userEmail, $userPicPath,
                                   $userResPath, $perms);
                
                
                //echo "<h4>Get result successful....</h4>";
    
                //while ($myrow = $result->fetch_array(MYSQLI_NUM)) {//SHOULD only be one record.
                while($stmt->fetch()){
                    session_start();
                    $_SESSION["user_id"] = $userId;
                    $_SESSION["username"] = $userName;
                    $_SESSION["fname"] = $userFirst;
                    $_SESSION["lname"] = $userLast;
                    $_SESSION["soc4"] = $userLast4;
                    $_SESSION["email"] = $userEmail;
                    $_SESSION["pic_path"] = $userPicPath;
                    $_SESSION["res_path"] = $userResPath;
                    $_SESSION["perms"] = $perms;
                    $_SESSION["logged_in"] = true;
                    //echo '<h4>Below is the raw data from $myrow: </h4>';
                    //print_r($myrow);
                    header('Location: ../index.php');
                    echo '<div class="install"><h4>Login successful!</h4><br><p><a href="../index.php">
					<button type="button" class="btn btn-primary btn-status login-button">
                    <i class="icon-login"></i> Continue -></button></a></p></div>';
                }
            }else echo '<div class="install"><h4>Mysqli_query failed! The error is: </h4></div>' .
				$dbconn->connect_errno . ': ' . $dbconn->connect_error;
        }else echo '<h4>Statment prepare failed! The error is: </h4>' .
			$dbconn->connect_errno . ': ' . $dbconn->connect_error;
			exit;
    }else{
            echo "<h4>Both Username and Password need to be entered.</h4><br>";
			exit;
    }//else echo "<h4>Either Username and password were not entered...</h4>";
    if (!strpos(getcwd(),'includes')) {
	    include dirname(__FILE__) . '/includes/footer.php';
	}else{
	    include dirname(__FILE__) . '/footer.php';
	}
}

function flexiLogout () {
	session_start();
	$_SESSION["user_id"] = '';
	$_SESSION["username"] = '';
	$_SESSION["fname"] = '';
	$_SESSION["lname"] = '';
	$_SESSION["soc4"] = '';
	$_SESSION["email"] = '';
	$_SESSION["pic_path"] = '';
	$_SESSION["res_path"] = '';
	$_SESSION["perms"] = '';
	$_SESSION["logged_in"] = false;
	header('Location: ../index.php');
	echo '<h4>You have been logged out successfully!</h4><br><p><a class="btn btn-primary btn-lg" role="button" href="../index.php"><- Back</a></p>';
	
}








function flexiQuery ($type, $qryString, $uid) {
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	ini_set('display_errors', 1);
	//error_reporting(E_ALL & ~E_NOTICE);
	error_reporting(E_ERROR);
	
	session_start(); //this must be on each page the $_SESSION will be set or accessed.
	
	if (!strpos(getcwd(),'includes')) {
	   include dirname(__FILE__) . '/includes/settings.php';
	  // include dirname(__FILE__) . '/includes/header.php';
	}else{
	   include dirname(__FILE__) . '/settings.php';
	  // include dirname(__FILE__) . '/header.php';
	}
    
	if (isset($type) && !empty($type) && isset($qryString) && !empty($qryString)
		&& isset($type) && !empty($type)) {
		return;
	}
	
}

function flexiSettingsSave() {
	
}

function flexiQuoteReq() {
	$errors = '';
	$myemail = 'mgagne43@gmail.com';//<-----Put Your email address here.

	if(empty($_POST['name'])  || empty($_POST['email']) || empty($_POST['message']))
	{
		$errors .= "\n Error: all fields are required";
	}
	 
	$name = $_POST['name']; 
	$email_address = $_POST['email']; 
	$message = $_POST['message']; 
	 
	if (!preg_match(
	"/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", 
	$email_address)) {
		$errors .= "\n Error: Invalid email address";
	}

	if( empty($errors))	{	 
		$to = $myemail;	 
		$email_subject = "Quote Request form submission from: $name";	 
		$email_body = "You have received a new Quote Request. ".
		" Sent by: $name \n ".
		"Email: $email_address\n Message \n $message";
		$headers = "From: $myemail\n";
		$headers .= "Reply-To: $email_address";
		mail($to,$email_subject,$email_body,$headers);
		//redirect to the 'thank you' page
		return true;
	}
}



