<?php
header("Content-Type: application/json");
require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid request data."]);
    exit;
}

$company_name = trim($data["company_name"] ?? "");
$job_role = trim($data["job_role"] ?? "");
$package = $data["package"] ?? "";
$eligible_branches = trim($data["eligible_branches"] ?? "");
$min_cgpa = $data["min_cgpa"] ?? "";
$max_backlogs = $data["max_backlogs"] ?? "";
$drive_date = $data["drive_date"] ?? "";
$status = $data["status"] ?? "Open";

if ($company_name === "" || $job_role === "" || $package === "" ||
    $eligible_branches === "" || $min_cgpa === "" || $max_backlogs === "" || $drive_date === "") {
    echo json_encode(["success" => false, "message" => "All required fields must be filled."]);
    exit;
}

if (!is_numeric($package) || $package <= 0) {
    echo json_encode(["success" => false, "message" => "Package must be greater than 0."]);
    exit;
}

if (!is_numeric($min_cgpa) || $min_cgpa < 0 || $min_cgpa > 10) {
    echo json_encode(["success" => false, "message" => "CGPA must be between 0 and 10."]);
    exit;
}

if (!is_numeric($max_backlogs) || $max_backlogs < 0) {
    echo json_encode(["success" => false, "message" => "Backlogs must be a positive whole number."]);
    exit;
}

$sql = "INSERT INTO table_drives
        (company_name, job_role, package, eligible_branches, min_cgpa, max_backlogs, drive_date, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdssdss",
    $company_name, $job_role, $package, $eligible_branches,
    $min_cgpa, $max_backlogs, $drive_date, $status
);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Placement drive added successfully.",
        "id" => $conn->insert_id
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add placement drive."]);
}

$stmt->close();
$conn->close();
?>
