<?php
// Set session cookie parameters
ini_set('session.cookie_lifetime', 86400);
ini_set('session.cookie_path', '/');
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');

session_start();

// Redirect if not POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

// Create accounts file if needed
if (!file_exists('accounts.txt')) {
    file_put_contents('accounts.txt', '');
}

function getAccounts() {
    $accounts = [];
    if (file_exists('accounts.txt')) {
        $lines = file('accounts.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $parts = explode(':', $line);
            if (count($parts) >= 2) {
                $accounts[$parts[0]] = [
                    'password' => $parts[1],
                    'banned' => isset($parts[2]) ? $parts[2] : 'false',
                    'admin' => isset($parts[3]) ? $parts[3] : 'false'
                ];
            }
        }
    }
    return $accounts;
}

function saveAccount($username, $password) {
    file_put_contents('accounts.txt', $username . ':' . $password . ':false:false' . "\n", FILE_APPEND);
}

// Handle Guest Mode
if (isset($_POST['guest'])) {
    $_SESSION['guest_mode'] = true;
    header('Location: index.html');
    exit;
}

// Handle Registration
if (isset($_POST['register'])) {
    $username = trim($_POST['reg_username']);
    $password = trim($_POST['reg_password']);
    $confirm = trim($_POST['reg_confirm']);
    
    if (empty($username) || empty($password)) {
        header('Location: login.html?error=Fill in all fields');
        exit;
    }
    
    if ($password !== $confirm) {
        header('Location: login.html?error=Passwords do not match');
        exit;
    }
    
    $accounts = getAccounts();
    if (isset($accounts[$username])) {
        header('Location: login.html?error=Username already exists');
        exit;
    }
    
    saveAccount($username, $password);
    $_SESSION['logged_in_user'] = $username;
    $_SESSION['is_admin'] = false;
    header('Location: index.html');
    exit;
}

// Handle Login
if (isset($_POST['login'])) {
    $username = trim($_POST['login_username']);
    $password = trim($_POST['login_password']);
    
    if (empty($username) || empty($password)) {
        header('Location: login.html?error=Fill in all fields');
        exit;
    }
    
    $accounts = getAccounts();
    
    if (!isset($accounts[$username])) {
        header('Location: login.html?error=User not found');
        exit;
    }
    
    if ($accounts[$username]['banned'] === 'true') {
        header('Location: login.html?error=Account banned');
        exit;
    }
    
    if ($accounts[$username]['password'] !== $password) {
        header('Location: login.html?error=Wrong password');
        exit;
    }
    
    $_SESSION['logged_in_user'] = $username;
    $_SESSION['is_admin'] = ($accounts[$username]['admin'] === 'true');
    header('Location: index.html');
    exit;
}

// Fallback
header('Location: login.html');
exit;
?>