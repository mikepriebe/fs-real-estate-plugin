<?php 
if (isset($_POST['submit'])) {
	$FSREPSendMail = TRUE;
	$ReplyEmail = explode('@',get_bloginfo('admin_email'));
	$EmailOrderRecipients = explode(',', $ListingDetails->listing_contact_form_email);
	unset($_POST['submit']);
	$CFormMessage = '';
	foreach ($_POST as $Name => $Value) {
		$CFormMessage .= '<strong>'.str_replace('_',' ',$Name).':</strong> '.$Value.'<br />';
	}
	if (function_exists('fsrep_pro_recaptcha_post')) { $FSREPSendMail = fsrep_pro_recaptcha_post(); }
		if ($FSREPSendMail == TRUE) {
		$subject = "Listing #".$ListingDetails->listing_id." Inquiry (".date("m/d/y").")"; 
		$headers  = 'MIME-Version: 1.0' . "\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
		$headers .= 'From: '.get_bloginfo('name').' <no-reply@'.$ReplyEmail[1].'>';
		for ($i=0;$i<=sizeof($EmailOrderRecipients);$i++) {
			mail($EmailOrderRecipients[$i], $subject, $CFormMessage, $headers);
		}
		if ($FSREPconfig['CopyAdminOnListingMessages'] == 'Yes') {
			mail(get_bloginfo('admin_email'), $subject, $CFormMessage, $headers);
		}
		$success = TRUE;
	}
}

$PageContent .= "<script language=\"javascript\" type=\"text/javascript\">
<!--
function popitup(url) {
	newwindow=window.open(url,'name','height=300,width=500');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
</script>";

if (isset($success)) {
	$PageContent .= 'Your message has been sent.';
} else {

	$PageContent .= '<a id="fsrep-contact-form" name="fsrep-contact-form"></a>';
	$PageContent .= '<form action="#fsrep-contact-form" id="fsrep-contact-form" method="post" style="margin-bottom: 5px; padding-bottom: 0;">';
	$PageContent .= '<table border="0" cellspacing="0" cellpadding="1">';

	$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_contact_fields ORDER BY field_order");
	foreach ($Fields as $Fields) {
		$FieldArray = array("" => "");
		$Array = explode(',',$Fields->field_value);
		for($i=0;$i<count($Array);$i++) {
			$AddArray = array($Array[$i] => $Array[$i]);
			$FieldArray = array_merge($FieldArray, $AddArray);
		}
		unset($FieldArray['']);
		if ($Fields->field_type == 'selectbox') {
			$PageContent .= fsrep_return_admin_selectbox($Fields->field_name, $Fields->field_name, '', $FieldArray, '', '');
		} elseif($Fields->field_type == 'radio') {
			$PageContent .= fsrep_return_admin_radio($Fields->field_name, $Fields->field_name, '', $FieldArray, '', '');
		} elseif($Fields->field_type == 'checkbox') {
			$PageContent .= fsrep_return_admin_checkbox($Fields->field_name, $Fields->field_name, '', $FieldArray, '', '');
		} elseif($Fields->field_type == 'textarea') {
			$PageContent .= fsrep_return_admin_textarea($Fields->field_name, $Fields->field_name, '', 8, 40, '');
		} else {
			$PageContent .= fsrep_return_admin_input($Fields->field_name, $Fields->field_name, '', 35, '');
		}
	}

	if (function_exists('fsrep_pro_recaptcha_get_html')) { $PageContent .= fsrep_pro_recaptcha_get_html(); }
	$PageContent .= '<tr><td width="100">&nbsp;</td><td style="text-align: center; padding-top: 5px;"><input name="submit" type="submit" value="Send Message" id="cfsubmit"></td></table>';
}
$PageContent .= '</form>';

if (isset($_POST['submit'])) { 
if ($error != "") {
$PageContent .= "<script language=\"javascript\" type=\"text/javascript\">
alert('$error');
</script>
";
}
}
?>
