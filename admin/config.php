<?php
// --------------------------------------------------------
//
// j0rpi_GameDB
//
// File: admin/config.php
// Purpose: Manage configuration 
//
// --------------------------------------------------------

session_start();

// --------------------------------------------------------
//
// Check If User Is Authenticated. Otherwise Redirect To
// Login Page.
//
// --------------------------------------------------------
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) 
{
    header('Location: login.php');
    exit;
}
// --------------------------------------------------------
//
// Define Config And Skin Config
//
// --------------------------------------------------------
include('../include/config.php');
include('../include/functions.php');
include('../version.php');
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
    if (isset($_POST['update_config'])) {
        $stmt = $db->prepare('DELETE FROM categories WHERE id = :id');
        $stmt->bindValue(':id', $_POST['delete_id'], SQLITE3_INTEGER);
        $stmt->execute();
		$status = "Configuration was successfully saved to the database!";
		echo "<div class='errorbar' style='background-color: darkgreen;'><span style='margin-bottom: 2px'>✔️ " . $status . "</div>";
    }else {
		$status = "There was an error trying to update the configuration!";
		echo "<div class='errorbar' style='background-color: darkred;'><span style='margin-bottom: 2px'>⛔️ " . $status . "</div>";
	}
}
// --------------------------------------------------------
//
// Run Query, And Select ALL From 'configuration' Table
//
// --------------------------------------------------------

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles/<?php getConfigVar('style') ?>/style.admin.css">
</head>
<body>
<div class='bg-text'><br>
<?php
// --------------------------------------------------------
//
// Configuration 
//
// --------------------------------------------------------
?>
    <h1>Admin Dashboard<span style="float:right; font-size: 16px; font-weight: normal;">Logged in As <strong><?php echo $_SESSION['admin_username']; ?></strong></span><br><span style="float:left; font-size: 12px; font-weight: normal;"></span><span style="float:right; font-size: 12px; font-weight: normal;"><a href="password.php" style="">Change Password</a> | <a href="logout.php" style="">Logout</a></h1><br>
    <div class="form-container">
	<span style="font-size: 16px;"><a href="index.php">Admin Dashboard</a> > Configuration</span>
	<br><br><div class='thindivider'></div>
<form method='POST'>
	<?php
		$result = $db->query('SELECT * FROM configuration');
		$config = $result->fetchArray(SQLITE3_ASSOC);
	?>
	<label for='language'>Language</label><br>
	<select name="language">
		<?php echo getLanguageOptions(); ?>
	</select>
	
	<label for="style-select">Select Style</label>
    <select id="style-select" name="style">
        <?php echo getStyleOptions(); ?>
    </select>

	<label for='headerTitle'>Header Title</label>
    <input type='text' id='headerTitle' name='headerTitle' value='<?php echo $config["headerTitle"]; ?>' required>

	<label for='listMax'>Max Entrys Per List</label>
    <input type='number' id='listMax' name='listMax' min='1' max='50' value='<?php echo $config["listMax"]; ?>' required>
	
	<label for='minSelectableYear'>Minimum Selectable Year For Filters</label>
    <input type='text' id='minSelectableYear' name='minSelectableYear' value='<?php echo $config["minSelectableYear"]; ?>' required>
	
	<label for='useRatingIcons'>Use Rating Icons</label>
    <select id='useRatingIcons' name='useRatingIcons'>
		<option value='<?php echo $config["useRatingIcons"]; ?>'><?php echo $config["useRatingIcons"]; ?></option>
		<option value='0'>0</option>
		<option value='1'>1</option>
	</select>
	<label for='usePlatformIcons'>Use Platform Icons</label>
    <select id='usePlatformIcons' name='usePlatformIcons'>
		<option value='<?php echo $config["usePlatformIcons"]; ?>'><?php echo $config["usePlatformIcons"]; ?></option>
		<option value='0'>0</option>
		<option value='1'>1</option>
	</select>
 
    <label for='IGDB_clientID'>IGDB Client ID</label>
    <input type='text' id='IGDB_clientID' name='IGDB_clientID' value='<?php echo $config["IGDB_clientID"]; ?>' required>
	
    <label for='IGDB_clientSecret'>IGDB Client Secret</label>
    <input type='text' id='IGDB_clientSecret' name='IGDB_clientSecret' value='<?php echo $config["IGDB_clientSecret"]; ?>' required>
	
    <label for='IGDB_accessToken'>IGDB Access Token [<a href="igdb_token.php">Generate Token</a>]</label>
    <input type='text' id='IGDB_accessToken' name='IGDB_accessToken' value='<?php echo $config["IGDB_accessToken"]; ?>' required>
            
    <button type='submit' class="submit" style="">✔️ Save Configuration</button>
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
		<center><a href='https://github.com/j0rpi/GameDB' style='text-decoration: none; border-bottom: 1px dotted white;'>GameDB</a> made with ❤️ by j0rpi<br><span style="font-weight: 200; font-size: 12px;"><?php echo $version; ?></span></center> 
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
