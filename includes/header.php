<?php
    session_start(); //this must be on each page the $_SESSION will be set or accessed.
?>
  <html lang="en">
    <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Flexi-Coding LLC" content="">
		<meta name="Matthew Gagn&eacute;@Flexi-Coding LLC" content="">
		<link rel="shortcut icon" href="img/favicon.ico">  
	    <title>Personal R&eacute;sum&eacute;/CV site of Matthew Gagn&eacute;</title>
		
		<link href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
		 
<?php
    if (!strpos(getcwd(),'includes')) {
		echo '<script src="../cv/js/bootstrap.min.js"></script>
		<link href="../cv/css/bootstrap.css" rel="stylesheet">
		
		<!-- Add the Timer and Easing plugins -->
		<script type="text/javascript" src="../cv/js/jquery.timers-1.2.js"></script>
		<script type="text/javascript" src="../cv/js/jquery.easing.1.3.js"></script>
		
		<!-- Add the GalleryView Javascript and CSS files -->
		<script type="text/javascript" src="../cv/js/jquery.galleryview-3.0-dev.js"></script>
		<link type="text/css" rel="stylesheet" href="../cv/css/jquery.galleryview-3.0-dev.css" />

		<!-- Now for the Popup Quote/Contact Form -->
		<link href="../cv/css/jquery.feedback_me.css" rel="stylesheet" type="text/css">
		<script src="../cv/js/jquery.feedback_me.js"></script>

		<!-- Custom styles for this template -->
		<link href="../cv/css/cv.css" rel="stylesheet" type="text/css" />        
		';
	}else{
		echo '<script src="../js/bootstrap.min.js"></script>
		<link href="../css/bootstrap.css" rel="stylesheet">

		<!-- Add the Timer and Easing plugins -->
		<script type="text/javascript" src="../js/jquery.timers-1.2.js"></script>
		<script type="text/javascript" src="../js/jquery.easing.1.3.js"></script>
		
		<!-- Add the GalleryView Javascript and CSS files -->
		<script type="text/javascript" src="../js/jquery.galleryview-3.0-dev.js"></script>
		<link type="text/css" rel="stylesheet" href="../css/jquery.galleryview-3.0-dev.css" />

		<!-- Now for the Popup Quote/Contact Form -->
		<link href="../css/jquery.feedback_me.css" rel="stylesheet" type="text/css">
		<script src="../js/jquery.feedback_me.js"></script>
		
		<!-- Custom styles for this template -->
		<link href="../css/cv.css" rel="stylesheet" type="text/css" />        
		';
	}
