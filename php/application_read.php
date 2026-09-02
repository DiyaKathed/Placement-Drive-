<?php
header("Content-Type: application/json");
require_once "db.php";

$roll_number = $_GET["roll_number"] ?? null;
$drive_id = $_GET["drive_id"] ?? null;

$sql = "SELECT
            a.id, a.drive_id, d.company_name, d.job_role, d.min_cgpa,
            a.student_name, a.roll_number, a.batch, a.category,
            a.cgpa, a.percentage, a.backlogs, a.resume,
            a.applied_at, a.status
        FROM application a
        INNER JOIN table_drives d ON a.drive_id = d.id";

$conditions = [];
$types = "";
$params = [];

if ($roll_number) {
    $conditions[] = "a.roll_number = ?";
    $types .= "s";
    $params[] = $roll_number;
}

if ($drive_id) {
    $conditions[] = "a.drive_id = ?";
    $types .= "i";
    $params[] = $drive_id;
}

if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY a.id DESC";

if (count($params) > 0) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

echo json_encode(["success" => true, "data" => $applications]);
$conn->close();
?>
