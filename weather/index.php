<?php
// Get the local session 
session_start();

$_SESSION['$csrfToken'] = bin2hex(random_bytes(32));

// If the user is not authenticated (logged in), redirect to the login page
// Change 1: Redirect to the login page with a redirect parameter to redirect back to the weather page after logging in
// This stops unauthorised users from being able to access the data
if ($_SESSION['authenticated'] != true) {
    header('Location: /login?redirect=weather');
    exit;
}
?>

<?php include '../partials/page_init.php'; ?>

<body>
    <main>
        <h1>Weather Lookup</h1>

        <!-- Change 2: Added a form to submit the city name to the server -->
        <form id="weather-form" class="card" method="post">
            <input type="text" name="city" id="city" placeholder="City" />
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor">
                    <path
                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                </svg>
            </button>

            <input type="hidden" value="<?php echo $_SESSION['csrfToken']; ?>" name="csrf-token" id="csrf-token">
        </form>

        <div? id="weather-card" class="card">
            <h2 class="card-header">Weather in <span id="location-name">no location searched</span></h2>
            <p>Weather: <span id="weather-output">-</span></p>
            <p>Temperature: <span id="temp-output">-&deg;</span></p>
        </div>
    </main>
</body>

<?php include '../partials/page_exit.php'; ?>