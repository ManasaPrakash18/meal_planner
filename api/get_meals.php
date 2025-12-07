<?php
// api/get_meals.php - return all meals grouped by day and meal_type with ingredients
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

require_once __DIR__ . '/db.php';

if (!isset($conn) || !$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'No DB connection']);
    exit;
}

$days = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
$types = ["Breakfast","Lunch","Dinner"];

// Initialize result structure
$result = [];
foreach ($days as $d) {
    $result[$d] = [];
    foreach ($types as $t) {
        $result[$d][$t] = [];
    }
}

// Fetch meals
$mres = $conn->query('SELECT id, day, meal_type, meal_name FROM meals');
if (!$mres) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Query failed']);
    exit;
}

$meals = [];
while ($row = $mres->fetch_assoc()) {
    $meals[] = $row;
}

// Fetch all ingredients and group by meal_id to avoid N+1 queries
$ingredients_map = [];
$ires = $conn->query('SELECT meal_id, ingredient_name FROM ingredients');
if ($ires) {
    while ($r = $ires->fetch_assoc()) {
        $mid = (int)$r['meal_id'];
        if (!isset($ingredients_map[$mid])) $ingredients_map[$mid] = [];
        $ingredients_map[$mid][] = $r['ingredient_name'];
    }
}

foreach ($meals as $m) {
    $mid = (int)$m['id'];
    $m['ingredients'] = $ingredients_map[$mid] ?? [];
    $day = $m['day'];
    $type = $m['meal_type'];
    if (!isset($result[$day])) $result[$day] = [];
    if (!isset($result[$day][$type])) $result[$day][$type] = [];
    $result[$day][$type][] = $m;
}

echo json_encode($result);

?>
