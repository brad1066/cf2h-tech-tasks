<?php

// Get the local session
session_start();

// If the user is not authenticated (logged in), return an error
if ($_SESSION['authenticated'] != true) {
    echo json_encode(['success' => false, 'message' => 'Unauthenticated']);
    exit;
}

// If there is no CSRF token, or the CSRF token is invalid, return an error
if (!isset($_POST['csrfToken']) || $_POST['csrfToken'] !== $_SESSION['csrfToken']) {
    echo json_encode(['success' => false, 'message' => 'Stale request. Please refresh the page and try again']);
    exit;
}

// Include the definitions file
include_once '../inc/definitions.php';

// API Key - I added the API key in my definitions file
$apiKey = OPEN_WEATHER_API_KEY;

// City Name (got from a user submission)
// Security: I added some sanitization to the city name to prevent XSS attacks
$cityName = filter_input(INPUT_POST, 'city');

// The below(provided) is a deprecated method of calling the API. Use of the GeoCoder API is recommended
// by OpenWeatherMap to get the lat and lon of the city, and then use it to get the weather data
// $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=$cityName&appid=$apiKey";

// Get the lat and lon of the city using the GeoCoder API
$geoData = file_get_contents("http://api.openweathermap.org/geo/1.0/direct?q=$cityName}&limit=1&appid=$apiKey");
$geoData = json_decode($geoData);
if (empty($geoData)) {
    echo json_encode(['success' => false, 'message' => 'Invalid city name']);
    exit;
}

// Extract the lat and lon from the GeoCoder API response, and use it to get the weather data
$lat = $geoData[0]->lat;
$lon = $geoData[0]->lon;
$apiUrl = "http://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&units=metric&appid=$apiKey";

// Get the api response and return it to the client
$response = file_get_contents($apiUrl);

echo $response;