?>		
		
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<script type="text/javascript">
			$(document).ready(function() {
				var sessLoggedIn = <?php echo json_encode($_SESSION['logged_in']); ?>;
				
				$('#contactDiv').hide();
				
				$(function() {
					var button = $('#loginButton');
					var box = $('#loginBox');
					var form = $('#loginForm');
					button.removeAttr('href');
					button.mouseup(function(login) {
						box.toggle();
						button.toggleClass('active');
					});
					form.mouseup(function() { 
						return false;
					});
					$(this).mouseup(function(login) {
						if(!($(login.target).parent('#loginButton').length > 0)) {
							button.removeClass('active');
							box.hide();
						}
					});
				});

				$(function(){
					$('.tab-section').hide();
					$('#tabs a').bind('click', function(e){
						$('#tabs a.current').removeClass('current');
						$('.tab-section:visible').hide();
						$(this.hash).show();
						$(this).addClass('current');
						//e.preventDefault();
					}).filter(':first').click();
				});
				
				if (sessLoggedIn){
				   // alert('You are logged in!');
					$('#loginButton').hide();
					$('#btn_logout').show();
					$('#admin').show();
				}else{
					//alert('You are not logged in!');
					$('#btn_logout').hide();					
					$('#loginButton').show();
					$('#admin').hide();
			    }
				$('#admin').bind('click', function(e){
					var url = 'overview.php';
					$.getJSON(url, function(data) {
						$.each(data, function(index, data) {
							$('#tablebody').append('<tr>');
							$('#tablebody').append('<td>'+data.name+'</td>');
							$('#tablebody').append('<td>'+data.pop_male+'</td>');
							$('#tablebody').append('<td>'+data.pop_female+'</td>');
							$('#tablebody').append('<td>'+data.pop_total+'</td>');
							$('#tablebody').append('</tr>');
						});
					});
				});

				$('#myGallery').galleryView({
					filmstrip_position: 'right',
					enable_overlays: true,
					overlay_position: 'top',
					panel_animation: 'crossfade'
				});
				
				//set up some minimal options for the feedback_me plugin
				//fm_options = {
				//	jQueryUI: true,
				//	bootstrap: true,
				//	position: "right-top",
				//	title_label: "Request a Quote",
				//	trigger_label: "Request a Quote",
				//	show_email: true,
				//	name_label: "Name:",
				//	email_label: "Email:",
				//	message_label: "Message:",
				//	name_placeholder: "YOUR name goes here...",
				//	email_placeholder: "user@example.com",
				//	message_placeholder: "The details of the type of service you would like a quote for.",
				//	name_required: true,
				//	email_required: true,
				//	message_required: true,
				//	show_asterisk_for_required: true,
				//	feedback_url: "mail.php",
				//	//delayed_options: {
				//	//	delay_success_milliseconds : 5000,
				//	//	delay_fail_milliseconds : 8000,
				//	//	sending : "Sending...", //This text will appear on the "send" button while sending
				//	//	send_fail : "Sending failed.", //This text will appear on the fail dialog
				//	//	send_success : "Feedack sent.", //This text will appear on the success dialog
				//	//	fail_color : #ff0000,
				//	//	success_color : #0000ff
				//	//}
				//	custom_params: {
				//		feedback_type: "Quote Request"
				//	}
				//};
				//
				////init feedback_me plugin
				//fm.init(fm_options);
				
				$('#contactLnk').bind('click', function(e){
					$('#contactDiv').slideToggle( "slow" );
				});

				$('#quoteBtn').bind('click', function(e) {
					alert('Made it into the searchform submit event');
					//get input field values
					var user_name       = $('input[name=name]').val(); 
					var user_email      = $('input[name=email]').val();
					var user_message    = $('textarea[name=message]').val();
					
					//simple validation at client's end
					//we simply change border color to red if empty field using .css()
					var proceed = true;
					if(user_name==""){ 
						$('input[name=name]').css('border-color','red'); 
						proceed = false;
					}
					if(user_email==""){ 
						$('input[name=email]').css('border-color','red'); 
						proceed = false;
					}
					if(user_message=="") {  
						$('textarea[name=message]').css('border-color','red'); 
						proceed = false;
					}
			
					//everything looks good! proceed...
					if(proceed) 
					{
						//data to be sent to server
						post_data = {'userName':user_name, 'userEmail':user_email, 'userPhone':user_phone, 'userMessage':user_message};
						
						//Ajax post data to server
						$.post('./includes/mail.php', post_data, function(response){  
							
							//load json data from server and output message     
							if(response.type == 'error')
							{
								output = '<div class="error">'+response.text+'</div>';
							}else{
							
								output = '<div class="success">'+response.text+'</div>';
								
								//reset values in all input fields
								$('#contact_form input').val(''); 
								$('#contact_form textarea').val(''); 
							}
							
							$("#result").hide().html(output).slideDown();
						}, 'json');
						
					}
				});
				
				//reset previously set border colors and hide all message on .keyup()
				$("#contact_form input, #contact_form textarea").keyup(function() { 
					$("#contact_form input, #contact_form textarea").css('border-color',''); 
					$("#result").slideUp();
				});				
			});/* end of doc ready */     
		</script>
<!--        <script type = "text/javascript" src = "../js/sliding.form.js"></script>-->
	</head>
  
	<body>
<!--		<div class="navbar" role="navigation">-->
		<div class="navbar">
			<a href="../index.php" title"Home">
				<div class="slogan">&nbsp;</div></a>
<!--				<div class="btn_login">-->
<?php		if (isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true) {			
				echo '<a href="./includes/functions.php?action=logout" id="btn_logout" class="btn btn_default btn_tabs btn_logout">Logout</a>';
			}
?>
<!--				</div>-->
			<ul id="tabs">
				<li><a href="#home" class="btn btn_default btn_tabs current">Home</a></li>
				<li><a href="#page2" class="btn btn_default btn_tabs">Experience</a></li>
				<li><a href="#page3" class="btn btn_default btn_tabs">Gallery/Portfolio</a></li>
				<li><a href="#adminpg" id="admin" class="btn btn_default btn_tabs">Job Tracking</a></li>
				
		</div>
			</ul>
		</div><!-- navbar -->			