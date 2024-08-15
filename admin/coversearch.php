<?php
// search_covers.php

header('Content-Type: application/json');

// Replace with your IGDB credentials
$clientId = 'bhakr82r25allnvftg3ib9kjzwyrq0';
$clientSecret = '2pukbxhkgdfq220lokej9s5unidg4k';
$accessToken = '5t05ostkazvfzdk3zdp3hw94801yul';

// Fetch the search query
$query = isset($_GET['query']) ? $_GET['query'] : '';

if (!$query) {
    echo json_encode(['error' => 'No query provided']);
    exit();
}

// Initialize cURL session
$ch = curl_init();

// IGDB
$url = 'https://api.igdb.com/v4/games';

$headers = [
    'Client-ID: ' . $clientId,
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
];

$data = "fields name,cover.url; search \"$query\"; limit 18;";

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// Execute cURL request
$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo json_encode(['error' => curl_error($ch)]);
    curl_close($ch);
    exit();
}

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Check if the response code is not 200 (success)
if ($http_code != 200) {
    echo json_encode(['error' => 'Request failed', 'status_code' => $http_code, 'response' => $response]);
    exit();
}

// Decode the response for further debugging
$responseData = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'JSON decode error', 'response' => $response]);
    exit();
}

// Output the response with the query included for debugging
echo json_encode(['query' => $query, 'data' => $responseData, 'raw_response' => $response]);
