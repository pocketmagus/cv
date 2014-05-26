<?php

    /**
     * This sets a global path variable to use...which translates to
     * something like /var/www/<base dir>/, which is good enough for us.
     **/
    define('FLEXI_ROOT', getcwd() . "/");
    
    include './includes/header.php';
    session_start(); //this must be on each page the $_SESSION will be set or accessed.
   //echo '<br><br><h4>Am inside the index file BEFORE calling the init my path is set to: '.FLEXI_ROOT.'</h4>';
    require FLEXI_ROOT . "includes/functions.php";
    //echo '<br><br><h4>Am inside the index file AFTER calling the init</h4>';

    $initComplete = flexiInit(); 
    
    if ($initComplete != "COMPLETE" && empty($initComplete)) 
    {
        echo "<h3>Initialization failed...when checking for db... </h3>";
    }else{
      //echo '<h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Initialization complete! Your base URL is: ' . $GLOBALS["BASE_URL"] . '</h4>';
        //if (!$_SESSION["logged_in"] === true) {
        //    header('Location: login.php');
        //}        
        include_once "./includes/main.php";
    }
    include "./includes/footer.php";
?>	
    
<div id="loginBox">                
    <form id="loginForm" method="post" action="./includes/functions.php?action=login">
        <fieldset id="loginfieldset">
<!--            <fieldset>-->
                <label for="username">Username</label>
                <input type="text" name="username" id="username" />
                <label for="password">Password</label>
                <input type="password" name="password" id="password" />
<!--            </fieldset>-->
            <input type="hidden" id="action" value="login" />
            <input type="submit" id="login" class="btn btn_default" value="Login" />
<!--			<label for="checkbox"><input type="checkbox" id="checkbox" />Remember me</label>-->
        </fieldset>
<!--		<span><a href="#">Forgot your password?</a></span>-->
    </form>
</div>

<div id="contactDiv">
    <form method="post" id="contact_form" name="contact_form" action="#">
        <legend class="contH2">Request an Interview:</legend>
        <fieldset>
            <div id="result"></div>
            <label for="name"><span>Name</span>
            <input type="text" name="name" id="name" placeholder="Enter Your Name" />
            </label>           
            <label for="email"><span>Email Address</span>
            <input type="email" name="email" id="email" placeholder="Enter Your Email" />
            </label>
            <label for="message"><span>Message</span>
            <textarea name="message" id="message"  cols=28 rows=4 style="color: #011c00;" placeholder="Details for interview time and date"></textarea>
            </label>
            <input type="submit" id="quoteBtn" class="btn btn_default" value="Send">
        </fieldset>
    </form>       
</div>

