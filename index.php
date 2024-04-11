<?php
// Initialise the session object
session_start();

// If the user is not authenticated, redirect to the login page
if ($_SESSION['authenticated'] != true) {
    header('Location: /login');
    exit;
}

// Get the user object from the session
$user = (object) $_SESSION['user'];
?>

<?php include 'partials/page_init.php'; ?>

<!-- Page content -->

<body>
    <main>
        <h1>Dashboard - <?php echo $user->first_name ?> <?php echo $user->last_name ?></h1>

        <button id="logout-button" class="button">Logout</button>
        <a href="/weather" class="button">Weather</a>
    </main>
</body>

<?php include 'partials/page_exit.php'; ?>