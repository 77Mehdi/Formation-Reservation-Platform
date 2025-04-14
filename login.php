<?php
session_start();
include 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            // Redirect to home or dashboard
            header("Location: index.php");
            exit;
        } else {
            $message = "Invalid email or password!";
        }

    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            background: linear-gradient(to right, #f4f5ec, #78853f);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-container {
            max-width: 450px;
            margin: 40px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .btn-custom {
            background-color: #78853f;
            border-color: #78853f;
            color: #fff;
        }

        .btn-custom:hover {
            background-color: #5e6b30;
            border-color: #5e6b30;
        }

        .message {
            margin-top: 15px;
            text-align: center;
            color: #4e5a1c;
            font-weight: 500;
        }
    </style>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="login-container">
    <h3 class="text-center mb-4">User Login</h3>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter email" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn btn-custom w-100">Login</button>
    </form>

    <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>

    <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>
</div>

</body>
</html>
