<?php
session_start();
require_once 'includes/config.php';

// Redirect logged-in users directly to profile/dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: members/profile.php');
    exit;
}

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF token validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Invalid request. Please try again.";
    } else {
        $phone = trim($_POST['phone'] ?? '');

        if (empty($phone)) {
            $errors[] = "Phone number is required.";
        } else {
            // Validate phone format (basic example)
            if (!preg_match('/^\+?[0-9\s\-]{10,}$/', $phone)) {
                $errors[] = "Please enter a valid phone number.";
            } else {
                // Prepare statement to fetch user by phone
                $sql = "SELECT id, full_name FROM user WHERE phone_number = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param('s', $phone);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows === 1) {
                        $user = $result->fetch_assoc();

                        // If you add passwords later, verify here with password_verify()
                        // For now, just login by phone number existence
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['full_name'] = $user['full_name'];

                        // Regenerate session ID to prevent fixation
                        session_regenerate_id(true);

                        header('Location: members/profile.php');
                        exit;
                    } else {
                        $errors[] = "No user found with that phone number.";
                    }
                    $stmt->close();
                } else {
                    $errors[] = "Database error. Please try again later.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Member Login - PowerGym</title>
<link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
<main class="form-container">
    <h1>Member Login</h1>

    <?php if (!empty($errors)): ?>
        <div class="error-messages" role="alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" novalidate>
        <label for="phone">Phone Number</label>
        <input
            type="tel"
            id="phone"
            name="phone"
            placeholder="+212612345678"
            required
            pattern="\+?[0-9\s\-]{10,}"
            aria-describedby="phoneHelp"
            autocomplete="tel"
        />
        <small id="phoneHelp">Enter your registered phone number</small>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</main>

<script>
    // Basic client-side validation feedback (optional)
    document.querySelector('form').addEventListener('submit', function(e) {
        const phoneInput = document.getElementById('phone');
        if (!phoneInput.checkValidity()) {
            alert('Please enter a valid phone number.');
            phoneInput.focus();
            e.preventDefault();
        }
    });
</script>
</body>
</html>
