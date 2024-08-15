<?php
// --------------------------------------------------------
//
// j0rpi_GameDB
//
// File: config.php
// Purpose: Configuration file for GameDB
//
// --------------------------------------------------------

// Include config
include('include/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Games List</title>
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
			background-color: rgba(0,0,0, 0.25);
			backdrop-filter: blur(10px);
            padding: 20px;
            margin-top: 0px;
			width: 30%;
			height: auto;
			text-align: left;
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
			height: auto;
			padding: 0px;
			border: 0px solid black;	
			backdrop-filter: blur(10px);
		}
		h2 {
			border-bottom: 1px solid rgba(255,255,255,0.1);
		}
    </style>
<body>
		<div class='bg-text'>
			<center>
			<img src="../logo.png" style="margin-top: 150px;" />
				<div class='login-container'>
					<h1>Configuration</h1><br>
					<h2>Database</h2>
					<p>SQL Engine = <?php echo $sqlEngine; ?></p>
					<p>Host = <?php echo $host; ?></p>
					<p>Username = <?php echo $username; ?></p>
					<p>Password = <?php echo $password; ?></p>
					<p>Database = <?php echo $db ?>;<br><br></p>
					<h2>Site Settings</h2>
					<p>Header Title = <?php echo $headerTitle; ?></p>
					<p>Max Displayed Games = <?php echo $listMax; ?></p>
					<p>Mininum Selectable Year = <?php echo $minSelectableYear; ?></p>
					<h2>LightBox Settings</h2>
					<p>Display Game Title = <?php echo $lightboxDisplayTitle; ?></p>
					<p>Display Game Genre = <?php echo $lightboxDisplayGenre; ?></p>
					<p>Display Game Year = <?php echo $lightboxDisplayYear; ?></p>
					<p>Display Game Developer = <?php echo $lightboxDisplayDeveloper; ?></p>
					<p>Display Game VOD Link = <?php echo $lightboxDisplayVODLink; ?></p> 
					<p>Display Game Platform = <?php echo $lightboxDisplayPlatform ?></p>
				</div>
			</center>
		</div>
</center>
</div>