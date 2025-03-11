# Google Login with PHP - OAuth 2.0 Integration

This guide provides step-by-step instructions to integrate **Google Login** using OAuth 2.0 in PHP applications.

---

## 🚀 Prerequisites
- PHP 7.4 or higher
- Google Cloud Project with OAuth 2.0 Credentials
- Composer installed

---

## 📄 Step 1: Google Cloud Project Setup
1. Go to the [Google Cloud Console](https://console.cloud.google.com/).
2. Create a new project (if not already created).
3. Navigate to **APIs & Services** → **Credentials**.
4. Click **Create Credentials** → **OAuth Client ID**.
5. Set the **Authorized redirect URI** to your callback URL (e.g., `http://localhost/google-callback.php`).
6. Copy your **Client ID** and **Client Secret**.

---

## 💻 Step 2: Install Dependencies

Install the **Google API Client Library** using Composer:
```bash
composer require google/apiclient
```

---

## 🛠️ Step 3: Code Implementation

### `index.php` — Login Page
```php
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
```

### `google-callback.php` — Callback Handler
```php
<?php
require 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setClientId('YOUR_CLIENT_ID');
$client->setClientSecret('YOUR_CLIENT_SECRET');
$client->setRedirectUri('http://localhost/google-callback.php');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    $_SESSION['user'] = [
        'id' => $userInfo->id,
        'name' => $userInfo->name,
        'email' => $userInfo->email,
        'picture' => $userInfo->picture
    ];

    header('Location: dashboard.php');
    exit;
} else {
    echo "Error during authentication!";
}
?>
```

### `dashboard.php` — Display User Info
```php
<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <img src="<?php echo htmlspecialchars($user['picture']); ?>" alt="Profile Picture">
    <br><br>
    <a href="logout.php">Logout</a>
</body>
</html>
```

### `logout.php` — Logout Script
```php
<?php
session_start();
session_destroy();
header('Location: index.php');
exit;
?>
```

---

## 📋 Step 4: Testing the Integration

1. Start your PHP server with the command:
   ```bash
   php -S localhost:8000
   ```
2. Open your browser and visit:
   **`http://localhost:8000/index.php`**
3. Click "Login with Google" and proceed with the OAuth flow.
4. After authentication, you should see your profile details on the **Dashboard**.

---

## ✅ Best Practices
- Use `.env` files to securely store your **Client ID** and **Client Secret**.
- Regularly rotate your OAuth credentials for enhanced security.
- Implement session expiry and CSRF protection for better security.

---

## ❓ Troubleshooting
- Ensure your **Client ID** and **Client Secret** are correctly added in the code.
- Verify the **Redirect URI** matches the URI set in your Google Cloud Console.
- Ensure the Google API Client Library is properly installed using Composer.

If you face any issues, feel free to raise an issue in this repository. 😊

