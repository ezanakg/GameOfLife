<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Game of Life</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    table {
      margin: 0 auto;
      border-collapse: collapse;
    }
    td {
      width: 20px;
      height: 20px;
      border: 1px solid #ccc;
      background-color: #f0f0f0;
    }
    .alive {
      background-color: black;
    }
  </style>
</head>

<body>

<!-- ✅ Navigation Bar -->
<nav class="navbar navbar-light bg-light mb-3">
  <div class="container-fluid">
    <span class="navbar-text">
      Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
    </span>
    <a href="logout.php" class="btn btn-danger">Logout</a>
  </div>
</nav>

<div class="container text-center">
  <h1 class="mb-4">Conway's Game of Life</h1>

  <div id="grid-container" class="mb-4"></div>

  <div class="btn-group mb-4">
    <button onclick="startGame()" class="btn btn-success">Start</button>
    <button onclick="stopGame()" class="btn btn-warning">Stop</button>
    <button onclick="nextGeneration()" class="btn btn-primary">Next Generation</button>
    <button onclick="run23Generations()" class="btn btn-info">+23 Generations</button>
    <button onclick="resetGame()" class="btn btn-danger">Reset</button>
  </div>

  <div class="mb-4">
    <label for="patternSelect" class="form-label">Load Pattern:</label>
    <select id="patternSelect" onchange="loadPattern()" class="form-select w-auto d-inline-block">
      <option value="">Select Pattern</option>
      <option value="block">Block (Still Life)</option>
      <option value="blinker">Blinker (Oscillator)</option>
      <option value="glider">Glider (Spaceship)</option>
    </select>
  </div>
</div>

<script>
const rows = 20;
const cols = 20;
let grid = [];
let interval = null;
let generations = 0;

// Create empty grid
function createGrid() {
  const container = document.getElementById('grid-container');
  container.innerHTML = '';
  grid = [];
  const table = document.createElement('table');

  for (let i = 0; i < rows; i++) {
    const tr = document.createElement('tr');
    grid[i] = [];
    for (let j = 0; j < cols; j++) {
      const td = document.createElement('td');
      td.addEventListener('click', () => toggleCell(i, j));
      tr.appendChild(td);
      grid[i][j] = 0;
    }
    table.appendChild(tr);
  }
  container.appendChild(table);
}

function toggleCell(i, j) {
  grid[i][j] = grid[i][j] ? 0 : 1;
  updateGrid();
}

function updateGrid() {
  const container = document.getElementById('grid-container');
  const cells = container.getElementsByTagName('td');
  let index = 0;
  for (let i = 0; i < rows; i++) {
    for (let j = 0; j < cols; j++) {
      cells[index].className = grid[i][j] ? 'alive' : '';
      index++;
    }
  }
}

function nextGeneration() {
  const newGrid = [];
  for (let i = 0; i < rows; i++) {
    newGrid[i] = [];
    for (let j = 0; j < cols; j++) {
      let aliveNeighbors = 0;
      for (let x = -1; x <= 1; x++) {
        for (let y = -1; y <= 1; y++) {
          if (x === 0 && y === 0) continue;
          const ni = i + x;
          const nj = j + y;
          if (ni >= 0 && ni < rows && nj >= 0 && nj < cols) {
            aliveNeighbors += grid[ni][nj];
          }
        }
      }

      if (grid[i][j] === 1) {
        newGrid[i][j] = (aliveNeighbors === 2 || aliveNeighbors === 3) ? 1 : 0;
      } else {
        newGrid[i][j] = (aliveNeighbors === 3) ? 1 : 0;
      }
    }
  }
  grid = newGrid;
  updateGrid();
  generations++;
}

function startGame() {
  if (!interval) {
    interval = setInterval(nextGeneration, 300);
  }
}

function stopGame() {
  clearInterval(interval);
  interval = null;

  const formData = new FormData();
  formData.append('generations', generations);

  fetch('save_session.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    console.log(data);
    alert("Game session saved successfully!");
  })
  .catch(error => {
    console.error('Error saving session:', error);
    alert("Failed to save session.");
  });
}

function run23Generations() {
  for (let i = 0; i < 23; i++) {
    nextGeneration();
  }
}

function resetGame() {
  clearInterval(interval);
  interval = null;
  generations = 0;
  createGrid();
}

function loadPattern() {
  const pattern = document.getElementById('patternSelect').value;
  resetGame();

  if (pattern === "block") {
    grid[10][10] = 1;
    grid[10][11] = 1;
    grid[11][10] = 1;
    grid[11][11] = 1;
  } else if (pattern === "blinker") {
    grid[10][9] = 1;
    grid[10][10] = 1;
    grid[10][11] = 1;
  } else if (pattern === "glider") {
    grid[0][1] = 1;
    grid[1][2] = 1;
    grid[2][0] = 1;
    grid[2][1] = 1;
    grid[2][2] = 1;
  }
  updateGrid();
}

// Initialize
createGrid();
</script>

</body>
</html>
