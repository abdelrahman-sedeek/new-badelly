<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <p>Hello,</p>
    <p>You've requested to reset your password.</p>
    <p>Use the following reset code: {{ $resetCode }}</p>
    <p>Click the link below to reset your password:</p>
    <a href="{{ $resetLink }}">Reset Password</a>
    <p>If you didn't request a password reset, you can ignore this email.</p>
    <p>Thank you!</p>
</body>
</html>
