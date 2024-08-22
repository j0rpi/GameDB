<?php
// --------------------------------------------------------
//
// j0rpi_GameDB
//
// File: include/functions.php
// Purpose: Make stuff easier right.. 
//
// --------------------------------------------------------
//
// Get game title by game ID
// 
// Usage: displayGameID(id) 
//
// --------------------------------------------------------
function displayGameByID($gameID)
{
    $db = new SQLite3($_SERVER['DOCUMENT_ROOT'] . '/gamedb/games.db', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $stmt = $db->prepare('SELECT "title" FROM "games" WHERE "id" = :gameID');
    $stmt->bindValue(':gameID', $gameID, SQLITE3_INTEGER);
    $game = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    print_r($game['title']);
}

// --------------------------------------------------------
//
// Get game cover by game ID
// 
// Usage: displayGameCoverByID(id) 
//
// --------------------------------------------------------
function displayGameCoverByID($gameID, $width)
{
    $db = new SQLite3($_SERVER['DOCUMENT_ROOT'] . '/gamedb/games.db', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $stmt = $db->prepare('SELECT "cover" FROM "games" WHERE "id" = :gameID');
    $stmt->bindValue(':gameID', $gameID, SQLITE3_INTEGER);
    $cover = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    print_r($cover['cover']);
}

// --------------------------------------------------------
//
// Get configuration value from database
// 
// Usage: getConfigVar(keyname) 
//
// --------------------------------------------------------
function getConfigVar($config_var)
{
    $db = new SQLite3($_SERVER['DOCUMENT_ROOT'] . '/gamedb/games.db', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $stmt = $db->prepare('SELECT :config_var FROM "configuration"');
    $stmt->bindValue(':config_var', $config_var, SQLITE3_TEXT);
    $var = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    print_r($var[$config_var]);
}

// --------------------------------------------------------
//
// Refresh IGDB access token
// 
// Usage: refreshIGDBKey(client id, client secret) 
//
// --------------------------------------------------------
function refreshIGDBKey($clientID, $clientSecret)
{
    $clientId = getConfigVar("IGDB_clientID");
    $clientSecret = getConfigVar("IGDB_clientSecret");

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://id.twitch.tv/oauth2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'grant_type' => 'client_credentials'
    ]));

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['access_token'])) {
        file_put_contents('access_token.txt', $data['access_token']);
        echo 'Access token generated successfully. Please delete access_token.txt after setting IGDB_accessToken.';
    } else {
        echo 'Failed to refresh access token';
        echo '<pre>' . print_r($data, true) . '</pre>';
    }
}

// --------------------------------------------------------
//
// Wipe the database
// 
// Usage: wipeDB() 
//
// --------------------------------------------------------
function wipeDB()
{
    $db = new SQLite3($_SERVER['DOCUMENT_ROOT'] . '/gamedb/games.db', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['wipe_db'])) {
            $stmt1 = $db->prepare('DELETE FROM categories');
            $stmt2 = $db->prepare('DELETE FROM platforms');
            $stmt3 = $db->prepare('DELETE FROM games');
            $stmt1->execute();
            $stmt2->execute();
            $stmt3->execute();
            $status = "The database was completely wiped.";
            echo "<div class='errorbar' style='background-color: darkgreen;'><span style='margin-bottom: 2px'>✔️ " . $status . "</div>";
        } else {
            $status = "There was an error trying to delete/update selected post.";
            echo "<div class='errorbar' style='background-color: darkred;'><span style='margin-bottom: 2px'>⛔️ " . $status . "</div>";
        }
    }
}
?>
