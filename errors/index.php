<?php
// --------------------------------------------------------
//
// j0rpi_GameDB
//
// File: crate_database.php
// Purpose: Creates SQLite3 Database
//
// Note: Edit admin username/password before running this script..
//
// --------------------------------------------------------

// Admin username/password
$admin_username = ('admin');
$admin_password = password_hash('admin', PASSWORD_DEFAULT);

$error = "";

// Check if database exists already
if (file_exists('../games.db')){
	$error = "GameDB seems to be installed already..";
}
else {
	// Define new database
	$db = new SQLite3('../games.db');
}

// Create games table
$db->exec('CREATE TABLE IF NOT EXISTS games (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
	year TEXT,
    rating REAL,
	desc TEXT,
    vod TEXT,
    cover TEXT,
    genre TEXT,
    completed INTEGER,
    speedrun INTEGER,
	platform TEXT
)');

// Create admins table
$db->exec('CREATE TABLE IF NOT EXISTS admins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
)');

$db->exec('CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cat_name TEXT NOT NULL UNIQUE,
    odd_genre INTEGER NOT NULL
)');

$db->exec('CREATE TABLE IF NOT EXISTS platforms (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    short_prefix TEXT NOT NULL
)');

// Insert sample admin account
$db->exec("INSERT INTO admins (username, password) VALUES ('$admin_username', '$admin_password')");

// All done
echo "<!DOCTYPE html>
		<html lang='en'>
		<head>
		<meta charset='UTF-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'>
		<title>GameDB :: Create Database</title><style>
        body, html {
			height: 100%;
			margin: 0;
			font-family: Bahnschrift;
			color: white;
			background: black;
		}

		* {
			box-sizing: border-box;
		}
		
        .login-container {
			width: 30%;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			background-color: rgb(0,0,0);
			background-color: rgba(0,0,0, 0.0); 
			backdrop-filter: blur(0px);
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
		.login-container a {
			color:#0080ff;
		}
		.bg-image {
			background-image: url('../styles/default/img/bg.jpg');	
			
			height: 100%; 		
			background-size: cover;
			border: 0px solid black;
		}
		.bg-text {
			backdrop-filter: blur(0px);
			background-color: rgb(0,0,0); 
			background-color: rgba(0,0,0, 0.4); 
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
	<body>
		<div class='bg-image'></div>
		<div class='bg-text'>
			<center>
			<img src='../styles/default/img/logo.png' style='margin-top: 150px;' />
				<div class='login-container'>";
					if ($error == '') {
						echo "
						Database setup complete.<br><br>
					<a href='../admin/login.php'>Login to Admin Dashboard</a><br><br>
					<strong>REMEMBER TO REMOVE INSTALL DIRECTORY!</strong>";
					}
					else{
						echo $error;
					}
					echo "
				</div>
			</center>
		</div>
	</div>
	</body>
	</html>";
?>