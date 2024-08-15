<?php
// --------------------------------------------------------
//
// j0rpi_GameDB
//
// File: admin/platforms.php
// Purpose: Manage platforms
//
// --------------------------------------------------------

session_start();

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
// For when we edit/delete posts
$status = "";
// --------------------------------------------------------
//
// Define Database
//
// --------------------------------------------------------
$db = new SQLite3('../games.db');
// --------------------------------------------------------
//
// Setup POST Variables
//
// --------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $stmt = $db->prepare('DELETE FROM platforms WHERE id = :id');
        $stmt->bindValue(':id', $_POST['delete_id'], SQLITE3_INTEGER);
        $stmt->execute();
		$status = $_POST['name'] . " (" . $_POST['short_prefix'] . ") was deleted from the database.";
		echo "<div class='errorbar' style='background-color: darkgreen;'><span style='margin-bottom: 2px'>✔️ " . $status . "</div>";
    } elseif (isset($_POST['update_id'])) {
        $stmt = $db->prepare('UPDATE platforms SET name = :name, short_prefix = :short_prefix WHERE id = :id');
        $stmt->bindValue(':id', $_POST['update_id'], SQLITE3_INTEGER);
        $stmt->bindValue(':name', $_POST['name'], SQLITE3_TEXT);
		$stmt->bindValue(':short_prefix', $_POST['short_prefix'], SQLITE3_INTEGER);
		$status = $_POST['name'] . " (" . $_POST['short_prefix'] . ") was updated.";
		echo "<div class='errorbar' style='background-color: darkgreen;'><span style='margin-bottom: 2px'>✔️ " . $status . "</div>";
        $stmt->execute();
    } elseif (isset($_POST['add_plat'])) {
		$stmt = $db->prepare('INSERT INTO platforms (name, short_prefix) VALUES (:name, :short_prefix)');
		$stmt->bindValue(':name', $_POST['name_add'], SQLITE3_TEXT);
		$stmt->bindValue(':short_prefix', $_POST['short_prefix_add'], SQLITE3_TEXT);
		$status = $_POST['name_add'] . " was added to the database.";
		echo "<div class='errorbar' style='background-color: darkgreen;'><span style='margin-bottom: 2px'>✔️ " . $status . "</div>";
		$stmt->execute();
	} else {
		$status = "There was an error trying to delete/update selected post.";
		echo "<div class='errorbar' style='background-color: darkred;'><span style='margin-bottom: 2px'>⛔️ " . $status . "</div>";
	}
}
// --------------------------------------------------------
//
// Run Query, And Select ALL From 'platforms' Table
//
// --------------------------------------------------------
$result = $db->query('SELECT * FROM platforms');
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
			margin-top: 30px;
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
		.info-container {
			background-color: rgb(0,0,0); 
			background-color: rgba(0,0,0, 0.3); 
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
			width: 24%;
			float: right;
        }
		.form-container {
			background-color: rgb(0,0,0); 
			background-color: rgba(0,0,0, 0.3); 
			width: 75%;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .form-container h2 {
            margin-top: 0;
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
			width: 60%;
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
		.info-container input, .form-container select {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
			font-family: Bahnschrift;
			background-color: rgb(0,0,0); 
			background-color: rgba(0,0,0, 0.1); 
			color:white;
        }
		.info-container button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
			font-family: Bahnschrift;
			width: 100%;
        }
        .info-container button:hover {
            background-color: #45a049;
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
?>
<?php
// --------------------------------------------------------
//
// Platform List
//
// --------------------------------------------------------
?>
    <h1>Admin Dashboard <span style="float:right; font-size: 16px; font-weight: normal;">Logged in As <strong><?php echo $_SESSION['admin_username']; ?></strong></span><br><span style="float:left; font-size: 12px; font-weight: normal;"></span><span style="float:right; font-size: 12px; font-weight: normal;"><a href="password.php" style="">Change Password</a> | <a href="logout.php" style="">Logout</a></h1><br>
    <?php
	// --------------------------------------------------------
	//
	// The little info box to the right of all posts
	//
	// --------------------------------------------------------
	?>
	<div class="info-container">
		<p>How to</p>
		<div class="thindivider"></div>
		<p>✏️ Update Platform</p>
		<p>❌ Delete Platform</p>
		<p style="margin-top: 25px;"></p>
		<p>Add New Platform</p>
		<div class="thindivider"></div>
		<form method="POST">
            <label for="name_add">Platform Name</label>
            <input type="text" id="name_add" name="name_add" required>
			<label for="short_prefix_add">Short Prefix</label>
			<input type="text" id="short_prefix_add" name="short_prefix_add" required>
            <button type="submit" name="add_plat">✔️ Add Platform</button>
        </form>
		</p>
		<p>Note: When adding new platforms, GameDB will try to look for each
		platforms icon by its short prefix that is specified.</p>
	</div>
	<div class="form-container">
	<?php
	// --------------------------------------------------------
	//
	// Setup Table Headers
	//
	// --------------------------------------------------------
	?>
        <span class="button-row-text"><a href="index.php">Admin Dashboard</a> > Manage Platforms</span>
		<table>
            <thead>
            <tr style="font-size: 14px;">
				<th style="text-align: center; width: 10px">ID</th>
				<th style="text-align: center; width: 32px">Icon</th>
				<th style="text-align: center; width: 50px">Name</th>
				<th style="text-align: center; width: 10px">Short Prefix</th>
				<th style="text-align: center; width: 75px">Actions</th>
                </tr>
            </thead>
            <tbody>
				<?php
				// --------------------------------------------------------
				//
				// Connect To Database And Populate The Table
				//
				// --------------------------------------------------------
				?>
                <?php while ($platform = $result->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <form method="POST">
						<td style="text-align: center;" name="id"><?= $platform['id'] ?></td>
						<?php
						// --------------------------------------------------------
						//
						// If platform does not have an existing platform icon, display a default one
						//
						// --------------------------------------------------------
						?>
						<?php 
						if (file_exists("../styles/default/img/platform_icons/" . $platform['short_prefix'] . ".png")) {
							echo "<td style='text-align: center;' name='platform_icon'><img style='width: 32px;' src='../styles/default/img/platform_icons/" . $platform['short_prefix'] . ".png' /></td>";
						}
						else {
							echo "<td style='text-align: center;' name='platform_icon'><img style='width: 32px;' src='../styles/default/img/platform_icons/unknown.png' /></td>";
						}
						?>
						<td><input type="text" name="name" value="<?= $platform['name'] ?>"></td>
						<td style="text-align: center;"><input type="text" name="short_prefix" value="<?= $platform['short_prefix'] ?>" style="width: 75px;"></td>
					<?php
					// --------------------------------------------------------
					//
					// Action Buttons
					//
					// --------------------------------------------------------
					?>
                        <td>
                            <button type="submit" style="vertical-align: middle; margin-bottom: 8px;" name="update_id" title="Update this game" value="<?= $platform['id'] ?>">✏️</button>
                            <button type="submit" style="background-color: rgba(255,0,0,0.6); vertical-align: middle; margin-bottom: 8px;" title="Delete" name="delete_id" value="<?= $platform['id'] ?>">❌</button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
		<?php
		// --------------------------------------------------------
		//
		// If Database Is Empty, Tell The User
		//
		// --------------------------------------------------------
		?>
		<?php 
		$count = $db->querySingle("SELECT COUNT(*) as total from platforms");
		if ($count < 1) {
			echo "<center>No platforms were found in the database.</center>";
		}
		?>
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
