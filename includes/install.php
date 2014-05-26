<?php
 
    /**
     * Root directory of @Flexi-Code installation.
     */
    define('FLEXI_ROOT', getcwd() . "/"); // This sets a global path variable to use...which
                                      //translates to: something like
                                      // /var/www/<base dir>/, which is good enough
                                      //for us.
                                      
    //echo '<h4 class="install">In the install.php just before including the functions.php</h4>';
    //include_once "functions.php";
	include('./header.php');
  
    session_start(); //this must be on each page the $_SESSION will be set or accessed.
	
    /**
     *
     * @file
     * Initiates a browser-based installation of @Flexi-Code's DBs.
     * 
     */
    
    // Started the installer. But first check if we are coming back in here from a
    // html post
    //echo '<h4 class="install">In the install.php just before checking the step</h4>';
    $step = (isset($_GET['step']) && $_GET['step'] != '') ? $_GET['step'] : '';
    switch($step){
      case '1':
        step_1();
        break;
      case '2':
        //echo 'heading into step 2';
        step_2();//Server info entry form
        break;
      case '3':
        //echo 'heading into step 3';
        step_3();//Create Settings File and the DB/Tables and Insert the Admin user.
        break;
      case '4':
        //echo 'heading into step 4';
        step_4();//Installation Complete display
        break;
      default:
        //echo 'heading into step 1';
        step_1();//Check Server Information/Settings
    }
    
    
    function step_1 ()
    {
      $myDir = dirname(__FILE__);
      header("Cache-Control: no-cache");
      header("Pragma: no-cache");

      if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['pre_error'] ==''){
        header('Location: install.php?step=4');
        exit;
      }

      if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['pre_error'] != '')
        echo $_POST['pre_error'];
          
      if (phpversion() < '5.0') {
        $pre_error = 'Your PHP installation is too old. This site requires at least
      PHP 5.2.4. Please see the <a href="http://justcode.twomini.com/requirements">system
      requirements</a> page for more information.<br />';
      }

      if (ini_get('session.auto_start')) {
        $pre_error .= 'This site will not work with session.auto_start enabled!<br>';
      }

      if (!extension_loaded('mysqli')) {
        $pre_error .= 'MySQLi extension needs to be loaded for this site to work!<br>';
      }

      if (!extension_loaded('gd')) {
        $pre_error .= 'GD extension needs to be loaded for this site to work!<br>';
      }

      if (!is_writable($myDir)) {
        $pre_error .= 'settings.php needs to be writable for this site to be installed!';
      }
    ?>
    <div class="install">
      <br><br>
      <h3 class="install-h3">Installation Step 1</h3><br>
      <p class="install-p">Checking the server information:</p><br>
      <p class="install-p">We neet to check the server information to be sure there will not be anything to prevent using it.</p>
      <table class="serverInfo">
      <tr>
        <th>Required Services</td>
        <th>Options Required:</td>
        <th>Current Settings:</td>
        <th>Pass/Fail</td>
      </tr>
	  <tr>
        <td>PHP Version:</td>
        <td>5.4+</td>
        <td><?php echo phpversion(); ?></td>
        <td><?php echo (phpversion() >= '5.2') ? 'Ok' : 'Not Ok'; ?></td>
      </tr>
      <tr>
        <td>Session Auto Start:</td>
        <td>Off</td>
        <td><?php echo (ini_get('session_auto_start')) ? 'On' : 'Off'; ?></td>
        <td><?php echo (!ini_get('session_auto_start')) ? 'Ok' : 'Not Ok'; ?></td>
      </tr>
      <tr>
        <td>MySQLi:</td>
        <td>On</td>
       <td><?php echo extension_loaded('mysqli') ? 'On' : 'Off'; ?></td>
        <td><?php echo extension_loaded('mysqli') ? 'Ok' : 'Not Ok'; ?></td>
      </tr>
      <tr>
        <td>GD:</td>
        <td>On</td>
        <td><?php echo extension_loaded('gd') ? 'On' : 'Off'; ?></td>
        <td><?php echo extension_loaded('gd') ? 'Ok' : 'Not Ok'; ?></td>
      </tr>
      <tr>
        <td>Writable directory</td>
        <td>Writable</td>
        <td><?php echo is_writable($myDir) ? 'Writable' : 'Unwritable'; ?></td>
        <td><?php echo is_writable($myDir) ? 'Ok' : 'Not Ok'; ?></td>
      </tr>
      </table>
      <h4 class="install-notok">Any errors (Not Ok) must be taken care of before continuing.</h4><br>
      <form action="install.php?step=2" method="post">
        <input type="hidden" name="pre_error" id="pre_error" value="<?php echo $pre_error;  ?>"/>
    <?php
        if (is_writable($myDir)) {
          echo '<input type="submit" class="btn btn_default" name="continue" value="Continue" />';
        //print_r(posix_getgrgid(filegroup($myDir)));
        //echo '<br>Current script owner: ' . get_current_user() . '<br>';
        //echo exec('whoami');
        
        }
    ?>
      </form>
    </div>
