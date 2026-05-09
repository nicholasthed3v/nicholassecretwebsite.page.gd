<?php
session_start();

// Check if admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    die('Unauthorized');
}

function getAccounts() {
    $accounts = [];
    if (file_exists('accounts.txt')) {
        $lines = file('accounts.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $parts = explode(':', $line);
            if (count($parts) >= 2) {
                $username = $parts[0];
                $password = $parts[1];
                $banned = isset($parts[2]) ? $parts[2] : 'false';
                $admin = isset($parts[3]) ? $parts[3] : 'false';
                $accounts[$username] = [
                    'password' => $password,
                    'banned' => $banned,
                    'admin' => $admin
                ];
            }
        }
    }
    return $accounts;
}

function saveAccounts($accounts) {
    $content = '';
    foreach ($accounts as $username => $data) {
        $content .= $username . ':' . $data['password'] . ':' . $data['banned'] . ':' . $data['admin'] . "\n";
    }
    file_put_contents('accounts.txt', $content);
}

// Handle ban user
if (isset($_POST['ban_user'])) {
    $username = $_POST['username'];
    $accounts = getAccounts();
    if (isset($accounts[$username])) {
        $accounts[$username]['banned'] = 'true';
        saveAccounts($accounts);
        echo "User banned successfully";
    } else {
        echo "User not found";
    }
    exit;
}

// Handle unban user
if (isset($_POST['unban_user'])) {
    $username = $_POST['username'];
    $accounts = getAccounts();
    if (isset($accounts[$username])) {
        $accounts[$username]['banned'] = 'false';
        saveAccounts($accounts);
        echo "User unbanned successfully";
    } else {
        echo "User not found";
    }
    exit;
}

// Handle feature toggle
if (isset($_POST['toggle_feature'])) {
    $feature = $_POST['feature'];
    $status = $_POST['status'];
    
    $features = [];
    if (file_exists('features.txt')) {
        $lines = file('features.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($f, $s) = explode(':', $line);
            $features[$f] = $s;
        }
    }
    
    $features[$feature] = $status;
    
    $content = '';
    foreach ($features as $f => $s) {
        $content .= $f . ':' . $s . "\n";
    }
    file_put_contents('features.txt', $content);
    
    echo "Feature updated successfully";
    exit;
}

// Handle character limit for hacker inject
if (isset($_POST['set_char_limit'])) {
    $limit = intval($_POST['limit']);
    if ($limit > 0 && $limit <= 200) {
        file_put_contents('char_limit.txt', $limit);
        echo "Character limit set to " . $limit;
    } else {
        echo "Invalid limit (must be 1-200)";
    }
    exit;
}

// Handle username character limit
if (isset($_POST['set_username_limit'])) {
    $limit = intval($_POST['limit']);
    if ($limit >= 3 && $limit <= 100) {
        file_put_contents('username_limit.txt', $limit);
        echo "Username limit set to " . $limit;
    } else {
        echo "Invalid limit (must be 3-100)";
    }
    exit;
}

// Get all users
if (isset($_GET['get_users'])) {
    header('Content-Type: application/json');
    echo json_encode(getAccounts());
    exit;
}

// Get features
if (isset($_GET['get_features'])) {
    $features = [];
    if (file_exists('features.txt')) {
        $lines = file('features.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($f, $s) = explode(':', $line);
            $features[$f] = $s;
        }
    } else {
        // Default features - all except login
        $features = [
            'weatherprank' => 'enabled',
            'calculator' => 'enabled',
            'downloadram' => 'enabled',
            'loading' => 'enabled',
            'virusscan' => 'enabled',
            'magic8ball' => 'enabled',
            'button' => 'enabled',
            'asciiart' => 'enabled',
            'excuses' => 'enabled',
            'hacker' => 'enabled',
            'cube3d' => 'enabled',
            'squarejump' => 'enabled'
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($features);
    exit;
}

// Get char limit for hacker inject
if (isset($_GET['get_char_limit'])) {
    $limit = 100; // default
    if (file_exists('char_limit.txt')) {
        $limit = intval(file_get_contents('char_limit.txt'));
    }
    echo $limit;
    exit;
}

// Get username character limit
if (isset($_GET['get_username_limit'])) {
    $limit = 50; // default
    if (file_exists('username_limit.txt')) {
        $limit = intval(file_get_contents('username_limit.txt'));
    }
    echo $limit;
    exit;
}
?>