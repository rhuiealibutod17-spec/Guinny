<?php
include 'db_connect.php';
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
$data = json_decode(file_get_contents("php://input"), true);

// Required fields
$item = isset($data['item']) ? trim($data['item']) : '';
$desc = isset($data['desc']) ? trim($data['desc']) : '';
$price = isset($data['price']) ? floatval($data['price']) : 0;
$method = isset($data['method']) ? trim($data['method']) : '';
$trans_num = isset($data['trans_num']) ? trim($data['trans_num']) : null;

if (!$item || !$price || !$method) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing required payment data."]);
    exit;
}

// --- Prepare and execute SQL statement ---
$stmt = $conn->prepare("INSERT INTO payments (item, description, price, payment_method, transaction_number, paid_at) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("ssdss", $item, $desc, $price, $method, $trans_num);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Payment recorded successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to record payment."]);
}

$stmt->close();
$conn->close();
?>