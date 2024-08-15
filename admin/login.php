<?php
// --------------------------------------------------------
//
// j0rpi_GameDB
//
// File: admin/login.php
// Purpose: Login page
//
// --------------------------------------------------------

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new SQLite3('../games.db');

    $stmt = $db->prepare('SELECT * FROM admins WHERE username = :username');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();

    $admin = $result->fetchArray(SQLITE3_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username; // Optionally store username
        header('Location: ../index.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body, html {
			height: 100%;
			margin: 0;
			font-family: Bahnschrift;
			color: white;
			background-attachment: fixed;
			background-image: url("bg.jpg");
			background-size: cover;
		}

		* {
			box-sizing: border-box;
		}
        .login-container {
			width: 20%;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			background-color: rgb(0,0,0);
			background-color: rgba(0,0,0, 0.6); 
			backdrop-filter: blur(1px);
			margin-top: 0px;
        }
        .login-container h2 {
            margin-top: 0;
        }
        .login-container form {
            display: flex;
            flex-direction: column;
			
        }
        .login-container input {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
			font-family: Bahnschrift;
			
        }
        .login-container button {
            padding: 10px;
            background-color: #0080ff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
			font-family: Bahnschrift;
        }
        .login-container button:hover {
            background-color: #45a049;
        }
        .login-container .error {
            color: red;
            margin-bottom: 10px;
        }
		.bg-text {
			
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			z-index: 2;
			width: 100%;
			height: 100%;
			padding: 20px;
			border: 0px solid black;
			justify-content: center;
		}
		h2 {
			margin-top: 200px;
		}
    </style>
</head>
<body>
<div class="bg-image"></div>
<div class="bg-text">
<center>
<img src="../styles/default/img/logo.png" style="margin-top: 150px;" />
    <div class="login-container">
        <h2 style="font-size: 16px;">Admin Login</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</center>
</div>
</body>
</html>
