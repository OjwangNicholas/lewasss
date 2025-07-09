<?php
// Simple login for admin (hardcoded for demo)
session_start();
$login_error = '';
$reset_success = '';
// Load admin credentials from JSON file
$admin_file = __DIR__ . '/admin.json';

if (!file_exists($admin_file)) {
    // Create default admin user if file doesn't exist
    $default_admin = [
        'username' => 'admin',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'email' => 'ojwangn56@gmail.com'
    ];
    file_put_contents($admin_file, json_encode($default_admin, JSON_PRETTY_PRINT));
}
$admin_data = json_decode(file_get_contents($admin_file), true);
// Always set email to the requested one
if ($admin_data['email'] !== 'ojwangn56@gmail.com') {
    $admin_data['email'] = 'ojwangn56@gmail.com';
    file_put_contents($admin_file, json_encode($admin_data, JSON_PRETTY_PRINT));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Password reset logic
    if (isset($_POST['reset_password'])) {
        $current = $_POST['current_password'] ?? '';
        $new1 = $_POST['new_password'] ?? '';
        $new2 = $_POST['confirm_password'] ?? '';
        if (!password_verify($current, $admin_data['password'])) {
            $login_error = 'Current password is incorrect.';
        } elseif (strlen($new1) < 6) {
            $login_error = 'New password must be at least 6 characters.';
        } elseif ($new1 !== $new2) {
            $login_error = 'New passwords do not match.';
        } else {
            $admin_data['password'] = password_hash($new1, PASSWORD_DEFAULT);
            file_put_contents($admin_file, json_encode($admin_data, JSON_PRETTY_PRINT));
            $reset_success = 'Password has been reset successfully.';
        }
    } elseif (isset($_POST['forgot_password'])) {
        $email = $_POST['email'] ?? '';
        if (strtolower($email) !== strtolower($admin_data['email'])) {
            $login_error = 'Email address not found.';
        } else {
            $token = bin2hex(random_bytes(32));
            $expires = time() + 3600; // 1 hour
            $reset_data = [
                'token' => $token,
                'expires' => $expires
            ];
            file_put_contents(__DIR__ . '/reset_token.json', json_encode($reset_data));
            $reset_link = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/reset_password.php?token=' . $token;
            $subject = 'Lewa Admin Password Reset';
            $message = "Click the link to reset your password: $reset_link\nThis link will expire in 1 hour.";
            $headers = 'From: noreply@' . $_SERVER['HTTP_HOST'];
            // Send email (for local dev, this may not work without SMTP)
            mail($admin_data['email'], $subject, $message, $headers);
            $reset_success = 'A password reset link has been sent to your email.';
        }
    } else {
        // Login logic
        $user = $_POST['username'] ?? '';
        $pass = $_POST['password'] ?? '';
        if (
            isset($admin_data['username'], $admin_data['password']) &&
            $user === $admin_data['username'] &&
            password_verify($pass, $admin_data['password'])
        ) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: index.php');
            exit();
        } else {
            $login_error = 'Invalid credentials.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>body { background: #f8f9fa; }</style>
<script>
// Disable right-click, F12, and common inspect shortcuts
document.addEventListener('contextmenu', function(e) { e.preventDefault(); });
document.addEventListener('keydown', function(e) {
    // F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+Shift+C
    if (
        e.keyCode === 123 || // F12
        (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74 || e.keyCode === 67)) || // Ctrl+Shift+I/J/C
        (e.ctrlKey && e.keyCode === 85) // Ctrl+U
    ) {
        e.preventDefault();
        return false;
    }
});
</script>
</head>
<body>
<div class="container" style="max-width:400px;margin-top:80px;">
    <div class="card p-4 mb-3">
        <h3 class="mb-3">Admin Login</h3>
        <?php if ($login_error): ?><div class="alert alert-danger"><?php echo $login_error; ?></div><?php endif; ?>
        <?php if ($reset_success): ?><div class="alert alert-success"><?php echo $reset_success; ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <div class="text-center mt-2">
            <a href="#" onclick="document.getElementById('forgot-form').style.display='block';return false;">Forgot Password?</a>
        </div>
        <form method="POST" id="forgot-form" style="display:none; margin-top:15px;">
            <div class="form-group">
                <label>Enter your admin email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" name="forgot_password" value="1" class="btn btn-info btn-block">Send Reset Link</button>
        </form>
    </div>
    <div class="card p-4">
        <h5 class="mb-3">Reset Admin Password</h5>
        <form method="POST">
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" name="reset_password" value="1" class="btn btn-warning btn-block">Reset Password</button>
        </form>
    </div>
</div>
</body>
</html>
