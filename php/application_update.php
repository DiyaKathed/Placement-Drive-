<?php
header("Content-Type: application/json");
require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $data["id"] ?? 0;
$status = trim($data["status"] ?? "");

if (!$id || $status === "") {
    echo json_encode(["success" => false, "message" => "Application ID and status are required."]);
    exit;
}

$sql = "UPDATE application SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Application updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update application."]);
}

$stmt->close();
$conn->close();
?>
