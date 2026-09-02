<?php
header("Content-Type: application/json");
require_once "db.php";

// This endpoint receives multipart/form-data (because of the resume file),
// so fields come from $_POST and the file comes from $_FILES.

$drive_id = $_POST["drive_id"] ?? 0;
$student_name = trim($_POST["student_name"] ?? "");
$roll_number = trim($_POST["roll_number"] ?? "");
$batch = trim($_POST["batch"] ?? "");
$category = trim($_POST["category"] ?? "");
$cgpa = $_POST["cgpa"] ?? "";
$percentage = $_POST["percentage"] ?? "";
$backlogs = $_POST["backlogs"] ?? "";

// =====================================================
// REQUIRED FIELD CHECK
// =====================================================
if (!$drive_id || $student_name === "" || $roll_number === "" || $batch === "" ||
    $category === "" || $cgpa === "" || $percentage === "" || $backlogs === "") {
    echo json_encode(["success" => false, "message" => "All application fields are required."]);
    exit;
}

if (!is_numeric($cgpa) || $cgpa < 0 || $cgpa > 10) {
    echo json_encode(["success" => false, "message" => "CGPA must be between 0 and 10."]);
    exit;
}

if (!is_numeric($percentage) || $percentage < 0 || $percentage > 100) {
    echo json_encode(["success" => false, "message" => "Percentage must be between 0 and 100."]);
    exit;
}

if (!is_numeric($backlogs) || $backlogs < 0) {
    echo json_encode(["success" => false, "message" => "Backlogs cannot be negative."]);
    exit;
}

// =====================================================
// RESUME FILE VALIDATION
// =====================================================
if (!isset($_FILES["resume"]) || $_FILES["resume"]["error"] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "message" => "Resume file is required."]);
    exit;
}

$originalName = $_FILES["resume"]["name"];
$fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

if ($fileExt !== "pdf") {
    echo json_encode(["success" => false, "message" => "Resume must be a PDF file."]);
    exit;
}

// =====================================================
// GET DRIVE ELIGIBILITY CRITERIA
// =====================================================
$sql = "SELECT min_cgpa, max_backlogs FROM table_drives WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $drive_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Placement drive not found."]);
    exit;
}

$drive = $result->fetch_assoc();
$minimumCgpa = $drive["min_cgpa"];
$maximumBacklogs = $drive["max_backlogs"];

// =====================================================
// ELIGIBILITY CHECK
// =====================================================
if ($cgpa < $minimumCgpa || $backlogs > $maximumBacklogs) {
    echo json_encode([
        "success" => false,
        "eligible" => false,
        "message" => "Not eligible: requires CGPA >= $minimumCgpa and backlogs <= $maximumBacklogs."
    ]);
    exit;
}

// =====================================================
// SAVE RESUME FILE
// =====================================================
$uploadDir = __DIR__ . "/../uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$safeRoll = preg_replace("/[^A-Za-z0-9_-]/", "", $roll_number);
$storedName = $safeRoll . "_" . time() . ".pdf";
$destination = $uploadDir . $storedName;

if (!move_uploaded_file($_FILES["resume"]["tmp_name"], $destination)) {
    echo json_encode(["success" => false, "message" => "Failed to save resume file."]);
    exit;
}

$resume = "uploads/" . $storedName;

// =====================================================
// INSERT APPLICATION
// =====================================================
$sql = "INSERT INTO application
        (drive_id, student_name, roll_number, batch, category, cgpa, percentage, backlogs, resume)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issssddis",
    $drive_id, $student_name, $roll_number, $batch, $category,
    $cgpa, $percentage, $backlogs, $resume
);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "eligible" => true,
        "message" => "Application submitted successfully.",
        "id" => $conn->insert_id
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to submit application."]);
}

$stmt->close();
$conn->close();
?>
