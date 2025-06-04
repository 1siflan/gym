<footer class="site-footer">
    <div class="container">
        <p>&copy; <?= date('Y') ?> PowerGym. All rights reserved.</p>
        <nav class="footer-nav" aria-label="Footer Navigation">
            <a href="/index.php">Home</a> |
            <a href="/login.php">Login</a> |
            <a href="/register.php">Register</a> |
            <a href="/contact.php">Contact Us</a>
        </nav>
    </div>
</footer>

<style>
.site-footer {
    background-color: #2b2d42;
    color: #ffffffcc;
    text-align: center;
    padding: 1rem 0;
    font-size: 0.9rem;
    margin-top: 3rem;
}
.site-footer a {
    color: #3a86ff;
    text-decoration: none;
    margin: 0 0.5rem;
}
.site-footer a:hover,
.site-footer a:focus {
    text-decoration: underline;
}
</style>
