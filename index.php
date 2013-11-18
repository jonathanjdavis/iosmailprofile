<?php

$name = NULL;
$username = NULL;
$email = NULL;

$name_m = NULL;
$username_m = NULL;
$email_m = NULL;

$generate = TRUE;

if (isset($_POST)) {

    if(!empty($_POST['name'])) {
        $name = trim(strip_tags ($_POST['name']));
      } else {
        $generate = FALSE;
        $name_m = 'class="missing" ';
    }

    if(!empty($_POST['username'])) {
        $username = trim(strip_tags ($_POST['username']));

        if (strpos($username, ' ') > 0) {
            $generate = FALSE;
            $username_m = 'class="missing" ';
        }

      } else {
        $generate = FALSE;
        $username_m = 'class="missing" ';
    }


    if(!empty($_POST['email'])) {

        $email = trim(strip_tags($_POST['email']));

        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            $generate = FALSE;
            $email_m = 'class="missing" ';
        }

    } else {
        $generate = FALSE;
        $email_m = 'class="missing" ';
    }


    if($generate) {

        // Creates the two unique (random) ProfileUUIDs
        // I do not know if this is correct, but it works

        $uuid1 = NULL;
        $uuid2 = NULL;

        foreach(array(8,4,4,4,12) as $seg) {
            $i = 1;
            while($i <= $seg) {
                $uuid1 .= strtoupper(dechex(rand(0,15)));
                $uuid2 .= strtoupper(dechex(rand(0,15)));
                $i++;
            }

            $uuid1 .= "-";
            $uuid2 .= "-";
        }

        $uuid1 = substr($uuid1, 0, -1);
        $uuid2 = substr($uuid2, 0, -1);

    }

} else {

    $generate = FALSE;

}


$html = <<< EOHTMLF
<!DOCTYPE html>
<html lang="en-CA">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<meta name="author" content="jonathan j davis@snickers.org">
<title>iOS Email Profile Generator</title>
<link rel="stylesheet" href="default.css">
</head>
<body>
<h1>iOS Email Profile Generator v1.0</h1>
<p>This page generates an email profile specific to Snickers.org which can be installed on iOS devices.</p>
<p>Please enter the following information:</p>
<form name="profile_info" action="{$_SERVER['PHP_SELF']}" method="post">
<ul>
    <li>
        <label for="name" {$name_m}>Display Name:</label>
        <input type="text" name="name" value="{$name}">(e.g. Jon Nistor)
    </li>
    <li>
        <label for="username" {$username_m}>Username:</label>
        <input type="text" name="username" value="{$username}">
    </li>
    <li>
        <label for="email" {$email_m}>Email Address:</label>
        <input type="email" name="email" size="32" value="{$email}">
    </li>
    <li class="submit">
         <input type="submit" value="Generate Your Profile" >
    </li>
</ul>
</form>
<p>Note: You will be prompted for your password when you install the profile on your device.</p>
<p>To remove these profiles from your iOS device, go to Settings => General => Profiles, then click on the name of the profile you want to remove, and then click the Remove botton.</p>
<p>Return to the <a href="http://www.snickers.org">Snickers.org</a> website.</p>
<p class="right">View on <a href="http://github.com/notdavis/iosmailprofile"><img src="http://www.snickers.org/~davis/GitHub-Mark-32px.png"></a></p>
</body>
</html>
EOHTMLF;

$xml = <<< EOXMLF
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>PayloadContent</key>
	<array>
		<dict>
			<key>EmailAccountDescription</key>
			<string></string>
			<key>EmailAccountName</key>
			<string>{$name}</string>
			<key>EmailAccountType</key>
			<string>EmailTypeIMAP</string>
			<key>EmailAddress</key>
			<string>{$email}</string>
			<key>IncomingMailServerAuthentication</key>
			<string>EmailAuthPassword</string>
			<key>IncomingMailServerHostName</key>
			<string>mail.snickers.org</string>
			<key>IncomingMailServerIMAPPathPrefix</key>
			<string>/</string>
			<key>IncomingMailServerPortNumber</key>
			<integer>993</integer>
			<key>IncomingMailServerUseSSL</key>
			<true/>
			<key>IncomingMailServerUsername</key>
			<string>{$username}</string>
			<key>IncomingPassword</key>
			<string></string>
			<key>OutgoingMailServerAuthentication</key>
			<string>EmailAuthPassword</string>
			<key>OutgoingMailServerHostName</key>
			<string>mail.snickers.org</string>
			<key>OutgoingMailServerPortNumber</key>
			<integer>465</integer>
			<key>OutgoingMailServerUseSSL</key>
			<true/>
			<key>OutgoingMailServerUsername</key>
			<string>{$username}</string>
			<key>OutgoingPasswordSameAsIncomingPassword</key>
			<true/>
			<key>PayloadDescription</key>
			<string>Configures email account.</string>
			<key>PayloadDisplayName</key>
			<string>IMAP Account (Snickers Email Account)</string>
			<key>PayloadIdentifier</key>
			<string>org.snickers.email.profile.</string>
			<key>PayloadOrganization</key>
			<string>Snickers.org</string>
			<key>PayloadType</key>
			<string>com.apple.mail.managed</string>
			<key>PayloadUUID</key>
			<string>{$uuid1}</string>
			<key>PayloadVersion</key>
			<integer>1</integer>
			<key>PreventAppSheet</key>
			<false/>
			<key>PreventMove</key>
			<false/>
			<key>SMIMEEnabled</key>
			<false/>
		</dict>
	</array>
	<key>PayloadDescription</key>
	<string>Snickers.org Email Configuration Profile for iOS Devices</string>
	<key>PayloadDisplayName</key>
	<string>{$email} Profile</string>
	<key>PayloadIdentifier</key>
	<string>org.snickers.email.profile</string>
	<key>PayloadOrganization</key>
	<string>Snickers.org</string>
	<key>PayloadRemovalDisallowed</key>
	<false/>
	<key>PayloadType</key>
	<string>Configuration</string>
	<key>PayloadUUID</key>
	<string>{$uuid2}</string>
	<key>PayloadVersion</key>
	<integer>1</integer>
</dict>
</plist>
EOXMLF;

if($generate) {
    header("Content-type: text/plain");
    header('Content-Disposition: attachment; filename="snickers.mobileconfig"');
    echo $xml;
} else {
    echo $html;
}

?>