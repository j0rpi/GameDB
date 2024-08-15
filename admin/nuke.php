<?php
// --------------------------------------------------------
//
// j0rpi_GameDB
//
// File: admin/index.php
// Purpose: Main administrator page
//
// --------------------------------------------------------

session_start();
// Include version info 
include('../version.php');
// --------------------------------------------------------
//
// Check If User Is Authenticated. Otherwise Redirect To
// Login Page.
//
// --------------------------------------------------------
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
// --------------------------------------------------------
//
// Define Config And Skin Config
//
// --------------------------------------------------------
include('../include/config.php');

// --------------------------------------------------------
//
// Setup POST Variables
//
// --------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['wipe_db'])) {
		$stmt = $db->prepare('DELETE FROM categories');
		$stmt2 = $db->prepare('DELETE FROM platforms');
		$stmt3 = $db->prepare('DELETE FROM games');
        $stmt->execute();
		$stmt2->execute();
		$stmt3->execute();
		$status = "The database was completely wiped.";
		echo "<div class='errorbar' style='background-color: darkgreen;'><span style='margin-bottom: 2px'>✔️ " . $status . "</div>";
	} else {
		$status = "There was an error trying to delete/update selected post.";
		echo "<div class='errorbar' style='background-color: darkred;'><span style='margin-bottom: 2px'>⛔️ " . $status . "</div>";
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body, html {
			height: 100%;
			margin: 0;
			font-size: 14px;
			font-family: Bahnschrift;
			color: white;
			background-attachment: fixed;
			background-image: url("../styles/<?php echo $style ?>/img/bg.jpg");
			background-size: cover;
		}

		* {
			box-sizing: border-box;
			
		}
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 0px solid #666666;
            text-align: left;
        }
        th {
            background-color: #0080ff;
            color: white;
			font-size: 14px;
        }
        
        a.add-game {
            text-decoration: none;
            padding: 8px 7px;
            background-color: #0080ff;
            color: white;
            border-radius: 4px;
			margin-top: 12px;
        }
        a.add-game:hover {
            background-color: #45a049;
        }
		
		tr {
			border-bottom: 1px solid rgba(255,255,255,0.1);
		}
        .pagination {
            display: flex;
            justify-content: center;
            margin: 10px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 16px;
            text-decoration: none;
            color: #4CAF50;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .pagination a.active {
            background-color: #0080ff;
            color: white;
            border: 1px solid #0080ff;
        }
        .pagination a:hover {
           background-color: #45a049;
        }
        .search-box, .filters {
            width: 28.5%;
           padding: 10px;
            display: inline-flex;
			font-family: Bahnschrift;
          
        }
        .search-box input, .filters select {
            width: 100%;
            margin: 0 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
			font-family: Bahnschrift;
        }
		.search-box{
			margin-left: -20px;	
			margin-right: -20px;
			font-family: Bahnschrift;
		}
		h1 {
			margin: 20px;
			color: white;
		}
		.form-container a {
			text-decoration: none;
		}
		.form-container a:hover {
			text-decoration: none;
			color: #0080ff;
		}
		.form-container {
			background-color: rgb(0,0,0); 
			background-color: rgba(0,0,0, 0.3); 
			
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .form-container h2 {
            margin-top: 0;
			font-size: 20px;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
			border: 0px solid black;

        }
        .form-container input, .form-container select {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
			font-family: Bahnschrift;
			background-color: rgb(0,0,0); 
			background-color: rgba(0,0,0, 0.1); 
			
			color:white;
        }
        .form-container button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
			font-family: Bahnschrift;
        }
        .form-container button:hover {
            background-color: #45a049;
        }
		.bg-text {
			
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			z-index: 1;
			width: 50%;
			height: 100%;
			padding: 0px;
			border: 0px solid black;
		}
		a {
			color: white;
		}
		.button-row {
			float:right;
			margin-bottom: 15px;
		}
		.button-row-text {
			float:left;
			font-size: 16px;
		}
		textarea{
			font-family: Bahnschrift;
			width: 350px;
			height: 100px;
			background-color: rgb(0,0,0); 
			background-color: rgba(0,0,0, 0.1); 

			color:white;
		}
		select option {
			background-color: #222222;
			appearance: none;
			-webkit-appearance: none;
			color: #fff;
			cursor: pointer;
		}
		th:first-child{
		border-radius: 8px 0px 0px 8px;
		}
		th:last-child{
		border-radius: 0px 8px 8px 0px;
		}
		.errorbar {
			width: 100%
			height: 25px;
			padding: 10px;
			font-family: Bahnschrift;
			font-size: 14px;
			background-color: darkred;
		}
		p {
			
		}
		.thindivider {
			border-bottom: 1px solid #666666;
			width: 100%;
			margin-top: -5px;
			margin-bottom: 8px;
		}
    </style>
</head>
<body>
<?php
// --------------------------------------------------------
//
// Display Warning If INSTALL Directory Is Still Present
// While Showing, Tell The User To Genererate IGDB Token
//
// --------------------------------------------------------
$folder = "../install";
$keygen = false;
if(is_dir($folder)) {
	echo "<div class='errorbar'><span style='margin-bottom: 2px'>⚠</span>️ Its strongly adviced to generate an <a href='../install/generate_token.php' style='text-decoration: none; border-bottom: 1px solid white;'>Access Token key for IGDB</a> now before removing the <strong>INSTALL</strong> folder for cover art search support!</div><br><br>";
	echo "<div class='bg-text' style='margin-top: 50px;'>";
	$keygen = true;
}
else
{
	echo "<div class='bg-text'>";
}
?><br>
<?php
// --------------------------------------------------------
//
// Dashboard container
//
// --------------------------------------------------------
?>
    <h1>Admin Dashboard<span style="float:right; font-size: 16px; font-weight: normal;">Logged in As <strong><?php echo $_SESSION['admin_username']; ?></strong></span><br><span style="float:left; font-size: 12px; font-weight: normal;"></span><span style="float:right; font-size: 12px; font-weight: normal;"><a href="password.php" style="">Change Password</a> | <a href="logout.php" style="">Logout</a></h1><br>
    <div class="form-container" style="text-align: center;">
	<span class="button-row-text"><a href="index.php">Admin Dashboard</a> > Nuke Database</span><br>
		<p>This will delete all games, categories and platforms from the database. <br>User accounts and GameDB configuration will not be wiped.<br><br>
		<strong style="color: rgba(255, 75, 75, 1)">Note:</strong> This action cannot be reversed. Are you sure?</p>
		<form method="POST">
			<center>
				<button type="submit" name="wipe_db" style="width: 50%;">Confirm Nuke</button>
			</center>
		</form>
	</div>
<?php
// --------------------------------------------------------
//
// Footer
//
// --------------------------------------------------------
?>
<div class="footerdivider">
	<div class="footer-content">
		<center>GameDB made with ❤️ by j0rpi<br><span style="font-weight: 200; font-size: 12px;"><?php echo $version; ?></span></center> 
	</div>
</div>
</div>
<?php
// --------------------------------------------------------
//
// End of file.
//
// --------------------------------------------------------
?>
</body>
</html>
