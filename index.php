<?php
require 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setClientId('YOUR_CLIENT_ID');
$client->setClientSecret('YOUR_CLIENT_SECRET');
$client->setRedirectUri('http://localhost/google-callback.php');
$client->addScope('email');
$client->addScope('profile');

$loginUrl = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Google Login Example</title>
</head>
<body>
    <h2>Login with Google</h2>
    <a href="<?php echo $loginUrl; ?>">Login with Google</a>
</body>
</html>