<?php
session_start();
require_once __DIR__ . '/auth.php';
?>

<header class="site-header">
    <div class="container">
        <a href="/index.php" class="logo" aria-label="PowerGym Home">
            <img src="/assets/images/logo.svg" alt="PowerGym Logo" height="40" />
            <span class="sr-only">PowerGym</span>
        </a>

        <nav class="main-nav" aria-label="Primary Navigation">
            <ul>
                <li><a href="/index.php">Home</a></li>
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li><a href="/admin/dashboard.php">Admin Dashboard</a></li>
                        <li><a href="/admin/subscriptions.php">Manage Subscriptions</a></li>
                    <?php else: ?>
                        <li><a href="/members/profile.php">My Profile</a></li>
                        <li><a href="/members/subscriptions.php">My Subscriptions</a></li>
                    <?php endif; ?>
                    <li><a href="/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="/login.php">Login</a></li>
                    <li><a href="/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<style>
.site-header {
    background-color: #2b2d42;
    color: white;
    padding: 1rem 0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.site-header .container {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.logo img {
    vertical-align: middle;
}
.main-nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
}
.main-nav li {
    margin-left: 1.5rem;
}
.main-nav a {
    color: white;
    text-decoration: none;
    font-weight: 600;
}
.main-nav a:hover,
.main-nav a:focus {
    text-decoration: underline;
}
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0,0,0,0);
    border: 0;
}
@media (max-width: 768px) {
    .main-nav ul {
        flex-direction: column;
        background-color: #1b1d2a;
        position: absolute;
        top: 60px;
        right: 0;
        width: 200px;
        display: none; /* You can add JS to toggle this */
    }
}
</style>
