<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Weekly Meal Planner | Plan Your Meals with Ease</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>🍽️ Weekly Meal Planner</h1>

<div class="container">

    <aside class="form-card">
        <h2>Add Meal</h2>

        <label for="day">Day</label>
        <select id="day">
            <option>Monday</option>
            <option>Tuesday</option>
            <option>Wednesday</option>
            <option>Thursday</option>
            <option>Friday</option>
            <option>Saturday</option>
            <option>Sunday</option>
        </select>

        <label for="meal_type">Meal Type</label>
        <select id="meal_type">
            <option>Breakfast</option>
            <option>Lunch</option>
            <option>Dinner</option>
        </select>

        <label for="meal">Meal Name</label>
        <input type="text" id="meal" placeholder="e.g., Spaghetti Carbonara">

        <label for="ingredients-container">Ingredients</label>
        <div id="ingredients-container">
            <input type="text" class="ingredient" placeholder="e.g., Pasta">
        </div>
        <button type="button" id="add-ingredient">+ Add Another Ingredient</button>
        <div class="actions">
            <button id="save-btn">Save Meal</button>
        </div>
        <div id="form-msg" class="form-msg" aria-live="polite" role="status"></div>
    </aside>

    <main class="overview">
        <h2>Weekly Overview</h2>
        <div class="grid" id="overview"></div>
    </main>

</div>

<script src="script.js"></script>
</body>
</html>
