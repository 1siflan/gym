<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['staff_id'])) {
    header('Location: ../login.php');
    exit;
}

// Pagination setup
$limit = 20;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch total subscriptions count
$totalResult = $conn->query("SELECT COUNT(*) FROM subscription");
$totalSubscriptions = $totalResult ? (int)$totalResult->fetch_row()[0] : 0;
$totalPages = ceil($totalSubscriptions / $limit);

// Fetch subscriptions with user info (join user table)
$stmt = $conn->prepare("
    SELECT s.id, s.user_id, s.plan_name, s.status, s.start_date, s.end_date, u.full_name, u.phone_number
    FROM subscription s
    JOIN user u ON s.user_id = u.id
    ORDER BY s.start_date DESC
    LIMIT ? OFFSET ?
");
$stmt->bind_param('ii', $limit, $offset);
$stmt->execute();
$subscriptions = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Subscriptions - Admin - PowerGym</title>
    <link rel="stylesheet" href="../assets/css/main.css" />
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }
        th, td {
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #f5f5f5;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }
        .pagination a {
            padding: 0.5rem 0.75rem;
            background: var(--primary, #3a86ff);
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .pagination .current-page {
            background: #1e3a8a;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <?php include_once '../includes/header.php'; ?>

    <main class="container admin-subscriptions">
        <h1>Manage Subscriptions</h1>

        <table>
            <thead>
                <tr>
                    <th>Subscription ID</th>
                    <th>User</th>
                    <th>Phone</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($subscriptions->num_rows === 0): ?>
                    <tr><td colspan="8">No subscriptions found.</td></tr>
                <?php else: ?>
                    <?php while ($sub = $subscriptions->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($sub['id']) ?></td>
                            <td><?= htmlspecialchars($sub['full_name']) ?></td>
                            <td><?= htmlspecialchars($sub['phone_number']) ?></td>
                            <td><?= htmlspecialchars($sub['plan_name']) ?></td>
                            <td><?= htmlspecialchars($sub['status']) ?></td>
                            <td><?= htmlspecialchars($sub['start_date']) ?></td>
                            <td><?= htmlspecialchars($sub['end_date']) ?></td>
                            <td>
                                <a href="subscription_edit.php?id=<?= $sub['id'] ?>">Edit</a> | 
                                <a href="subscription_delete.php?id=<?= $sub['id'] ?>" onclick="return confirm('Are you sure you want to delete this subscription?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($totalPages > 1): ?>
            <nav class="pagination" aria-label="Subscription pages">
                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <a href="?page=<?= $p ?>" class="<?= $p === $page ? 'current-page' : '' ?>"><?= $p ?></a>
                <?php endfor; ?>
            </nav>
        <?php endif; ?>
    </main>

    <?php include_once '../includes/footer.php'; ?>
</body>
</html>
