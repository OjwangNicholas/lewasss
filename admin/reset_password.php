<?php
// reset_password.php: Handles password reset via emailed token
session_start();
$reset_error = '';
$reset_success = '';
$admin_file = __DIR__ . '/admin.json';
$token_file = __DIR__ . '/reset_token.json';
$admin_data = json_decode(file_get_contents($admin_file), true);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
    $token = $_POST['token'];
    $new1 = $_POST['new_password'] ?? '';
    $new2 = $_POST['confirm_password'] ?? '';
    $reset_data = file_exists($token_file) ? json_decode(file_get_contents($token_file), true) : null;
    if (!$reset_data || $reset_data['token'] !== $token || time() > $reset_data['expires']) {
        $reset_error = 'Invalid or expired reset token.';
    } elseif (strlen($new1) < 6) {
        $reset_error = 'New password must be at least 6 characters.';
    } elseif ($new1 !== $new2) {
        $reset_error = 'Passwords do not match.';
    } else {
        $admin_data['password'] = password_hash($new1, PASSWORD_DEFAULT);
        file_put_contents($admin_file, json_encode($admin_data, JSON_PRETTY_PRINT));
        @unlink($token_file);
        $reset_success = 'Password has been reset. You may now <a href="login.php">login</a>.';
    }
} else {
    // Validate token from GET
    $token = $_GET['token'] ?? '';
    $reset_data = file_exists($token_file) ? json_decode(file_get_contents($token_file), true) : null;
    if (!$reset_data || $reset_data['token'] !== $token || time() > $reset_data['expires']) {
        $reset_error = 'Invalid or expired reset token.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Admin Password</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>body { background: #f8f9fa; }</style>
</head>
<body>
<div class="container" style="max-width:400px;margin-top:80px;">
    <div class="card p-4">
        <h3 class="mb-3">Reset Admin Password</h3>
        <?php if ($reset_error): ?><div class="alert alert-danger"><?php echo $reset_error; ?></div><?php endif; ?>
        <?php if ($reset_success): ?><div class="alert alert-success"><?php echo $reset_success; ?></div><?php endif; ?>
        <?php if (!$reset_success && !$reset_error || ($reset_error && isset($_POST['token']))): ?>
        <form method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning btn-block">Set New Password</button>
        </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
