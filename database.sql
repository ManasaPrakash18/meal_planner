-- Create the meal_planner database
CREATE DATABASE IF NOT EXISTS meal_planner;
USE meal_planner;

-- Create meals table
CREATE TABLE IF NOT EXISTS meals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    day VARCHAR(20) NOT NULL,
    meal_type VARCHAR(20) NOT NULL,
    meal_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create ingredients table
CREATE TABLE IF NOT EXISTS ingredients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    meal_id INT NOT NULL,
    ingredient_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (meal_id) REFERENCES meals(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for faster queries
CREATE INDEX idx_day_type ON meals(day, meal_type);
CREATE INDEX idx_meal_id ON ingredients(meal_id);
