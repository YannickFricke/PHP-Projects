<?php

define('INCLUDE_CHECK',true);

require 'connect.php';
require 'functions.php';
require 'config.php';
// Those three files can be included only if INCLUDE_CHECK is defined


session_name('wowpanel');
// Starting the session

session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks

session_start();

if($_SESSION['id'] && !isset($_COOKIE['meRemember']) && !$_SESSION['rememberMe'])
{
	// If you are logged in, but you don't have the meRemember cookie (browser restart)
	// and you have not checked the rememberMe checkbox:

	$_SESSION = array();
	session_destroy();
	
	// Destroy the session
}


if(isset($_GET['logoff']))
{
	$_SESSION = array();
	session_destroy();
	
	header("Location: index2.php");
	exit;
}

if($_POST['submit']=='Login')
{
	// Checking whether the Login form has been submitted
	
	// Select the right database ;)
	mysql_select_db("realmd");
	
	$err = array();
	// Will hold our errors
	
	
	if(!$_POST['username'] || !$_POST['password'])
		$err[] = 'All the fields must be filled in!';
	
	if(!count($err))
	{
		$uname = mysql_real_escape_string(strtoupper($_POST['username']));
		$pwd = mysql_real_escape_string(strtoupper($_POST['password']));
		$str = $uname. ":" .$pwd ;
		$pwd1 = sha1($str);
		$sql = "SELECT * FROM `account` WHERE `username` = '" .$uname. "' AND `sha_pass_hash` = '" .$pwd1. "';";
		//echo $sql;
		$query = mysql_query($sql);
		if (mysql_num_rows($query) == 0)
		{
		$err[]='Falscher Benutzername oder falsches Passwort!';
		}
		else 
		{
		$data = mysql_fetch_array($query);
		$_SESSION['id'] = $data[id];
		$_SESSION['usr'] = $uname;
		setcookie('meRemember',$_POST['rememberMe']);
		}
	}
	
	if($err)
	$_SESSION['msg']['login-err'] = implode('<br />',$err);
	// Save the error messages in the session

	header("Location: index2.php");
	exit;
}
else if($_POST['submit']=='Register')
{
	// If the Register form has been submitted
	mysql_select_db("realmd");
	$err = array();
	
	if(strlen($_POST['username'])<4 || strlen($_POST['username'])>32)
	{
		$err[]='Der Benutzername darf mindestenz 3 Zeichen oder höchstens 32 Zeichen haben!';
	}
	
	if(preg_match('/[^a-z0-9\-\_\.]+/i',$_POST['username']))
	{
		$err[]='Der Benutzername enthält ung&uuml;ltige Zeichen!';
	}
	if($_POST['passwort1'] != $_POST['passwort2'])
	{
		$err[]='Die beiden Passw&ouml;rter stimmen nicht &uuml;berein!';
	}
	if(!count($err))
	{
		// If there are no errors
		$uname = strtoupper($_POST['username']);
		$pwd = strtoupper($_POST['passwort1']);
		$str = $uname. ":" .$pwd ;
		$pwd1 = sha1($str);
		$sql = sprintf("INSERT INTO `account` (`username`, `sha_pass_hash`) VALUES ('%s', '%s');", $uname, $pwd1);
		$query = mysql_query($sql);
		if(mysql_affected_rows($link)==1)
		{
			
			$_SESSION['msg']['reg-success']='Erfolgreich registriert! Sie k&ouml;nnen sich nun einloggen.';
		}
		else $err[]='Dieser Benutzername ist schon vergeben!';
	}

	if(count($err))
	{
		$_SESSION['msg']['reg-err'] = implode('<br />',$err);
	}	
	
	header("Location: index2.php");
	exit;
}

$script = '';

