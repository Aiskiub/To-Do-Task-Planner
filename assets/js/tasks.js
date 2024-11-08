// Manejo del modal y tareas
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
        
        const dueDateSelect = document.getElementById('dueDateSelect');
        const customDateInput = document.getElementById('customDateInput');
        
        // Procesar la fecha según la selección
        let finalDate = '';
        switch(dueDateSelect.value) {
            case 'today':
                finalDate = new Date().toISOString().split('T')[0];
                break;
            case 'tomorrow':
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                finalDate = tomorrow.toISOString().split('T')[0];
                break;
            case 'nextWeek':
                const nextWeek = new Date();
                nextWeek.setDate(nextWeek.getDate() + 7);
                finalDate = nextWeek.toISOString().split('T')[0];
                break;
            case 'custom':
                finalDate = customDateInput.value;
                break;
        }

        // Actualizar el valor del campo fecha_ejecucion antes de enviar
        const dateInput = document.createElement('input');
        dateInput.type = 'hidden';
        dateInput.name = 'fecha_ejecucion';
        dateInput.value = finalDate;
        taskForm.appendChild(dateInput);

        // Enviar el formulario
        taskForm.submit();
    });

    // Función para mostrar/ocultar el campo de fecha personalizada
    const dueDateSelect = document.getElementById('dueDateSelect');
    dueDateSelect.addEventListener('change', function() {
        const customDateInput = document.getElementById('customDateInput');
        customDateInput.style.display = this.value === 'custom' ? 'block' : 'none';
    });
});