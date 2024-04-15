<?php 
    session_start();
    $_SESSION['csrfToken'] = bin2hex(random_bytes(32));
?>

<?php include '../partials/page_init.php'; ?>

<body id="login-page">
    <main>
        <form id="login-form" class="card" method="post">
            <h1>Login</h1>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="username" />
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="password" />
            <input type="hidden" id="csrf-token" name="csrf-token" value="<?php echo $_SESSION['csrfToken']; ?>">
            <button type="submit">Submit</button>
        </form>
    </main>
</body>

<?php include '../partials/page_exit.php'; ?>