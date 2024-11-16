document.addEventListener('DOMContentLoaded', function() {
    const taskInfoSection = document.querySelector('.task-info-section');
    
    // Manejar visualización de detalles de tarea
    document.querySelectorAll('.task-item').forEach(task => {
        task.addEventListener('click', (e) => {
            // No abrir detalles si se clickea en los botones de acción
            if (e.target.closest('.task-actions')) return;
            
            if (window.innerWidth <= 768) {
                taskInfoSection.classList.add('active');
                
                // Asegurarse de que existe el botón de cerrar
                if (!taskInfoSection.querySelector('.close-details')) {
                    const closeButton = document.createElement('button');
                    closeButton.innerHTML = '<i class="fas fa-times"></i>';
                    closeButton.className = 'close-details';
                    taskInfoSection.prepend(closeButton);

                    closeButton.addEventListener('click', () => {
                        taskInfoSection.classList.remove('active');
                    });
                }
            }
        });
    });

    // También permitir cerrar al deslizar hacia la derecha
    let touchStartX = 0;
    let touchEndX = 0;

    taskInfoSection.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    }, false);

    taskInfoSection.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        if (touchEndX > touchStartX + 50) { // Si el deslizamiento es mayor a 50px
            taskInfoSection.classList.remove('active');
        }
    }, false);
}); 