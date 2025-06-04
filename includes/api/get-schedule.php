header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';

$day = $_GET['day'] ?? date('l');
$stmt = $conn->prepare("
    SELECT c.name, c.time, s.full_name AS trainer 
    FROM classes c
    JOIN staff s ON c.trainer_id = s.id
    WHERE c.day = ?
");
$stmt->bind_param("s", $day);
$stmt->execute();

echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));