<?php
session_start();
// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Tareas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <h2>Daniel</h2>
            <p>Free Plan</p>
            <nav>
                <ul>
                    <li id="myDay"><a href="#"><i class="fas fa-sun"></i> My Day</a></li>
                    <li id="next7Days"><a href="#"><i class="fas fa-calendar"></i> Next 7 Days</a></li>
                    <li id="allMyTasks"><a href="#"><i class="fas fa-list"></i> All My Tasks</a></li>
                    <li id="myCalendar"><a href="#"><i class="fas fa-calendar-alt"></i> My Calendar (Beta)</a></li>
                </ul>
            </nav>
            <h3>Lists</h3>
            <ul id="lista">
                <li id="personal-list"><a href="#"><span class="bullet personal-bullet"></span> Personal</a></li>
                <li id="workList"><a href="#"><span class="bullet work-bullet"></span> Work</a></li>
                <li id="groceryList"><a href="#"><span class="bullet grocery-bullet"></span> Grocery List</a></li>
            </ul>
            <h3>Tags</h3>
            <div id="tags">
                <span class="tag tag-important">Important</span>
                <span class="tag tag-project">Project</span>
                <span class="tag tag-personal">Personal</span>
            </div>
            <div class="Login-button">
            <?php if(isset($_SESSION['nombre'])): ?>
                <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?> | </span>
                <a href="php/cerrar_sesion.php">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php" class="login-button">Iniciar Sesión</a>
            <?php endif; ?>
            </div>

            <button class="start-tour-btn"><i onclick="initializeDriver()" class="fa-solid fa-circle-info"></i></button>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="main">
            <div class="task-section" id="task">
                <header class="main-header">
                    <h1 id="headerTitle">My Day</h1>
                    <button id="addTaskBtn" class="add-task-btn">
                        <i class="fas fa-plus"></i> Add Task
                    </button>
                </header>
                <div>
                    <input type="text" id="searchInput" placeholder="Search tasks..." class="search-input">
                </div>
                <div class="task-list" id="taskList">
                    <?php
                    include 'php/obtener_tareas.php';
                    $tareas = obtenerTareas($_SESSION['usuario_id']);
                    
                    foreach($tareas as $tarea): ?>
                        <div class="task-item">
                            <input type="checkbox" class="task-checkbox" 
                                   data-id="<?php echo $tarea['id']; ?>"
                                   <?php echo ($tarea['estado_id'] == 2) ? 'checked' : ''; ?>>
                            
                            <span class="task-title <?php echo ($tarea['estado_id'] == 2) ? 'completed' : ''; ?>">
                                <?php echo htmlspecialchars($tarea['titulo']); ?>
                            </span>
                            
                            <div class="task-actions">
                                <?php if($tarea['importante']): ?>
                                    <i class="fas fa-star task-star active"></i>
                                <?php else: ?>
                                    <i class="fas fa-star task-star"></i>
                                <?php endif; ?>
                                
                                <?php if($tarea['prioridad_id'] == 1): ?>
                                    <i class="fas fa-flag text-danger"></i>
                                <?php endif; ?>
                                
                                <button class="btn-delete" onclick="eliminarTarea(<?php echo $tarea['id']; ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        
            <div class="task-info-section" id="info-task">
                <h2>Task Information</h2>
                <div id="taskDetails">
                    <p>Select a task to see details here</p>
                </div>
            </div>
        </main>

        <div id="taskModal" class="modal">
    <div class="modal-content">
        <h3>Add New Task</h3>
        <form action="php/process_task.php" method="post">
            <div class="form-group">
                <input type="text" name="titulo" id="newTaskInput" placeholder="Task title" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Description</label>
                <textarea name="descripcion" id="descripcion" placeholder="Task description"></textarea>
            </div>

            <div class="form-group">
                <label for="dueDateSelect">Due Date</label>
                <select name="dueDateSelect" id="dueDateSelect">
                    <option value="today">Today</option>
                    <option value="tomorrow">Tomorrow</option>
                    <option value="nextWeek">Next Week</option>
                    <option value="custom">Custom Date</option>
                </select>
                <input type="date" id="customDateInput" style="display: none;">
            </div>

            <div class="form-group">
                <label for="prioridad_id">Priority</label>
                <select name="prioridad_id" id="prioridad_id">
                    <option value="1">High</option>
                    <option value="2">Medium</option>
                    <option value="3">Low</option>
                </select>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="importante" id="importante">
                    Important
                </label>
            </div>

            <div class="form-group">
                <label for="categoria_id">Category</label>
                <select name="categoria_id" id="categoria_id">
                    <option value="">Select a category</option>
                    <option value="1">Personal</option>
                    <option value="2">Work</option>
                    <option value="3">Shopping</option>
                </select>
            </div>

            <button type="submit" class="save-task-btn">Save Task</button>
        </form>
    </div>
</div>
</div>
        


    

    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script><!--  -->
    <script src="./assets/js/driver.js"></script>
    <script src="./assets/js/tasks.js"></script>
</body>
</html>
