<?php 

function fsrep_sendmail($rname, $remail, $semail, $subject, $message) {
	// sendmail(Sender Name, Sender Email, Recipient Email, Subject, Message)
 	$error = "";
 	
	// CHECK FOR ERRORS
	if (!preg_match('/^[a-z0-9()\/\'":\*+|,.; \- !?&#$@]{2,75}$/i', $rname)){
		$error = "Please enter a valid name.";
	}
	if (!preg_match('/^[a-z0-9()\/\'":\*+|,.; \- !?&#$@]{2,75}$/i', $subject)){
		$error = "Please enter a valid subject.";
	}
	if (!preg_match("/^[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}$/i", $remail)){
		$error = "Please enter a valid email address.";
	}


	if ($error == "") {
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "From: \"$rname\" <$remail>\r\n"; 
		$headers .= "Reply-To: $remail\r\n"; 
		$headers .= "Content-Type: text/HTML; charset=ISO-8859-1\r\n";
		$headers .= "\r\n"; 
		mail($semail, $subject, $message, $headers);
		return "successful";
	} else {
		return $error;
	}
}

if (isset($_POST['submit'])) { 
	$error = "";
	
	$useremail = $_POST['contact_email'];
	$name = $_POST['contact_name'];
	$phone = $_POST['contact_phone'];
	$message = $_POST['contact_message'];
	
	if ($phone != "") {
		if (!preg_match('/^[a-z0-9()\/\'":\*+|,.; \- !?&#$@]{2,75}$/i', $phone)){
			$error = "Please enter a valid phone.";
		}
	}
	
	$message = preg_replace("/\r/", "<br />", $message); 
	$message = preg_replace("/\n/", "<br />", $message);
	
	if ($error == "") {
		// Receiver Email Address
		$email = $ListingDetails->listing_contact_form_email;

		// The message
		$subject = "Listing #".$ListingDetails->listing_id." Inquiry (".date("m/d/y").")"; 
		//$message = str_replace("\n", "<br>", $message);
		$message2 = "<b>Name:</b> $name<br>";
		$message2 .= "<b>Email:</b> $useremail<br>";
		if ($phone != "") {
			$message2 .= "<b>Phone:</b> $phone<br>";
		}
		$message2 .= "<br>";
		$message2 .= "<b>Message:</b> $message";
		
		// mail message
		if (fsrep_sendmail($name, $useremail, $email, $subject, $message2) == "successful") {
			$success = TRUE;
		} else {
			$error = fsrep_sendmail($name, $useremail, $email, $subject, $message2);
		}
	}
}

?>
<script language="javascript" type="text/javascript">
<!--
function popitup(url) {
	newwindow=window.open(url,'name','height=300,width=500');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
</script>
<?php
if (isset($success)) {
	echo 'Your message has been sent.';
} else {
?>
<a id="fsrep-contact-form" name="fsrep-contact-form"></a>
<form action="#fsrep-contact-form" method="post" style="margin-bottom: 5px; padding-bottom: 0;">
<table border="0" cellspacing="0" cellpadding="1">
<tr> 
<td width="100"><b>Your Name:</b></td>
<td><input name="contact_name" type="text" style="font-size: 12px;" size="38" value="<?php if (isset($_POST['contact_name'])) { print $_POST['contact_name']; }?>"></td>
</tr>
<tr> 
<td width="100"><b>Your Email:</b></td>
<td><input name="contact_email" type="text" style="font-size: 12px;" size="38" value="<?php if (isset($_POST['contact_email'])) { print $_POST['contact_email']; }?>"></td>
</tr>
<tr> 
<td width="100"><b>Your Phone:</b></td>
<td><input name="contact_phone" type="text" style="font-size: 12px;" size="38" value="<?php if (isset($_POST['contact_phone'])) { print $_POST['contact_phone']; }?>"></td>
</tr>
<tr> 
<td width="100"><b>Message:</b></td>
<td><textarea name="contact_message" cols="38" style="font-size: 12px;" rows="7"><?php if (isset($_POST['contact_message'])) { print $_POST['contact_message']; }?></textarea></td>
</tr>
<tr><td colspan="2" style="text-align: center; padding-top: 5px;"><input name="submit" type="submit" value="Send"></td>
</tr>
</table>
</form>
<?php
}

if (isset($_POST['submit'])) { 
if ($error != "") {
print "<script language=\"javascript\" type=\"text/javascript\">
alert('$error');
</script>
";
}
}
?>