if($_SESSION['msg'])
{
	// The script below shows the sliding panel on page load
	
	$script = '
	<script type="text/javascript">
	
		$(function(){
		
			$("div#panel").show();
			$("#toggle a").toggle();
		});
	
	</script>';
	
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $servername; ?> - WoW-Panel</title>
    
    <link rel="stylesheet" type="text/css" href="demo.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="login_panel/css/slide.css" media="screen" />
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    
    <!-- PNG FIX for IE6 -->
    <!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
    <!--[if lte IE 6]>
        <script type="text/javascript" src="login_panel/js/pngfix/supersleight-min.js"></script>
    <![endif]-->
    
    <script src="login_panel/js/slide.js" type="text/javascript"></script>
    
    <?php echo $script; ?>
</head>

<body>

<!-- Panel -->
<div id="toppanel">
	<div id="panel">
		<div class="content clearfix">
			<div class="left">
				<h1>The Sliding jQuery Panel</h1>
				<h2>A register/login solution</h2>		
				<p class="grey">You are free to use this login and registration system in you sites!</p>
				<h2>A Big Thanks</h2>
				<p class="grey">This tutorial was built on top of <a href="http://web-kreation.com/index2.php/tutorials/nice-clean-sliding-login-panel-built-with-jquery" title="Go to site">Web-Kreation</a>'s amazing sliding panel.</p>
			</div>
            
            
            <?php
			
			if(!$_SESSION['id']):
			
			?>
            
			<div class="left">
				<!-- Login Form -->
				<form class="clearfix" action="" method="post">
					<h1>Member Login</h1>
                    
                    <?php
						
						if($_SESSION['msg']['login-err'])
						{
							echo '<div class="err">'.$_SESSION['msg']['login-err'].'</div>';
							unset($_SESSION['msg']['login-err']);
						}
					?>
					
					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					<label class="grey" for="password">Password:</label>
					<input class="field" type="password" name="password" id="password" size="23" />
	            	<label><input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1" /> &nbsp;Remember me</label>
        			<div class="clear"></div>
					<input type="submit" name="submit" value="Login" class="bt_login" />
				</form>
			</div>
			<div class="left right">			
				<!-- Register Form -->
				<form action="" method="post">
					<h1>Not a member yet? Sign Up!</h1>		
                    
                    <?php
						
						if($_SESSION['msg']['reg-err'])
						{
							echo '<div class="err">'.$_SESSION['msg']['reg-err'].'</div>';
							unset($_SESSION['msg']['reg-err']);
						}
						
						if($_SESSION['msg']['reg-success'])
						{
							echo '<div class="success">'.$_SESSION['msg']['reg-success'].'</div>';
							unset($_SESSION['msg']['reg-success']);
						}
					?>
                    		
					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					<label class="grey" for="passwort">Passwort:</label>
					<input class="field" type="password" name="passwort1" id="email" size="23" />
					<label class="grey" for="passwort">Passwort wiederholen:</label>
					<input class="field" type="password" name="passwort2" id="email" size="23" />
					<input type="submit" name="submit" value="Register" class="bt_register" />
				</form>
			</div>
            
            <?php
			
			else:
			
			?>
            
            <div class="left">
            
            <h1>Members panel</h1>
            
            <p>You can put member-only data here</p>
            <a href="registered.php">View a special member page</a>
            <p>- or -</p>
            <a href="?logoff">Log off</a>
            
            </div>
            
            <div class="left right">
            </div>
            
            <?php
			endif;
			?>
		</div>
	</div> <!-- /login -->	

    <!-- The tab on top -->	
	<div class="tab">
		<ul class="login">
	    	<li class="left">&nbsp;</li>
	        <li>Hallo <?php echo $_SESSION['usr'] ? $_SESSION['usr'] : 'Guest';?>!</li>
			<li class="sep">|</li>
			<li id="toggle">
				<a id="open" class="open" href="#"><?php echo $_SESSION['id']?'Panel &ouml;ffnen':'Einloggen';?></a>
				<a id="close" style="display: none;" class="close" href="#">Panel schlie&szlig;en</a>			
			</li>
	    	<li class="right">&nbsp;</li>
		</ul> 
	</div> <!-- / top -->
	
</div> <!--panel -->
<?php
	if($_SESSION['id']):
?>
<div class="pageContent">
    <div id="main">
      <div class="container">
      <center>
      <a href="./account/create/"><img src="img/acc_create.png" /></a>      <a href="./vote"><img src="img/vote_page.png" /></a>      <a href="./forum"><img src="img/forum_page.png" /></a>				<a href="./ticketsystem/"><img src="img/ticketsystem.png" /></a><br /><br /><br /><br /><br /><a href="./unstucker/"><img src="img/unstucker.png" /></a>      <a href="./chart-transport"><img src="img/char_trans.png" /></a>      <a href="./shop/"><img src="img/shop.png" /></a>      <a href="./beschwerden"><img src="img/Beschwerden.png" /></a></center>
      </div>
    </div>
</div>
<?php
else:
?>
<div class="pageContent">
    <div id="main">
      <div class="container">
      <center>
      Sie müssen <a href="index2.php">eingeloggt</a> sein, bevor Sie diese Seite benutzen k&ouml;nnen!
      </div>
    </div>
</div>
<?
endif;
?>
</body>
</html>