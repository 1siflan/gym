<?php
session_start();
require_once '../includes/config.php';

// Simple admin check - adjust as per your auth system
if (!isset($_SESSION['staff_id'])) {
    header('Location: ../login.php');
    exit;
}

// Fetch admin info
$staffId = $_SESSION['staff_id'];
$stmt = $conn->prepare("SELECT full_name, role FROM staff WHERE id = ?");
$stmt->bind_param('i', $staffId);
$stmt->execute();
$staff = $stmt->get_result()->fetch_assoc();

if (!$staff) {
    session_destroy();
    header('Location: ../login.php');
    exit;
}

// Fetch summary data
function getCount(mysqli $conn, string $table, string $where = ''): int {
    $sql = "SELECT COUNT(*) FROM $table";
    if ($where) $sql .= " WHERE $where";
    $result = $conn->query($sql);
    return $result ? (int)$result->fetch_row()[0] : 0;
}

$totalUsers = getCount($conn, 'user');
$activeSubscriptions = getCount($conn, 'subscription', "status = 'Active'");
$totalStaff = getCount($conn, 'staff');

?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - PowerGym</title>
    <link rel="stylesheet" href="../assets/css/main.css" />
</head>
<body>
    <?php include_once '../includes/header.php'; ?>

    <main class="container admin-dashboard">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($staff['full_name']) ?> (<?= htmlspecialchars($staff['role']) ?>)</p>

        <section class="dashboard-summary">
            <div class="summary-card">
                <h2><?= $totalUsers ?></h2>
                <p>Total Users</p>
            </div>
            <div class="summary-card">
                <h2><?= $activeSubscriptions ?></h2>
                <p>Active Subscriptions</p>
            </div>
            <div class="summary-card">
                <h2><?= $totalStaff ?></h2>
                <p>Staff Members</p>
            </div>
        </section>

        <section class="admin-actions">
            <h2>Manage</h2>
            <ul>
                <li><a href="users.php">User Management</a></li>
                <li><a href="subscriptions.php">Subscriptions</a></li>
                <li><a href="staff.php">Staff</a></li>
                <li><a href="reports.php">Reports</a></li>
            </ul>
        </section>
    </main>

    <?php include_once '../includes/footer.php'; ?>
</body>
</html>
