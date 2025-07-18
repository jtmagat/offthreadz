<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style>
    body {
      background-color: #0a0a0a;
      color: #fff;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background: #111;
      padding: 2rem;
      border-radius: 12px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.05);
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      letter-spacing: 2px;
      text-transform: uppercase;
    }

    input {
      width: 100%;
      padding: 0.8rem;
      margin-bottom: 1rem;
      border: none;
      border-radius: 8px;
      background: #222;
      color: #fff;
    }

    button {
      width: 100%;
      padding: 0.8rem;
      background-color: #e50914;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color: #b00610;
    }

    p {
      margin-top: 1rem;
      text-align: center;
    }

    a {
      color: #00bfff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Login</h2>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <?php if (isset($error)) echo "<p style='color: #e63946;'>$error</p>"; ?>
    <p>No account yet? <a href="register.php">Register</a></p>
  </div>
</body>
</html>
