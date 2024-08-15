<?php
// --------------------------------------------------------
//
// j0rpi_GameDB
//
// File: admin/add.php
// Purpose: Simple form to add new game to database
//
// --------------------------------------------------------
session_start();

// Define db
$db = new SQLite3('../games.db');

// --------------------------------------------------------
//
// Define Config And Skin Config
//
// --------------------------------------------------------
include('../include/config.php');

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ../admin/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new SQLite3('../games.db');
    $stmt = $db->prepare('INSERT INTO games (title, year, desc, rating, vod, cover, genre, completed, speedrun, platform) VALUES (:title, :year, :desc, :rating, :vod, :cover, :genre, :completed, :speedrun, :platform)');
    $stmt->bindValue(':title', $_POST['title'], SQLITE3_TEXT);
    $stmt->bindValue(':rating', $_POST['rating'], SQLITE3_FLOAT);
	$stmt->bindValue(':year', $_POST['year'], SQLITE3_TEXT);
	$stmt->bindValue(':desc', $_POST['desc'], SQLITE3_TEXT);
    $stmt->bindValue(':vod', $_POST['vod'], SQLITE3_TEXT);
    $stmt->bindValue(':cover', $_POST['cover'], SQLITE3_TEXT);
    $stmt->bindValue(':genre', $_POST['genre'], SQLITE3_TEXT);
    $stmt->bindValue(':completed', $_POST['completed'], SQLITE3_INTEGER);
    $stmt->bindValue(':speedrun', $_POST['speedrun'], SQLITE3_INTEGER);
	$stmt->bindValue(':platform', $_POST['platform'], SQLITE3_TEXT);
    $stmt->execute();

    header('Location: ../');
    exit;
}
?>
<?php $years = range(1960, strftime("%Y", time())); ?>
<?php $ratings = range(1, 10); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Game</title>
    <style>
        body {
            height: 100%;
			margin: 0;
			font-family: Bahnschrift;
			color: white;
			background-attachment: fixed;
			background-image: url("../styles/<?php echo $style ?>/img/bg.jpg");
			background-size: cover;
			
        }
		* {
			box-sizing: border-box;
		}
        .container {
			text-align: left;
			width: 20%;
			
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			background-color: rgb(0,0,0); /* Fallback color */
			background-color: rgba(0,0,0, 0.5); /* Black w/opacity/see-through */
		
			margin-top: 15px;
        }
        h2 {
            margin-top: 0;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
        }
        input[type="text"], input[type="number"], select {
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
			font-family: Bahnschrift;
        }
        button {
            padding: 10px;
            background-color: #0080ff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
			font-family: Bahnschrift;
			margin-bottom: 10px;
        }
        button:hover {
            background-color: #45a049;
		}
		button:focus {
            background-color: #45a049;
		}
		.bg-text {
			background-color: rgb(0,0,0); /* Fallback color */
			background-color: rgba(0,0,0, 0.1); /* Black w/opacity/see-through */
			
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			z-index: 2;
			width: 100%;
			height: 100%;
			padding: 20px;
			border: 0px solid black;

		}
		.optiondisabled {
			color: #0080ff;
		}
		.cover-search {
            display: flex;
            align-items: center;
            margin-top: 50px;
        }
        .cover-search input {
            flex: 1;
            margin-right: 10px;
			
        }
		
		input:focus {
			outline: none;
            flex: 1;
            margin-right: 10px;
			
        }
        .cover-results img {
            width: 120px;
            height: 148px;
            cursor: pointer;
            margin-right: 10px;
			margin-bottom: 5px;
        }
		.cover-results img:hover {
            border: 3px solid black;
			outline:3px solid #0080ff;  
			box-shadow: 0 0 5px 5px #48abe0;
			  z-index: 45;
		}
        .cover-results {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;
			margin-left: 10px;
			justify-content: center;
			
        }
		.modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
			margin-top: -100px;
            width: 100%;
            height: auto;
            overflow: hidden;
			
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: rgba(0, 0, 0, 0.99);
			
            justify-content: center;
            align-items: center;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 100%;
            max-width: 850px;
			max-height: 850px;
            border-radius: 0px;
			margin-bottom: 50px;
        }
        .close {
			width: 100%;
            color: white;;
            float: right;
            font-size: 16px;
            font-weight: bold;
			border-bottom: 1px solid #333333;
        }
        .close:hover,
        .close:focus {
            text-decoration: none;
			
        }
		.modalCloseButton:hover{
			color: #0080ff;
			cursor: pointer;
		}
		.a2 {
			text-decoration: none;
			color: white;
        }
    </style>
	
