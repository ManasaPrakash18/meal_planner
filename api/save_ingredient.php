<?php
// api/save_ingredient.php - add single ingredient to existing meal
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

require_once __DIR__ . '/db.php';

if (!isset($conn) || !$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'No DB connection']);
    exit;
}

$meal_id = isset($_POST['meal_id']) ? (int)$_POST['meal_id'] : 0;
$ingredient_name = isset($_POST['ingredient_name']) ? trim($_POST['ingredient_name']) : '';

if ($meal_id <= 0 || $ingredient_name === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

$stmt = $conn->prepare('INSERT INTO ingredients (meal_id, ingredient_name) VALUES (?,?)');
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error']);
    exit;
}
$stmt->bind_param('is', $meal_id, $ingredient_name);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Insert failed']);
}
$stmt->close();
exit;

?>
