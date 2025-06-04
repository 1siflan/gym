function getTotalMembers(mysqli $conn): int {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user");
    $stmt->execute();
    return (int) $stmt->get_result()->fetch_row()[0];
}

function getActiveMembersCount(mysqli $conn): int {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM subscription WHERE status = 'Active'");
    $stmt->execute();
    return (int) $stmt->get_result()->fetch_row()[0];
}