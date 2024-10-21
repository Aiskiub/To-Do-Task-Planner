let tasks = [];

function addTask(title, important = false, tag = "") {
    const task = {
        id: Date.now(),
        title,
        completed: false,
        important,
        tag
    };
    tasks.push(task);
    renderTasks();
}

function deleteTask(id) {
    const confirmation = confirm("¿Estás seguro de que deseas borrar esta tarea?");
    if (confirmation) {
        tasks = tasks.filter(t => t.id !== id); // Filtra la tarea que se quiere borrar
        renderTasks(); // Vuelve a renderizar la lista de tareas
    }
}


function toggleTaskCompletion(id) {
    const task = tasks.find(t => t.id === id);
    if (task) {
        task.completed = !task.completed;
        renderTasks();
    }
}

function toggleTaskImportance(id) {
    const task = tasks.find(t => t.id === id);
    if (task) {
        task.important = !task.important;
        renderTasks();
    }
}

function renderTasks() {
    const taskList = document.getElementById('taskList');
    taskList.innerHTML = '';
    tasks.forEach(task => {
        const li = document.createElement('div');
        li.className = 'task-item';
        li.innerHTML = `
            <button onclick="toggleTaskCompletion(${task.id})">
                <i class="fas fa-check-square ${task.completed ? 'completed' : ''}"></i>
            </button>
            <span class="${task.completed ? 'completed' : ''}">${task.title}</span>
            <span style="margin-left: auto;">
                <button onclick="toggleTaskImportance(${task.id})">
                    <i class="fas fa-star ${task.important ? 'important' : ''}"></i>
                </button>
                ${task.tag ? `<i class="fas fa-tag" style="color: #8b5cf6;"></i>` : ''}
                <button onclick="deleteTask(${task.id})" class="btn-delete">
                    <i class="fas fa-trash" style="color: red;"></i>
                </button>
            </span>
        `;
        taskList.appendChild(li);
    });
}


document.getElementById('addTaskBtn').addEventListener('click', () => {
    document.getElementById('taskModal').style.display = 'block';
});

document.getElementById('saveTaskBtn').addEventListener('click', () => {
    const title = document.getElementById('newTaskInput').value;
    const important = document.getElementById('importantCheckbox').checked;
    const tag = document.getElementById('tagSelect').value;
    if (title) {
        addTask(title, important, tag);
        document.getElementById('newTaskInput').value = '';
        document.getElementById('importantCheckbox').checked = false;
        document.getElementById('tagSelect').value = '';
        document.getElementById('taskModal').style.display = 'none';
    }
});

document.getElementById('searchInput').addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    const filteredTasks = tasks.filter(task => task.title.toLowerCase().includes(searchTerm));
    renderFilteredTasks(filteredTasks);
});

function renderFilteredTasks(filteredTasks) {
    const taskList = document.getElementById('taskList');
    taskList.innerHTML = '';
    filteredTasks.forEach(task => {
        const li = document.createElement('div');
        li.className = 'task-item';
        li.innerHTML = `
            <button onclick="toggleTaskCompletion(${task.id})">
                <i class="fas fa-check-square ${task.completed ? 'completed' : ''}"></i>
            </button>
            <span class="${task.completed ? 'completed' : ''}">${task.title}</span>
            <span style="margin-left: auto;">
                <button onclick="toggleTaskImportance(${task.id})">
                    <i class="fas fa-star ${task.important ? 'important' : ''}"></i>
                </button>
                ${task.tag ? `<i class="fas fa-tag" style="color: #8b5cf6;"></i>` : ''}
            </span>
        `;
        taskList.appendChild(li);
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('taskModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}