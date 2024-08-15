<?php
// refresh_token.php

$clientId = 'bhakr82r25allnvftg3ib9kjzwyrq0';
$clientSecret = '2pukbxhkgdfq220lokej9s5unidg4k';

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
    echo 'Access token refreshed successfully';
} else {
    echo 'Failed to refresh access token';
    echo '<pre>' . print_r($data, true) . '</pre>';
}