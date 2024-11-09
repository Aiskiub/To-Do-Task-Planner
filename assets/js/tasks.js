console.log('tasks.js loaded');

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('taskModal');
    const addTaskBtn = document.getElementById('addTaskBtn');
    const saveTaskBtn = document.getElementById('saveTaskBtn');
    const closeModalBtn = document.getElementsByClassName('close')[0];
    
    // Abrir modal
    addTaskBtn.addEventListener('click', function() {
        modal.style.display = 'flex';
    });

    // Cerrar modal cuando se hace clic fuera de él
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });

    // Manejar el envío del formulario
    const taskForm = document.querySelector('#taskModal form');
    taskForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = this.getAttribute('data-mode') === 'edit';
        const taskId = this.getAttribute('data-task-id');
        
        // Si es edición, añadir el ID de la tarea al FormData
        if (isEdit && taskId) {
            formData.append('id', taskId);
            console.log('Editando tarea:', taskId); // Debug
        }
        
        // Procesar fecha y tiempo
        const dueDateSelect = document.getElementById('dueDateSelect');
        const customDateInput = document.getElementById('customDateInput');
        const customTimeInput = document.getElementById('customTimeInput');
        
        let finalDate = '';
        let finalTime = '';
        
        switch(dueDateSelect.value) {
            case 'today':
                finalDate = new Date().toISOString().split('T')[0];
                finalTime = '12:00';
                break;
            case 'tomorrow':
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                finalDate = tomorrow.toISOString().split('T')[0];
                finalTime = '12:00';
                break;
            case 'nextWeek':
                const nextWeek = new Date();
                nextWeek.setDate(nextWeek.getDate() + 7);
                finalDate = nextWeek.toISOString().split('T')[0];
                finalTime = '12:00';
                break;
            case 'custom':
                finalDate = customDateInput.value;
                finalTime = customTimeInput.value || '12:00';
                break;
        }
        
        formData.append('fecha_ejecucion', finalDate);
        formData.append('hora_ejecucion', finalTime);
        
        const url = isEdit ? './php/tasks/actualizar_tarea.php' : './php/tasks/process_task.php';
        
        // Debug: mostrar datos que se envían
        console.log('Enviando datos:', {
            url: url,
            isEdit: isEdit,
            taskId: taskId,
            fecha: finalDate,
            hora: finalTime
        });
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Cerrar el modal
                closeModal();
                
                // Recargar las tareas
                const selectedCategory = document.querySelector('#tags .tag.selected')?.dataset.id;
                if (selectedCategory) {
                    cargarTareasPorCategoria(selectedCategory);
                } else {
                    cargarTodasLasTareas();
                }
            } else {
                throw new Error(data.error || 'Error al guardar la tarea');
            }
        })
        .catch(error => {
            console.error('Error completo:', error);
            alert('Error al guardar la tarea: ' + error.message);
        });
    });

    // Actualizar la función para mostrar/ocultar los campos personalizados
    const dueDateSelect = document.getElementById('dueDateSelect');
    dueDateSelect.addEventListener('change', function() {
        const customDateInput = document.getElementById('customDateInput');
        const customTimeInput = document.getElementById('customTimeInput');
        const isCustom = this.value === 'custom';
        
        customDateInput.style.display = isCustom ? 'block' : 'none';
        customTimeInput.style.display = isCustom ? 'block' : 'none';
    });

    // Manejar clicks en las categorías (tags)
    document.querySelectorAll('#tags .tag').forEach(tag => {
        tag.addEventListener('click', function() {
            // Toggle selección visual
            document.querySelectorAll('#tags .tag').forEach(t => t.classList.remove('selected'));
            this.classList.add('selected');
            
            const categoriaId = this.dataset.id;
            cargarTareasPorCategoria(categoriaId);
            
            // Actualizar título
            document.getElementById('headerTitle').textContent = this.textContent + " Tasks";
        });
    });

    // Manejar la búsqueda
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const selectedCategory = document.querySelector('#tags .tag.selected')?.dataset.id;
        
        if (searchTerm.length > 0) {
            buscarTareas(searchTerm, selectedCategory);
        } else if (selectedCategory) {
            cargarTareasPorCategoria(selectedCategory);
        } else {
            cargarTodasLasTareas();
        }
    });

    // Configurar drag & drop
    setupDragAndDrop();
    
    // Configurar visualización de detalles
    setupTaskDetails();
    
    // Event listeners para los filtros temporales
    document.getElementById('myDay').addEventListener('click', function() {
        console.log('Clicked My Day'); // Para debugging
        cargarTareasPorFecha('today');
        actualizarNavActiva(this);
    });

    document.getElementById('next7Days').addEventListener('click', function() {
        console.log('Clicked Next 7 Days'); // Para debugging
        cargarTareasPorFecha('next7');
        actualizarNavActiva(this);
    });

    document.getElementById('allMyTasks').addEventListener('click', function() {
        console.log('Clicked All Tasks'); // Para debugging
        cargarTodasLasTareas();
        actualizarNavActiva(this);
    });
});

