# 🥗 Weekly Meal Planner

A simple, full-stack **weekly meal planning** app built with **PHP, MySQL, HTML/CSS, and Vanilla JavaScript**.  
You can add meals for each day of the week, attach ingredients, and view everything in a clean weekly grid.

---

## ✨ Features

- **Add meals by day & type**
  - Choose a day (Mon–Sun) and meal type (Breakfast, Lunch, Dinner)
  - Add a meal name + optional list of ingredients
- **Dynamic ingredient input fields**
  - Add multiple ingredients using “Add Another Ingredient”
- **Weekly Overview Grid**
  - Auto-generated layout of all days × meal types
  - Each cell shows meals + ingredients
- **PHP API + MySQL storage**
  - Fully persistent meal & ingredient data
- **Simple, responsive UI**
  - Clean layout for fast planning

---

## 🛠 Tech Stack

**Frontend:**  
- HTML  
- CSS  
- Vanilla JavaScript (Fetch API)

**Backend:**  
- PHP (REST-style API endpoints)

**Database:**  
- MySQL (meals & ingredients tables)

---

## 📁 Project Structure

```text
meal_planner/
├── index.php          # Main UI for adding/viewing meals
├── style.css          # Styles for form + weekly grid
├── script.js          # Client-side logic and API calls
├── database.sql       # Database schema
└── api/
    ├── db.php             # DB connection
    ├── get_meals.php      # GET → Fetch weekly meals
    ├── save_meal.php      # POST → Save meal
    └── save_ingredient.php# POST → Save ingredient
```

## 🚀 Getting Started
---

### **1. Install Requirements**
Make sure you have:
- PHP 7+
- MySQL / MariaDB
- Apache/Nginx **or** PHP built-in server

---

### **2. Clone the Repository**
```bash
git clone https://github.com/ManasaPrakash18/meal_planner.git
cd meal_planner
```
### **3. Set Up Database**
Run the SQL script in your MySQL client **or** simply import `database.sql`.

---

### **4. Configure DB Credentials**
Edit the file: api/db.php


Ensure that the value of `$db` matches the name of your database.

---

### **5. Run the App**

Using PHP’s built-in server:

```bash
php -S localhost:8000
```
Open the app in your browser: http://localhost/index.php


## 🧩 How It Works

### **Frontend Flow**
- `buildGrid()` → Generates the weekly table layout  
- `loadMeals()` → Fetches meals + ingredients from the backend  

---

### **When a Meal Is Saved**
The app performs the following operations:

1. **POST → `save_meal.php`**  
2. **POST → `save_ingredient.php`** for each ingredient  
3. **UI Refresh** → Updates the weekly grid  

---


