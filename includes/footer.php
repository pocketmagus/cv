<?php
    
    $footerCode = '
      <footer class="footer">
      <hr>
        <p class="pull_left">2014 &copy; Flexicoding</p>
		<p class="pull_middle">';
		if ($_SESSION["logged_in"] != true) {			
			 $footerCode .= '<a href="#" id="loginButton"><span>Login</span></a>';
		}
	 $footerCode .= '&nbsp;&nbsp;<a href="#" id="contactLnk" role="button"> <i class="icon-mail"></i> REQUEST AN INTERVIEW</a></p>
	 <p class="pull_right"><a href="./includes/overview.php">TEST OVERVIEW</a></p>
      </footer>
	</body>
</html>
';

echo $footerCode;