function buscarTareas(searchTerm, categoriaId = null) {
    // Corregir la ruta del endpoint de búsqueda
    let url = `./php/search/buscar_tareas.php?search=${encodeURIComponent(searchTerm)}`;
    if (categoriaId) {
        url += `&categoria_id=${categoriaId}`;
    }
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(tareas => {
            actualizarListaTareas(tareas);
            // Añadir mensaje si no hay resultados
            if (tareas.length === 0) {
                const taskList = document.getElementById('taskList');
                taskList.innerHTML = '<div class="no-results">No se encontraron tareas</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const taskList = document.getElementById('taskList');
            taskList.innerHTML = '<div class="error-message">Error al cargar las tareas</div>';
        });
}

function cargarTodasLasTareas() {
    fetch('./php/tasks/obtener_todas_tareas.php')
        .then(response => response.json())
        .then(tareas => {
            actualizarListaTareas(tareas);
            document.getElementById('headerTitle').textContent = 'All My Tasks';
            
            // Remover selección de categorías
            document.querySelectorAll('#tags .tag').forEach(tag => {
                tag.classList.remove('selected');
            });
        })
        .catch(error => {
            console.error('Error:', error);
            actualizarListaTareas([]);
        });
}

function cargarTareasPorCategoria(categoriaId) {
    fetch(`./php/tasks/obtener_tareas_categoria.php?categoria_id=${categoriaId}`)
        .then(response => response.json())
        .then(tareas => {
            actualizarListaTareas(tareas);
        })
        .catch(error => console.error('Error:', error));
}

function actualizarListaTareas(tareas) {
    const assignedList = document.getElementById('assignedList');
    const inProgressList = document.getElementById('inProgressList');
    const completedList = document.getElementById('completedList');
    
    if (!assignedList || !inProgressList || !completedList) {
        console.error('No se encontraron las listas necesarias');
        return;
    }
    
    // Limpiar todas las listas
    assignedList.innerHTML = '';
    inProgressList.innerHTML = '';
    completedList.innerHTML = '';
    
    tareas.forEach(tarea => {
        const div = document.createElement('div');
        div.className = 'task-item';
        div.setAttribute('draggable', 'true');
        div.dataset.id = tarea.id;
        
        div.innerHTML = `
            <span class="task-title">
                ${tarea.titulo}
            </span>
            <div class="task-actions">
                <i class="fas fa-star task-star ${tarea.importante ? 'active' : ''}"></i>
                ${tarea.prioridad_id == 1 ? '<i class="fas fa-flag text-danger"></i>' : ''}
            </div>
        `;

        // Añadir evento click para mostrar detalles
        div.addEventListener('click', () => mostrarDetallesTarea(tarea.id));
        
        // Colocar en la columna correspondiente
        switch(parseInt(tarea.estado_id)) {
            case 1:
                assignedList.appendChild(div);
                break;
            case 2:
                inProgressList.appendChild(div);
                break;
            case 3:
                completedList.appendChild(div);
                break;
        }
    });

    // Configurar drag & drop después de que todos los elementos estén en el DOM
    setTimeout(() => {
        setupDragAndDrop();
    }, 0);
}

function setupDragAndDrop() {
    console.log('Setting up drag and drop');
    const taskItems = document.querySelectorAll('.task-item');
    const taskLists = document.querySelectorAll('.task-list');
    
    console.log('Tasks found:', taskItems.length);
    console.log('Lists found:', taskLists.length);
    
    // Verificar si existen elementos antes de configurar el drag & drop
    if (!taskItems.length || !taskLists.length) {
        console.log('No hay elementos para configurar drag & drop');
        return;
    }

    taskItems.forEach(task => {
        task.setAttribute('draggable', true);
        
        task.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', task.dataset.id);
            task.classList.add('dragging');
        });

        task.addEventListener('dragend', function() {
            task.classList.remove('dragging');
        });
    });

    taskLists.forEach(list => {
        list.addEventListener('dragover', function(e) {
            e.preventDefault();
            const draggingTask = document.querySelector('.dragging');
            if (draggingTask) {
                const afterElement = getClosestTask(list, e.clientY);
                if (afterElement) {
                    list.insertBefore(draggingTask, afterElement);
                } else {
                    list.appendChild(draggingTask);
                }
            }
        });

        list.addEventListener('drop', function(e) {
            e.preventDefault();
            const taskId = e.dataTransfer.getData('text/plain');
            const newStatus = this.dataset.status;
            
            actualizarEstadoTarea(taskId, newStatus);
        });
    });
}

