<?php
// api/save_meal.php - save a meal and optional ingredients
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

require_once __DIR__ . '/db.php';

if (!isset($conn) || !$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'No DB connection']);
    exit;
}

// Read POSTed values (application/x-www-form-urlencoded)
$day = isset($_POST['day']) ? trim($_POST['day']) : '';
$meal_type = isset($_POST['meal_type']) ? trim($_POST['meal_type']) : '';
$meal_name = isset($_POST['meal_name']) ? trim($_POST['meal_name']) : '';
$ingredients_json = isset($_POST['ingredients']) ? $_POST['ingredients'] : null;

if ($day === '' || $meal_type === '' || $meal_name === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

// Begin transaction to ensure meal + ingredients consistency
$conn->begin_transaction();
try {
    $stmt = $conn->prepare('INSERT INTO meals (day, meal_type, meal_name) VALUES (?,?,?)');
    if (!$stmt) throw new Exception('Prepare failed: ' . $conn->error);
    $stmt->bind_param('sss', $day, $meal_type, $meal_name);
    $stmt->execute();
    $meal_id = $conn->insert_id;
    $stmt->close();

    // If ingredients were provided as JSON (array), decode and insert them
    if ($ingredients_json) {
        $ingredients = json_decode($ingredients_json, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($ingredients)) {
            $istmt = $conn->prepare('INSERT INTO ingredients (meal_id, ingredient_name) VALUES (?,?)');
            if (!$istmt) throw new Exception('Prepare failed: ' . $conn->error);
            foreach ($ingredients as $ing) {
                $ing = trim((string)$ing);
                if ($ing === '') continue;
                $istmt->bind_param('is', $meal_id, $ing);
                $istmt->execute();
            }
            $istmt->close();
        }
    }

    $conn->commit();
    echo json_encode(['success' => true, 'meal_id' => $meal_id]);
    exit;

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error']);
    exit;
}

?>


