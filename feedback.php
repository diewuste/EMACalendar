<?php
require_once 'functions.php';

session_start();

$send = false;

if (!empty($_REQUEST['userCode'])) {
	$userCode = strtoupper($_REQUEST['userCode']);
	if (md5($userCode) == $_SESSION['captcha']) {
		sendemail($_REQUEST['email'], $_REQUEST['subject'], $_REQUEST['message']);
		$_REQUEST = null;
		$send = true;
	}
}

ob_start('clean_source');
?>
<!doctype html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title>EMACalendar : Feedback</title>
		<script src="js/mootools.core.js" type="text/javascript"></script>
		<script src="js/mootools.more.js" type="text/javascript"></script>
		<style>
			body{color:white;font:normal 14px/16px "Helvetica Neue",Helvetica,sans-serif;margin:0;padding:0}
			form{position:relative;width:100%}
			form fieldset{padding:1em}
			form label{display:inline;float:left;width:20%;padding:3px}
			form input{float:right;width:75%}
			form textarea{float:right;width:100%;height:150px}
			form input#button{float:none;display:block;clear:both;width:70px;margin:0 40%}
			.captcha{text-align:center}
			.captcha img{vertical-align:middle}
			.captcha input{float:none;width:100px}
			.error{background-color:firebrick}
			.msg{font-weight:blod;text-align:center}
		</style>
	</head>
	<body>
<?php
if ($send) {
	echo '<p class="msg">Merci pour votre feedback :)</p>';
} else {
	?>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="contact">
				<fieldset>
					<legend>Un bug, une amélioration ? Laissez-moi un message :)</legend>
					<label for="email">E-mail :</label><input type="text" name="email" id="email" value="<?php echo (isset($_REQUEST['email']) ? $_REQUEST['email'] : '') ?>"/><br/>
					<label for="subject">Sujet :</label><input type="text" name="subject" id="subject" value="<?php echo (isset($_REQUEST['subject']) ? $_REQUEST['subject'] : '') ?>"/><br/>
					<label for="message">Message :</label><br/>
					<textarea name="message" id="message" rows="10" cols="50"><?php echo (isset($_REQUEST['message']) ? $_REQUEST['message'] : '') ?></textarea><br/>
					<p class="captcha<?php if (!$send && isset($_REQUEST['userCode']))
			echo " error" ?>">
						<a style="cursor:pointer" onclick="document.images.captcha.src='captcha/captcha.php?id='+Math.round(Math.random(0)*1000)+1">
							<img src="captcha/reload.png" alt="Recharger le captcha"/>
						</a>
						<img src="captcha/captcha.php" alt="Captcha" id="captcha"/>
						<input name="userCode" id="userCode" type="text"/>
					</p>
					<input type="button" value="Envoyer" id="button" onclick="valid()"/>
				</fieldset>
			</form>
			<script type="text/javascript">
				function valid() {
					if ($('email').value == '') {
						alert('Merci de renseigner votre adresse mail');
						return false;
					} else if (!isValidEmail($('email').value)) {
						alert('Le mail saisi est invalide');
						return false;
					} else if ($('subject').value == '') {
						alert('Merci de renseigner le sujet du message');
						return false;
					} else if ($('message').value == '') {
						alert('Merci de renseigner votre message');
						return false;
					} else if ($('userCode').value == '') {
						alert('Merci de recopier le captcha');
						return false;
					}

					$('contact').submit();
				}

				function isValidEmail(mail) {
					var reg = new RegExp('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$', 'i');

					if (reg.test(mail))
						return true;
					else
						return false;
				}
			</script>
		<?php } ?>
	</body>
</html>
<?php ob_end_flush(); ?>