function getClosestTask(container, mouseY) {
    const tasks = [...container.querySelectorAll('.task-item:not(.dragging)')];
    
    return tasks.reduce((closest, task) => {
        const box = task.getBoundingClientRect();
        const offset = mouseY - box.top - box.height / 2;
        
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: task };
        } else {
            return closest;
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}

function actualizarEstadoTarea(taskId, newStatus) {
    console.log('Actualizando tarea:', taskId, 'a estado:', newStatus);
    
    fetch('./php/tasks/actualizar_estado_tarea.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${taskId}&estado=${newStatus}`
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta del servidor:', data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function eliminarTarea(taskId) {
    // Animación de desvanecimiento
    const taskElement = document.querySelector(`.task-item[data-id="${taskId}"]`);
    if (taskElement) {
        taskElement.style.transition = 'all 0.3s ease';
        taskElement.style.opacity = '0';
        taskElement.style.transform = 'scale(0.8)';
    }

    // Corregir la ruta del endpoint
    fetch('./php/tasks/eliminar_tarea.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${taskId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (taskElement) {
                setTimeout(() => {
                    taskElement.remove();
                }, 300);
            }
        } else {
            console.error('Error:', data.error);
            // Restaurar la tarea si hay error
            if (taskElement) {
                taskElement.style.opacity = '1';
                taskElement.style.transform = 'scale(1)';
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

function setupTaskDetails() {
    document.querySelectorAll('.task-item').forEach(task => {
        task.addEventListener('click', function(e) {
            // Evitar que se propague el evento si se hace clic en los botones de acción
            if (e.target.closest('.task-actions')) return;
            
            const taskId = this.dataset.id;
            mostrarDetallesTarea(taskId);
        });
    });
}

function mostrarDetallesTarea(taskId) {
    fetch(`./php/tasks/obtener_detalles_tarea.php?id=${taskId}`)
        .then(response => response.json())
        .then(tarea => {
            const taskDetails = document.getElementById('taskDetails');
            if (!taskDetails) return;
            
            taskDetails.innerHTML = `
                <div class="detail-content">
                    <div class="detail-row">
                        <span class="detail-label">Título:</span>
                        <span class="detail-value">${tarea.titulo || 'Sin título'}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Descripción:</span>
                        <span class="detail-value">${tarea.descripcion || 'Sin descripción'}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Fecha de ejecución:</span>
                        <span class="detail-value">${formatearFecha(tarea.fecha_ejecucion) || 'No especificada'}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Hora:</span>
                        <span class="detail-value">${tarea.hora_ejecucion || 'No especificada'}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Prioridad:</span>
                        <span class="detail-value ${tarea.prioridad_id === 1 ? 'high-priority' : ''}">${getPrioridadTexto(tarea.prioridad_id)}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Estado:</span>
                        <span class="detail-value status-${tarea.estado_id}">${getEstadoTexto(tarea.estado_id)}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Categoría:</span>
                        <span class="detail-value">${tarea.categoria_nombre || 'Sin categoría'}</span>
                    </div>
                </div>
                
                <div class="detail-actions">
                    <button onclick="editarTarea(${tarea.id})" class="btn-edit">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button onclick="confirmarEliminarTarea(${tarea.id})" class="btn-delete">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error al obtener detalles de la tarea:', error);
            const taskDetails = document.getElementById('taskDetails');
            if (taskDetails) {
                taskDetails.innerHTML = `
                    <div class="error-message">
                        <p>Error al cargar los detalles de la tarea</p>
                    </div>
                `;
            }
        });
}

function formatearFecha(fecha) {
    if (!fecha) return null;
    try {
        const f = new Date(fecha);
        if (isNaN(f.getTime())) return null;
        return f.toLocaleDateString();
    } catch (e) {
        return null;
    }
}

function getPrioridadTexto(prioridadId) {
    const prioridades = {
        1: 'Alta',
        2: 'Media',
        3: 'Baja'
    };
    return prioridades[prioridadId] || 'Unknown';
}

function getEstadoTexto(estadoId) {
    const estados = {
        1: 'Asignada',
        2: 'En Progreso',
        3: 'Completada'
    };
    return estados[estadoId] || 'Unknown';
}

function editarTarea(taskId) {
    const modal = document.getElementById('taskModal');
    if (!modal) {
        console.error('Modal no encontrado');
        return;
    }

    // Mostrar el modal
    modal.style.display = 'flex';

    // Obtener el formulario
    const taskForm = document.getElementById('taskForm');
    if (!taskForm) {
        console.error('Formulario no encontrado');
        return;
    }

    // Corregir la ruta para obtener detalles de la tarea
    fetch(`./php/tasks/obtener_detalles_tarea.php?id=${taskId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(tarea => {
            console.log('Datos de la tarea:', tarea);

            // Actualizar el título del modal primero
            const modalTitle = modal.querySelector('h3');
            if (modalTitle) modalTitle.textContent = 'Editar Tarea';

            // Llenar el formulario con los datos
            const titulo = document.getElementById('titulo');
            if (titulo) titulo.value = tarea.titulo || '';

            const descripcion = document.getElementById('descripcion');
            if (descripcion) descripcion.value = tarea.descripcion || '';

            const prioridad = document.getElementById('prioridad_id');
            if (prioridad) prioridad.value = tarea.prioridad_id || '2';

            const dueDateSelect = document.getElementById('dueDateSelect');
            const customDateInput = document.getElementById('customDateInput');
            const customTimeInput = document.getElementById('customTimeInput');

            if (dueDateSelect && customDateInput && customTimeInput) {
                dueDateSelect.value = 'custom';
                customDateInput.style.display = 'block';
                customTimeInput.style.display = 'block';
                customDateInput.value = tarea.fecha_ejecucion || '';
                customTimeInput.value = tarea.hora_ejecucion || '';
            }

            const categoria = document.getElementById('categoria_id');
            if (categoria && tarea.categoria_id) {
                categoria.value = tarea.categoria_id;
            }

            const importante = document.getElementById('importante');
            if (importante) {
                importante.checked = tarea.importante == 1;
            }

            // Establecer el modo de edición
            taskForm.setAttribute('data-mode', 'edit');
            taskForm.setAttribute('data-task-id', taskId);

            // Actualizar el texto del botón
            const saveButton = modal.querySelector('button[type="submit"]');
            if (saveButton) {
                saveButton.textContent = 'Actualizar Tarea';
            }
        })
        .catch(error => {
            console.error('Error al cargar los detalles de la tarea:', error);
            alert('Error al cargar los detalles de la tarea. Por favor, intente nuevamente.');
            // No cerrar el modal en caso de error
        });
}

// Función auxiliar para cerrar el modal
function closeModal() {
    const modal = document.getElementById('taskModal');
    if (modal) {
        modal.style.display = 'none';
        
        // Limpiar el formulario
        const form = document.getElementById('taskForm');
        if (form) {
            form.reset();
            form.removeAttribute('data-mode');
            form.removeAttribute('data-task-id');
        }
        
        // Restaurar el texto del botón
        const saveButton = modal.querySelector('button[type="submit"]');
        if (saveButton) {
            saveButton.textContent = 'Guardar Tarea';
        }
    }
}

function confirmarEliminarTarea(taskId) {
    if (confirm('¿Estás seguro de que deseas eliminar esta tarea?')) {
        eliminarTarea(taskId);
    }
}

function cargarTareasPorFecha(filtro) {
    console.log('Cargando tareas con filtro:', filtro);
    fetch(`./php/tasks/obtener_tareas_fecha.php?filtro=${filtro}`)
        .then(response => response.json())
        .then(tareas => {
            console.log('Tareas recibidas:', tareas);
            actualizarListaTareas(tareas);
            const titles = {
                'today': 'My Day',
                'next7': 'Next 7 Days'
            };
            document.getElementById('headerTitle').textContent = titles[filtro];
        })
        .catch(error => {
            console.error('Error:', error);
            actualizarListaTareas([]);
        });
}

function actualizarNavActiva(elemento) {
    // Remover clase activa de todos los elementos
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    // Añadir clase activa al elemento seleccionado
    elemento.classList.add('active');
}