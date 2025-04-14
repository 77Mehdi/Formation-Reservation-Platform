<?php
session_start();
include 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = 'user';

    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role)
                                VALUES (:username, :email, :password, :role)");

        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password,
            ':role' => $role
        ]);

        // On successful registration, set session variables and redirect to the home page.
        $_SESSION['user'] = [
            'username' => $username,
            'email'    => $email,
            'role'     => $role
        ];

        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $message = "Email already exists!";
        } else {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            background: linear-gradient(to right, #f4f5ec, #78853f);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .register-container {
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

<div class="register-container">
    <h3 class="text-center mb-4">User Registration</h3>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Enter username" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter email" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn btn-custom w-100">Register</button>
    </form>

    <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>
</div>

</body>
</html>
