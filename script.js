// Single, clean script for meal planner
const days = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"]; 
const types = ["Breakfast","Lunch","Dinner"];

const overview = document.getElementById('overview');
const addIngredientBtn = document.getElementById('add-ingredient');
const saveBtn = document.getElementById('save-btn');
const formMsg = document.getElementById('form-msg');

function buildGrid(){
    overview.innerHTML = '';

    // header row: empty cell + day headers
    const headerRow = document.createElement('div');
    headerRow.className = 'grid-row header';
    headerRow.appendChild(document.createElement('div'));
    days.forEach(d=>{
        const h = document.createElement('div');
        h.className = 'grid-day';
        h.textContent = d;
        headerRow.appendChild(h);
    });
    overview.appendChild(headerRow);

    types.forEach(type=>{
        const row = document.createElement('div');
        row.className = 'grid-row';

        const typeCell = document.createElement('div');
        typeCell.className = 'grid-type';
        typeCell.textContent = type;
        row.appendChild(typeCell);

        days.forEach(day => {
            const cell = document.createElement('div');
            cell.className = 'grid-cell';
            cell.id = `cell-${day}-${type}`;
            cell.innerHTML = '<div class="placeholder">—</div>';
            row.appendChild(cell);
        });
        overview.appendChild(row);
    });
}

// Add ingredient input
function addIngredientInput(){
    const container = document.getElementById('ingredients-container');
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'ingredient';
    input.placeholder = 'Enter ingredient';
    container.appendChild(input);
}

// Attach event listeners
if (addIngredientBtn) addIngredientBtn.addEventListener('click', addIngredientInput);

// Save meal + ingredients
function saveMeal(){
    const day = document.getElementById('day').value;
    const meal_type = document.getElementById('meal_type').value;
    const meal_name = document.getElementById('meal').value.trim();
    if(!meal_name) return alert('Enter a meal');

    const ingredients = Array.from(document.querySelectorAll('.ingredient'))
                            .map(i=>i.value.trim())
                            .filter(v=>v);
    // Save meal first
    fetch('api/save_meal.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`day=${encodeURIComponent(day)}&meal_type=${encodeURIComponent(meal_type)}&meal_name=${encodeURIComponent(meal_name)}`
    })
    .then(async response => {
        if (!response.ok) {
            const text = await response.text();
            throw new Error(`HTTP ${response.status}: ${text.substring(0,100)}...`);
        }
        const contentType = response.headers.get('Content-Type');
        if(contentType && contentType.includes('application/json')){
            return response.json();
        } else {
            const text = await response.text();
            throw new Error(`Expected JSON, got ${contentType || 'unknown'}: ${text.substring(0,100)}...`);
        }
    })
    .then(async data => {
        console.log("JSON received:", data);
        if(data.success && data.meal_id){
            const mealId = data.meal_id;
            // Save ingredients (if any)
            if(ingredients.length){
                await Promise.all(ingredients.map(ing=>{
                    return fetch('api/save_ingredient.php', {
                        method:'POST',
                        headers:{'Content-Type':'application/x-www-form-urlencoded'},
                        body:`meal_id=${encodeURIComponent(mealId)}&ingredient_name=${encodeURIComponent(ing)}`
                    }).then(async resp=>{
                        if(!resp.ok){
                            const t = await resp.text();
                            throw new Error(`Ingredient save failed: ${resp.status} ${t}`);
                        }
                        const ct = resp.headers.get('Content-Type');
                        if(ct && ct.includes('application/json')) return resp.json();
                        const t = await resp.text();
                        throw new Error(`Expected JSON for ingredient save, got ${ct||'unknown'}: ${t.substring(0,100)}`);
                    });
                }));
            }
            alert('Meal saved');
            loadMeals();
        } else {
            alert('Save failed: ' + (data.error || 'unknown'));
        }
    })
    .catch(err => {
        console.error('Save error:', err);
        alert('Save error: '+err.message);
    });
}

// Attach save button listener
if (saveBtn) saveBtn.addEventListener('click', saveMeal);

// Load meals + ingredients
function loadMeals(){
    fetch('api/get_meals.php')
    .then(async response=>{
        if(!response.ok) throw new Error(`HTTP ${response.status}`);
        const contentType = response.headers.get('Content-Type');
        if(contentType && contentType.includes('application/json')){
            return response.json();
        } else {
            const text = await response.text();
            throw new Error(`Expected JSON, got ${contentType || 'unknown'}: ${text.substring(0,100)}...`);
        }
    })
    .then(data=>{
        types.forEach(type=>{
            days.forEach(day=>{
                const cell = document.getElementById(`cell-${day}-${type}`);
                if(!cell) return;
                cell.innerHTML='';
                if(data[day] && data[day][type]){
                    data[day][type].forEach(meal=>{
                        const mealDiv = document.createElement('div');
                        mealDiv.className = 'meal-card';
                        mealDiv.innerHTML = `<b>${meal.meal_name}</b>`;
                        cell.appendChild(mealDiv);

                        if(meal.ingredients){
                            const ul = document.createElement('ul');
                            ul.className = 'ingredient-list';
                            meal.ingredients.forEach(ing=>{
                                const li = document.createElement('li');
                                li.textContent = ing;
                                ul.appendChild(li);
                            });
                            mealDiv.appendChild(ul);
                        }
                    });
                }
            });
        });
    })
    .catch(err=>{console.error(err);});
}

// Initialize
buildGrid();
loadMeals();