</head>
<body>
<center>
<div class="bg-text">
    <div class="container">
       
        <form method="POST">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
			
			<label for="genre">Genre</label>
            <select name="genre">
		<?php 
		// Querystring for non-odd genres
		$cat_query = 'SELECT cat_name FROM categories WHERE odd_genre = 0';
		
		// Querystring for the ODD genres
		$cat_query_odd = 'SELECT cat_name FROM categories WHERE odd_genre = 1';
		
		// Query the db for non-odd genres
		$cat_stmt = $db->prepare($cat_query);
		$cat_results = $cat_stmt->execute();
		
		// Copy pasta same, but use ODD query string
		$cat_stmt_odd = $db->prepare($cat_query_odd);
		$cat_results_odd = $cat_stmt_odd->execute();
		?>
		<?php
		// --------------------------------------------------------
		//
		// Query the non-odd genres
		//
		// --------------------------------------------------------
		?>
	    <?php while ($option = $cat_results->fetchArray(SQLITE3_ASSOC)): ?>
			<option><?php echo $option['cat_name']; ?></option>
		<?php endwhile; ?>
			<option disabled>Other Genres ...</option>
		<?php
		// --------------------------------------------------------
		//
		// Query the odd genres
		//
		// --------------------------------------------------------
		?>
		<?php while ($option_odd = $cat_results_odd->fetchArray(SQLITE3_ASSOC)): ?>
			<option><?php echo $option_odd['cat_name']; ?></option>
		<?php endwhile; ?>
			</select>
            
			<label for="year">Year</label>
			<select id="year" name="year">
            <?php foreach($years as $year) : ?>
			<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
			<?php endforeach; ?>
			</select>
			
			<label for="platform">Platform</label>
            <select id="platform" name="platform">
                <?php 
		
				// Querystring for platforms
				$platform_query = 'SELECT * FROM platforms ORDER BY name';
		
				// Setup connection
				$platform_stmt = $db->prepare($platform_query);
				$platform_results = $platform_stmt->execute();
				?>
				<?php
				// --------------------------------------------------------
				//
				// Query all platforms
				//
				// --------------------------------------------------------
				?>
				<?php while ($platform = $platform_results->fetchArray(SQLITE3_ASSOC)): ?>
					<option value="<?php echo $platform['short_prefix']; ?>"><?php echo $platform['name']; ?></option>
				<?php endwhile; ?>
            </select>
			
            <label for="rating">Rating:</label>
            <select id="rating" name="rating">
            <?php foreach($ratings as $rating) : ?>
			<option value="<?php echo $rating; ?>"><?php echo $rating; ?></option>
			<?php endforeach; ?>
			</select>
			
			<label for="desc">Review</label>
            <input type="text" id="desc" name="desc" required>
            
            <label for="vod">VOD</label>
            <input type="text" id="vod" name="vod" required>

            <label for="cover">Cover URL</label>
                <input type="text" id="cover" name="cover" required>
			<button type="button" style="width: 100%;" onclick="openModal()">🔍 Search</button>


            <label for="completed">Completed</label>
            <select id="completed" name="completed">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>

            <label for="speedrun">Speedrun</label>
            <select id="speedrun" name="speedrun">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
            
			
			
            <button type="submit">✔️ Add Game</button>
        </form>
</div>
<!-- The Modal -->
    <div id="coverModal" class="modal">
        <div class="modal-content">
            <span class="close"><span style="float:left" id="modalTitle">Search for cover art</span><span class="modalCloseButton" onclick="closeModal()" style="float:right; font-size: 24px; margin-bottom: 5px;">&times;</span></span>
            <div class="cover-search">
                <input type="text" id="cover-search-input" placeholder="Search for cover art...">
                <button type="button" onclick="searchCovers()" style="margin-bottom: 10px;">🔍 Search</button>
            </div>
            <div class="cover-results" id="cover-results"></div>
			<center>
			<div style="display: inline-flex; margin-top: 25px;">
				<img src="https://www.igdb.com/packs/static/igdbLogo-bcd49db90003ee7cd4f4.svg" style="width: 57px;" /><span style="margin-top: 5px; margin-left: 5px;">Powered by <a class="a2" href="https://www.igdb.com/">IGDB API</a></span>
			</div>
			</center>
        </div>
    </div>

    <script>
        // Open the modal
        function openModal() {
            document.getElementById('coverModal').style.display = 'block';
        }

        // Close the modal
        function closeModal() {
            document.getElementById('coverModal').style.display = 'none';
        }

        // Search for cover art
        function searchCovers() {
            const query = document.getElementById('cover-search-input').value;
            fetch(`coversearch.php?query=${query}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Network response was not ok ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const resultsContainer = document.getElementById('cover-results');
                    resultsContainer.innerHTML = '';
                    if (data.error) {
                        resultsContainer.innerHTML = `<p>Error: ${data.error}</p>`;
                        if (data.status_code) {
                            resultsContainer.innerHTML += `<p>Status Code: ${data.status_code}</p>`;
                        }
                        if (data.response) {
                            resultsContainer.innerHTML += `<p>Response: ${data.response}</p>`;
                        }
                        return;
                    }
                    if (data.data.length === 0) {
                        resultsContainer.innerHTML = '<p>😔 No matching cover found.</p>';
                        return;
                    }
                    data.data.forEach(game => {
                        if (game.cover) {
                            const img = document.createElement('img');
                            img.src = game.cover.url.replace('thumb', 'cover_big'); // Use higher resolution image
                            img.onclick = () => {
                                document.getElementById('cover').value = img.src;
                                closeModal();
                            };
                            resultsContainer.appendChild(img);
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching cover art:', error);
                    const resultsContainer = document.getElementById('cover-results');
					const modalContainer = document.getElementById('modal');
                    resultsContainer.innerHTML = `<p>Error: ${error.message}</p>`;
					modalContainer.innerHTML = `<p>Error: ${error.message}</p>`;
                });
        }

        // Close the modal when clicking outside of the modal content
        window.onclick = function(event) {
            const modal = document.getElementById('coverModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</center>
</body>
</html>