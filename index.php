<?php
require_once 'php/config/config.php';

// Asegurarse de que la sesión está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario_id'])) {
    error_log("Usuario no autenticado - redirigiendo a login");
    header('Location: login.php');
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
            <h2 class="Login-button">
            <?php if(isset($_SESSION['nombre'])): ?>
                <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?> | </span>
                <a href="php/auth/cerrar_sesion.php">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php" class="login-button">Iniciar Sesión</a>
            <?php endif; ?>
        </h2>
            <p>Free Plan</p>
            <nav>
                <div class="nav-item" id="myDay">
                    <i class="fas fa-sun"></i> My Day
                </div>
                <div class="nav-item" id="next7Days">
                    <i class="fas fa-calendar"></i> Next 7 Days
                </div>
                <div class="nav-item" id="allMyTasks">
                    <i class="fas fa-list"></i> All My Tasks
                </div>
                <div class="nav-item" id="myCalendar">
                    <i class="fas fa-calendar-alt"></i> My Calendar (Beta)
                </div>
            </nav>
            <h3>Categories</h3>
            <div id="tags">
                <?php
                include 'php/categories/obtener_categorias_tags.php';
                ?>
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
                
                <!-- Añadimos las columnas manteniendo la estructura existente -->
                <div class="task-columns" id="taskColumns">
                    <div class="task-column">
                        <h3>Assigned</h3>
                        <div class="task-list" id="assignedList" data-status="1">
                            <?php
                            include 'php/tasks/obtener_tareas.php';
                            $tareas = obtenerTareas($_SESSION['usuario_id']);
                            
                            foreach($tareas as $tarea):
                                if($tarea['estado_id'] == 1): ?>
                                    <div class="task-item" draggable="true" data-id="<?php echo $tarea['id']; ?>">
                                        <span class="task-title">
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
                                        </div>
                                    </div>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="task-column">
                        <h3>In Progress</h3>
                        <div class="task-list" id="inProgressList" data-status="2">
                            <?php
                            foreach($tareas as $tarea):
                                if($tarea['estado_id'] == 2): ?>
                                    <div class="task-item" draggable="true" data-id="<?php echo $tarea['id']; ?>">
                                        <span class="task-title">
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
                                        </div>
                                    </div>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="task-column">
                        <h3>Completed</h3>
                        <div class="task-list" id="completedList" data-status="3">
                            <?php
                            foreach($tareas as $tarea):
                                if($tarea['estado_id'] == 3): ?>
                                    <div class="task-item" draggable="true" data-id="<?php echo $tarea['id']; ?>">
                                        <span class="task-title">
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
                                        </div>
                                    </div>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="task-info-section" id="taskInfoSection">
                <h2>Task Information</h2>
                <div id="taskDetails" class="task-details">
                    <p class="no-task-selected">Select a task to see details here</p>
                </div>
                
                <!-- Template for task details (inicialmente oculto) -->
                <div id="taskDetailsTemplate" class="task-details-content" style="display: none;">
                    <h3 class="task-title"></h3>
                    
                    <div class="detail-group">
                        <label>Description</label>
                        <p class="task-description"></p>
                    </div>
                    
                    <div class="detail-group">
                        <label>Due Date</label>
                        <p class="task-date"></p>
                    </div>
                    
                    <div class="detail-group">
                        <label>Priority</label>
                        <p class="task-priority"></p>
                    </div>
                    
                    <div class="detail-group">
                        <label>Category</label>
                        <p class="task-category"></p>
                    </div>
                    
                    <div class="detail-group">
                        <label>Status</label>
                        <p class="task-status"></p>
                    </div>
                    
                    <div class="task-actions">
                        <button class="btn-edit" onclick="editarTarea(this.dataset.taskId)">
                            <i class="fas fa-edit"></i> Edit Task
                        </button>
                        <button class="btn-delete" onclick="confirmarEliminarTarea(this.dataset.taskId)">
                            <i class="fas fa-trash-alt"></i> Delete Task
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <div id="taskModal" class="modal">
    <div class="modal-content">
        <h3>Nueva Tarea</h3>
        <form id="taskForm">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" name="descripcion">
            </div>

            <div class="form-group">
                <label for="dueDateSelect">Fecha</label>
                <select id="dueDateSelect" name="dueDateSelect">
                    <option value="today">Hoy</option>
                    <option value="tomorrow">Mañana</option>
                    <option value="nextWeek">Próxima semana</option>
                    <option value="custom">Personalizada</option>
                </select>
                
                <input type="date" id="customDateInput" name="fecha_ejecucion" style="display: none;">
                <input type="time" id="customTimeInput" name="hora_ejecucion" style="display: none;">
            </div>

            <div class="form-group">
                <label for="prioridad_id">Prioridad</label>
                <select id="prioridad_id" name="prioridad_id">
                    <option value="1">Alta</option>
                    <option value="2">Media</option>
                    <option value="3">Baja</option>
                </select>
            </div>

            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select id="categoria_id" name="categoria_id">
                    <?php include 'php/categories/read_categorias.php'; ?>
                </select>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" id="importante" name="importante">
                    Marcar como importante
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">Guardar</button>
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>
</div>
        


    

    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script><!--  -->
    <script src="./assets/js/driver.js"></script>
    <script src="./assets/js/tasks.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded');
            setupDragAndDrop();
        });
    </script>
</body>
</html>