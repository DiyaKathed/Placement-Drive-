<?php
header("Content-Type: application/json");
require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"] ?? 0;

if (!$id) {
    echo json_encode(["success" => false, "message" => "Application ID is required."]);
    exit;
}

$sql = "DELETE FROM application WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Application deleted successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete application."]);
}

$stmt->close();
$conn->close();
?>