<?php

    }//end of stap 1
     
    function step_2(){
    ?>
    <div class="install">
      <h3 class="install-h3">Installation Step 2</h3>
      <p class="install-p">
        <ol>
          <li>Creating the settings file</li>
          <li>Install the database</li>
          <li>Create the admin user</li>
        </ol>
      </p>
      
      <div class='settingsform'>
        <form class="install-form" method="post" action="install.php?step=3">
          <fieldset class="install">
            <legend>A "<span class="required">*</span>" means it is required.</legend>
            <label class="install_label" for="dbhost">Database Host: <span class="required">*</span> </label>
            <input type="text" class="install_input" name="dbhost" value='localhost' size="30"><br><br>
            <label class="install_label" for="dbname">Database Name: <span class="required">*</span> </label>
            <input type="text" class="install_input" name="dbname" size="30" value="<?php echo $database_name; ?>"><br><br>
            <label class="install_label" for="dbuser">Database Username: <span class="required">*</span> </label>
            <input type="text" class="install_input" name="dbuser" size="30" value="<?php echo $database_username; ?>"><br><br>
            <label class="install_label" for="dbpass">Database Password: </label>
            <input type="password" class="install_input" name="dbpass" size="30" value="<?php echo $database_password; ?>"><br><br>
            <label class="install_label" for="dbprefix">Table Prefix: </label>
            <input type="text" class="install_input" name="dbprefix" size="30" value="<?php echo $database_prefix; ?>"><br><br>
          </fieldset>
           <fieldset class="install-username">
            <label class="install_label" for="user">Admin User: <span class="required">*</span> </label>
            <input type="text" class="install_user_input" name="user" size="30" value="<?php echo $admin_name; ?>"><br><br>
            <label class="install_label" for="pass">Admin Password: <span class="required">*</span> </label>
            <input name="pass" class="install_user_input" type="password" size="30" maxlength="15" value="<?php echo $admin_password; ?>">
          </fieldset>
          <input type="submit" class="btn btn_default btn_install" name="submit" value="Install!"><br>
        </form>
      </div>
    </div>
  <?php
  }//end of step 2
  
  function step_3 ()
  {

    header("Cache-Control: no-cache");
    header("Pragma: no-cache");

    //error_reporting(E_ALL & ~E_NOTICE);

    //echo '<br><h3 class="install-h3">In INTERNAL step_3 (non-visible) settings.php and db creation. Raw $_POST data is: </h3><br>';
    //print_r($_POST);
    //echo '<br>';
    
    if (isset($_POST['submit']) && $_POST['submit']=="Install!")
    {
      //echo '<br><h3 class="install-h3">Made it past the $_POST test and it DID equal Install!</h3><br>';
      $database_host=isset($_POST['dbhost'])?$_POST['dbhost']:"";
      $database_name=isset($_POST['dbname'])?$_POST['dbname']:"";
      $database_username=isset($_POST['dbuser'])?$_POST['dbuser']:"";
      $database_password=isset($_POST['dbpass'])?$_POST['dbpass']:"";
      $database_prefix=isset($_POST['dbprefix'])?$_POST['dbprefix']:"";
      $admin_name=isset($_POST['user'])?$_POST['user']:"";
      $admin_password=isset($_POST['pass'])?$_POST['pass']:"";
      
      //echo '<h4 class="install-h3">Checking for required fields in 20 seconds...</h4><br />';
      //sleep(20);
      
      if (empty($admin_name) || empty($admin_password) || empty($database_host) || empty($database_username) || empty($database_name))
      {
        echo '<h3 class="install-h3">All fields are required! Please re-enter.</h3><br />';
      } else {
      
        //echo '<h4>All fields were completed and came through $_POST.</h4><br />';
        
        if (!$dbconn = new mysqli($database_host, $database_username, $database_password))
        {
          echo '<h4>DB Connection failed!! With error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error .' </h4>';
          
        }else{

          if ($dbconn->connect_errno) {
            printf("Database connection failed: %s\n", $dbconn->connect_errno . " Description: " . $dbconn->error);
          }else{
  
            //echo '<h3 class="install-h3">DB Connection successfull! About to attempt creating the DB if it doesn\'t exist...</h3><br />';
            //Create the database first...
            
            $dbconn->query("CREATE DATABASE IF NOT EXISTS " . $database_name ."") or die($dbconn->errno);    
            $dbconn->close();
            
            //echo '<h4 class="install-h3">Creating the create tables query in 20 seconds...</h4><br />';
            //sleep(20);
            if (isset($database_prefix) && !empty($database_prefix)) {        
              $createTable1 = "CREATE TABLE IF NOT EXISTS `".$database_prefix."jobActivity` (
                `activityID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `companyID` bigint(20) NOT NULL,
                `userID` bigint(20) NOT NULL,
                `contactID` bigint(20) NOT NULL,
                `contactDate` date NOT NULL,
                `contactTime` time NOT NULL,
                `activitySpokeTo` varchar(255) COLLATE utf8_bin NOT NULL,
                `activityLeftMsg` tinyint(1) NOT NULL,
                `followUpDate` date NOT NULL,
                `followUpTime` time NOT NULL,
                `appOnline` tinyint(1) DEFAULT NULL,
                `appOnsite` tinyint(1) DEFAULT NULL,
                `resOnsite` tinyint(1) DEFAULT NULL,
                `resFaxed` tinyint(1) DEFAULT NULL,
                `resEmailed` tinyint(1) DEFAULT NULL,
                `resEmailedTo` varchar(255) COLLATE utf8_bin DEFAULT NULL,
                `positionOpen` varchar(255) COLLATE utf8_bin DEFAULT NULL,
                `activityComments` blob NOT NULL,
                PRIMARY KEY (`activityID`),
                UNIQUE KEY `activityID` (`activityID`),
                KEY `companyID` (`companyID`,`contactDate`,`followUpDate`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;";
              
              $createTable2 = "CREATE TABLE IF NOT EXISTS `".$database_prefix."jobCallLog` (
                `contactID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `userID` bigint(20) NOT NULL,
                `companyID` bigint(20) NOT NULL,
                `contactName` varchar(255) COLLATE utf8_bin NOT NULL,
                `contactTitle` varchar(50) COLLATE utf8_bin NOT NULL,
                `contactPhone` varchar(12) COLLATE utf8_bin DEFAULT NULL,
                `contactEmail` varchar(255) COLLATE utf8_bin NOT NULL,
                `contactComments` blob NOT NULL,
                PRIMARY KEY (`contactID`),
                UNIQUE KEY `contactID` (`contactID`),
                KEY `companyID` (`companyID`),
                KEY `contactName` (`contactName`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;";
              
              $createTable3 = " CREATE TABLE IF NOT EXISTS `".$database_prefix."jobCompanies` (
                `companyID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `userID` bigint(20) NOT NULL,
                `companyName` varchar(255) COLLATE utf8_bin NOT NULL,
                `companyStr` varchar(50) COLLATE utf8_bin DEFAULT NULL,
                `companySte` varchar(15) COLLATE utf8_bin DEFAULT NULL,
                `companyCty` varchar(25) COLLATE utf8_bin DEFAULT NULL,
                `companyState` varchar(2) COLLATE utf8_bin DEFAULT NULL,
                `companyZip5` varchar(5) COLLATE utf8_bin DEFAULT NULL,
                `companyPh` varchar(25) COLLATE utf8_bin DEFAULT NULL,
                `companyUrl` varchar(25) COLLATE utf8_bin DEFAULT NULL,
                PRIMARY KEY (`companyID`),
                KEY `userID` (`userID`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;";
              
              $createTable4 = "CREATE TABLE IF NOT EXISTS `".$database_prefix."jobContact` (
                `contactID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `userID` bigint(20) NOT NULL,
                `companyID` bigint(20) NOT NULL,
                `contactName` varchar(255) COLLATE utf8_bin NOT NULL,
                `contactTitle` varchar(50) COLLATE utf8_bin NOT NULL,
                `contactPhone` varchar(12) COLLATE utf8_bin DEFAULT NULL,
                `contactEmail` varchar(255) COLLATE utf8_bin NOT NULL,
                `contactComments` blob NOT NULL,
                PRIMARY KEY (`contactID`),
                UNIQUE KEY `contactID` (`contactID`),
                KEY `companyID` (`companyID`),
                KEY `contactName` (`contactName`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;";
              
              $createTable5 = "CREATE TABLE IF NOT EXISTS `".$database_prefix."jobInterviews` (
                `interviewID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `userID` bigint(20) NOT NULL,
                `companyID` bigint(20) NOT NULL,
                `interviewDate` varchar(10) COLLATE utf8_bin NOT NULL,
                `interviewTime` varchar(5) COLLATE utf8_bin NOT NULL,
                `interviewCompleted` tinyint(1) NOT NULL,
                `interviewComments` blob NOT NULL,
                PRIMARY KEY (`interviewID`),
                UNIQUE KEY `interviewID` (`interviewID`),
                KEY `interviewDate` (`interviewDate`,`interviewCompleted`),
                KEY `interviewCompanyID` (`companyID`),
                KEY `userID` (`userID`),
                KEY `userID_2` (`userID`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;";
              
              $createTable6 = 'CREATE TABLE IF NOT EXISTS `' . $database_prefix . 'jobUsers` (
                `userID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `username` varchar(15) COLLATE utf8_bin NOT NULL,
                `password` varchar(15) COLLATE utf8_bin NOT NULL,
                `firstName` varchar(15) COLLATE utf8_bin NOT NULL,
                `lastName` varchar(15) COLLATE utf8_bin NOT NULL,
                `socLastFour` int(4) NOT NULL,
                `email` varchar(100) COLLATE utf8_bin NOT NULL,
                `photoPath` varchar(255) COLLATE utf8_bin NULL,
                `resumePath` varchar(255) COLLATE utf8_bin NULL,
                `permission` varchar(5) COLLATE utf8_bin NOT NULL,
                PRIMARY KEY (`userID`),
                UNIQUE KEY `userID` (`userID`),
                KEY `username` (`username`),
                KEY `firstName` (`firstName`,`lastName`,`permission`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;';
            }else{
              $createTable1 = "CREATE TABLE IF NOT EXISTS `jobActivity` (
                `activityID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `companyID` bigint(20) NOT NULL,
                `userID` bigint(20) NOT NULL,
                `contactID` bigint(20) NOT NULL,
                `contactDate` date NOT NULL,
                `contactTime` time NOT NULL,
                `activitySpokeTo` varchar(255) COLLATE utf8_bin NOT NULL,
                `activityLeftMsg` tinyint(1) NOT NULL,
                `followUpDate` date NOT NULL,
                `followUpTime` time NOT NULL,
                `appOnline` tinyint(1) DEFAULT NULL,
                `appOnsite` tinyint(1) DEFAULT NULL,
                `resOnsite` tinyint(1) DEFAULT NULL,
                `resFaxed` tinyint(1) DEFAULT NULL,
                `resEmailed` tinyint(1) DEFAULT NULL,
                `resEmailedTo` varchar(255) COLLATE utf8_bin DEFAULT NULL,
                `positionOpen` varchar(255) COLLATE utf8_bin DEFAULT NULL,
                `activityComments` blob NOT NULL,
                PRIMARY KEY (`activityID`),
                UNIQUE KEY `activityID` (`activityID`),
                KEY `companyID` (`companyID`,`contactDate`,`followUpDate`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;";
              
              $createTable2 = "CREATE TABLE IF NOT EXISTS `jobCallLog` (
                `contactID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `userID` bigint(20) NOT NULL,
                `companyID` bigint(20) NOT NULL,
                `contactName` varchar(255) COLLATE utf8_bin NOT NULL,
                `contactTitle` varchar(50) COLLATE utf8_bin NOT NULL,
                `contactPhone` varchar(12) COLLATE utf8_bin DEFAULT NULL,
                `contactEmail` varchar(255) COLLATE utf8_bin NOT NULL,
                `contactComments` blob NOT NULL,
                PRIMARY KEY (`contactID`),
                UNIQUE KEY `contactID` (`contactID`),
                KEY `companyID` (`companyID`),
                KEY `contactName` (`contactName`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;";
              
              $createTable3 = " CREATE TABLE IF NOT EXISTS `jobCompanies` (
                `companyID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `userID` bigint(20) NOT NULL,
                `companyName` varchar(255) COLLATE utf8_bin NOT NULL,
                `companyStr` varchar(50) COLLATE utf8_bin DEFAULT NULL,
                `companySte` varchar(15) COLLATE utf8_bin DEFAULT NULL,
                `companyCty` varchar(25) COLLATE utf8_bin DEFAULT NULL,
                `companyState` varchar(2) COLLATE utf8_bin DEFAULT NULL,
                `companyZip5` varchar(5) COLLATE utf8_bin DEFAULT NULL,
                `companyPh` varchar(25) COLLATE utf8_bin DEFAULT NULL,
                `companyUrl` varchar(25) COLLATE utf8_bin DEFAULT NULL,
                PRIMARY KEY (`companyID`),
                KEY `userID` (`userID`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;";
              
              $createTable4 = "CREATE TABLE IF NOT EXISTS `jobContact` (
                `contactID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `userID` bigint(20) NOT NULL,
                `companyID` bigint(20) NOT NULL,
                `contactName` varchar(255) COLLATE utf8_bin NOT NULL,
                `contactTitle` varchar(50) COLLATE utf8_bin NOT NULL,
                `contactPhone` varchar(12) COLLATE utf8_bin DEFAULT NULL,
                `contactEmail` varchar(255) COLLATE utf8_bin NOT NULL,
                `contactComments` blob NOT NULL,
                PRIMARY KEY (`contactID`),
                UNIQUE KEY `contactID` (`contactID`),
                KEY `companyID` (`companyID`),
                KEY `contactName` (`contactName`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;";
              
              $createTable5 = "CREATE TABLE IF NOT EXISTS `jobInterviews` (
                `interviewID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `userID` bigint(20) NOT NULL,
                `companyID` bigint(20) NOT NULL,
                `interviewDate` varchar(10) COLLATE utf8_bin NOT NULL,
                `interviewTime` varchar(5) COLLATE utf8_bin NOT NULL,
                `interviewCompleted` tinyint(1) NOT NULL,
                `interviewComments` blob NOT NULL,
                PRIMARY KEY (`interviewID`),
                UNIQUE KEY `interviewID` (`interviewID`),
                KEY `interviewDate` (`interviewDate`,`interviewCompleted`),
                KEY `interviewCompanyID` (`companyID`),
                KEY `userID` (`userID`),
                KEY `userID_2` (`userID`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;";
              
              $createTable6 = 'CREATE TABLE IF NOT EXISTS `jobUsers` (
                `userID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `username` varchar(15) COLLATE utf8_bin NOT NULL,
                `password` varchar(15) COLLATE utf8_bin NOT NULL,
                `firstName` varchar(15) COLLATE utf8_bin NOT NULL,
                `lastName` varchar(15) COLLATE utf8_bin NOT NULL,
                `socLastFour` int(4) NOT NULL,
                `email` varchar(100) COLLATE utf8_bin NOT NULL,
                `photoPath` varchar(255) COLLATE utf8_bin NULL,
                `resumePath` varchar(255) COLLATE utf8_bin NULL,
                `permission` varchar(5) COLLATE utf8_bin NOT NULL,
                PRIMARY KEY (`userID`),
                UNIQUE KEY `userID` (`userID`),
                KEY `username` (`username`),
                KEY `firstName` (`firstName`,`lastName`,`permission`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;';
            }
            
            //echo '<h3 class="install-h3">About to attempt creating the tables...</h3><br />';        
            $newConn = new mysqli($database_host, $database_username, $database_password, $database_name);
            if ($newConn->connect_errno) {
              printf("Database connection failed: %s\n", $newConn->connect_errno);
            }
            
            //$connection->query($allTables) or die($connection->errno);
            if(!$newConn->query("DESCRIBE `$createTable1`")) { //We need to check if the table already exists first
														  // The use may simply be re-using the code on the same server.
				if (!$newConn->query($createTable1) === TRUE) {
				  echo "<h4>The create table 1 query failed! Error was: " . $newConn->errno . ": " . $newConn->error . "</h4><br>";
				}
			}
            
            if(!$newConn->query("DESCRIBE `$createTable2`")) {
				if (!$newConn->query($createTable2) === TRUE) {
				  echo "<h4>The create table 1 query failed! Error was: " . $newConn->errno . ": " . $newConn->error . "</h4><br>";
				}
			}

            if(!$newConn->query("DESCRIBE `$createTable3`")) {
				if (!$newConn->query($createTable3) === TRUE) {
				  echo "<h4>The create table 1 query failed! Error was: " . $newConn->errno . ": " . $newConn->error . "</h4><br>";
				}
			}
                        
            if(!$newConn->query("DESCRIBE `$createTable4`")) {
				if (!$newConn->query($createTable4) === TRUE) {
				  echo "<h4>The create table 1 query failed! Error was: " . $newConn->errno . ": " . $newConn->error . "</h4><br>";
				}
			}
                        
            if(!$newConn->query("DESCRIBE `$createTable5`")) {
				if (!$newConn->query($createTable5) === TRUE) {
				  echo "<h4>The create table 1 query failed! Error was: " . $newConn->errno . ": " . $newConn->error . "</h4><br>";
				}
			}
                        
            if(!$newConn->query("DESCRIBE `$createTable6`")) {
				if (!$newConn->query($createTable6) === TRUE) {
				  echo "<h4>The create table 1 query failed! Error was: " . $newConn->errno . ": " . $newConn->error . "</h4><br>";
				}
			}
                        
            //if (!$newConn->query($createTable7) === TRUE) {
            //  echo "<h4>The create table 7 query failed! Error was: " . $newConn->errno . ": " . $newConn->error . "</h4><br>";
            //}
            //
            //if (!$newConn->query($createTable8) === TRUE) {
            //  echo "<h4>The create table 7 query failed! Error was: " . $newConn->errno . ": " . $newConn->error . "</h4><br>";
            //}
     
            require "password_compat/lib/password.php";
            $salt = "NSwjO2SgGpy2MZm81KBF0JrxEcn3zAHOFKYEfI9s";
            
            //$admin_pass = md5($admin_password, $salt);
            $admin_pass = $admin_password;
            
            if (isset($database_prefix) && !empty($database_prefix)) {        
	            $adminQry = "INSERT INTO `" . $database_prefix . "jobUsers` (username, password, permission)
                          VALUES ('$admin_name', '$admin_pass', 'admin')";
				$userSelQry = "SELECT * FROM `" . $database_prefix . "jobUsers`
							   WHERE username = '$admin_name';";
			}else{
	            $adminQry = "INSERT INTO `" . $database_prefix . "jobUsers` (username, password, permission)
                          VALUES ('$admin_name', '$admin_pass', 'admin')";
				$userSelQry = "SELECT * FROM `jobUsers`
							   WHERE username = '$admin_name';";
			}
            //echo '<h3 class="install-h3">About to insert the admin account info...</h3><br />';
                          
            //echo "The admin insert query statement BEFORE insertion: " . $adminQry . "<br>";
            //sleep(30);
    
            if (!$newConn->query($userSelQry) === TRUE) { //means the user doesn't exist
				if (!$newConn->query($adminQry) === TRUE) {
				  echo "<h4>The admin insert query failed! Error was: " . $newConn->errno . ": " . $newConn->error . "</h4><br>";            
				}
			}
            
            $newConn->close($newConn);
            //echo '<h3 class="install-h3">Attempting to create the settings.php file...</h3><br />';
      
            $content = "<?php\n
    /**
     * Base URL (optional).
     *
     * If @Beyond Code is generating incorrect URLs on your site, which could
     * be in HTML headers (links to CSS and JS files) or visible links on pages
     * (such as in menus), uncomment the Base URL statement below (remove the
     * leading hash/pound sign) and fill in the absolute URL to your @Beyond Code installation.
     *
     * You might also want to force users to use a given domain.
     *
     * Examples:
     *   \$base_url = 'http://www.example.com';
     *   \$base_url = 'http://www.example.com:8888';
     *   \$base_url = 'http://www.example.com/rjournal';
     *   \$base_url = 'https://www.example.com:8888/rjournal';
     *
     * It is not allowed to have a trailing slash; @Beyond Code will add it
     * for you.
     */
    \n
    # \$base_url = 'http://www.example.com';  // NO trailing slash!
    \n
    //DO NOT CHANGE ANYTHING BELOW!!! It is set during install!! If you DO change this it WILL break the system!
    \n
    \$host = '".$database_host."';
    \$defaultdb = '".$database_name."';
    \$serveruser = '".$database_username."';
    \$password = '".$database_password."';
    \$prefix = '".$database_prefix."';
    \$salt = 'NSwjO2SgGpy2MZm81KBF0JrxEcn3zAHOFKYEfI9s';
    ";
    
            
            //echo '<h4 class="install-h3">Settings data is ready, atempting to write the file in 20 seconds...</h4><br />';
            //sleep(20);
    
            $settingsFile = dirname(__FILE__)."/settings.php"; 
            $myDir = JC_ROOT;
            
            clearstatcache();
            
            if (!$fileHandle = fopen($settingsFile, 'w')) {
              echo '<h3 class="install-h3">I couldn\'t write/create the settings.php file...</h3><br>';
            }else{
              if (fwrite($fileHandle, $content) == FALSE) {
                echo '<h3 class="install-h3">I wasn\'t able to write the settings.php file for some reason...</h3><br>';
              }else{
                fclose($fileHandle);
                //echo '<h3 class="install-h3">The settings.php file SHOULD have been created...taking you to Step 4 in 20 seconds...</h3><br>';
                sleep(20);
                header("Location: install.php?step=4");
                echo '<div class="install"><h3 class="install-h3">Almost done...see that button below? Just click it :P</h3><br>
                 <p><a class="btn btn-primary btn-lg" type="button" href="install.php?step=4">Continue -></a></p></div>';
      $admin_name=isset($_POST['user'])?$_POST['user']:"";
      $admin_password=isset($_POST['pass'])?$_POST['pass']:"";

              }
            }
          }
        }
      }       
    }
  }//end of step_3
  
  function step_4 ()
  {
      //echo '<br><h3 class="install-h3">In VISIBLE step_3 (internal setp_4) Everything SHOULD be completed....</h3><br>';
   ?>
    <div class="install">
		<h3 class="install-h3">Installation Step 3</h3><br>
		<p class="install-p">Installation complete!:</p><br>
		<p class="install-p">You installation has been completed, we thank you for your patience and hope you enjoy your new home.<br>
		<br>You will want to go to your account settings and complete your profile.<br><br>Enjoy!<br><br>-- It's Just Code Staff</p>
		<form method="post" action="./functions.php?action=login">
			<input type="hidden" name="username" value="'.$admin_name.'">
			<input type="hidden" name="password" value="'.$admin_password.'">
			<input type="submit" class="btn btn_default" value="Login">
		</form>
	</div>
  <?php
  }
  


	include('./footer.php');






    
