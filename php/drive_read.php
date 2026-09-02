<?php
header("Content-Type: application/json");
require_once "db.php";

$id = $_GET["id"] ?? null;
$status = $_GET["status"] ?? null;

if ($id) {
    $sql = "SELECT id, company_name, job_role, package, eligible_branches,
                   min_cgpa, max_backlogs, drive_date, applied_at, status
            FROM table_drives WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $drive = $result->fetch_assoc();

    if ($drive) {
        echo json_encode(["success" => true, "data" => $drive]);
    } else {
        echo json_encode(["success" => false, "message" => "Drive not found."]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

$sql = "SELECT id, company_name, job_role, package, eligible_branches,
               min_cgpa, max_backlogs, drive_date, applied_at, status
        FROM table_drives";

if ($status) {
    $sql .= " WHERE status = ?";
    $sql .= " ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql .= " ORDER BY id DESC";
    $result = $conn->query($sql);
}

$drives = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $drives[] = $row;
    }
}

echo json_encode(["success" => true, "data" => $drives]);
$conn->close();
?>
