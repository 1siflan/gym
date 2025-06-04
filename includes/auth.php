<?php
/**
 * includes/auth.php
 * 
 * Handles authentication checks and helper functions.
 */

session_start();

/**
 * Check if staff/admin is logged in
 * @return bool
 */
function isStaffLoggedIn(): bool
{
    return isset($_SESSION['staff_id']) && !empty($_SESSION['staff_id']);
}

/**
 * Check if user is logged in
 * @return bool
 */
function isUserLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Require staff login or redirect to login page
 * Call at the top of admin pages
 */
function requireStaffLogin(): void
{
    if (!isStaffLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * Require user login or redirect to login page
 * Call at the top of members pages
 */
function requireUserLogin(): void
{
    if (!isUserLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * Get logged-in staff ID or null if not logged in
 * @return int|null
 */
function getStaffId(): ?int
{
    return isStaffLoggedIn() ? (int)$_SESSION['staff_id'] : null;
}

/**
 * Get logged-in user ID or null if not logged in
 * @return int|null
 */
function getUserId(): ?int
{
    return isUserLoggedIn() ? (int)$_SESSION['user_id'] : null;
}
