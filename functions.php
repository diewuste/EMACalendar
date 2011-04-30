<?php

function clean_source($buffer) {
	return (preg_replace("/\t/", '', $buffer));
}

function sendemail($email, $subject, $message) {
	$headers = 'From: "EMACalendar"<no-reply@emacalendar.diewuste.fr>' . "\n";
	$headers .='Reply-To: ' . $email . "\n";
	$headers .='Content-Type: text/html; charset="utf-8"' . "\n";
	$headers .='Content-Transfer-Encoding: 8bit';

	$subject = '[EMACalendar-Feedback] ' . $subject;

	return mail('nicolas.talon@mines-ales.org', $subject, $message, $headers);
}

?>