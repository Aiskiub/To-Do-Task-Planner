
function initializeDriver() {
    const driver = window.driver.js.driver;


    const driverObj = driver({
        showProgress: true,
        steps: [
            {
                element: "#sidebar",
                popover: {
                    title: "Barra Lateral",
                    description: "Aquí puedes acceder a las principales funciones.",
                    side: "left",
                    align: "start"
                }
            },
            {
                element: "#task",
                popover: {
                    title: "My Day",
                    description: "Aquí puedes ver tus taras y agregar otras.",
                    side: "Left Start",
                    align: "start"
                }
            },
            {
                element: "#info-task",
                popover: {
                    title: "Informacion tarea",
                    description: "Aqui podras ver mas detalles de la tarea.",
                    side: "bottom",
                    align: "start"
                }
            },
            {
                element: "#allMyTasks",
                popover: {
                    title: "Todas las tareas",
                    description: "Accede a todas tus tareas.",
                    side: "right",
                    align: "start"
                }
            },
            {
                element: "#lista",
                popover: {
                    title: "Listas",
                    description: "Esta son tus listas de tareas personales, trabajo o listas de compras.",
                    side: "right",
                    align: "start"
                }
            },
            {
                element: "#tags",
                popover: {
                    title: "Etiquetas",
                    description: "Usa las etiquetas para organizar tus tareas.",
                    side: "top",
                    align: "start"
                }
            },
            {
                element: "#addTaskBtn",
                popover: {
                    title: "Añadir Tarea",
                    description: "Haz clic aquí para añadir una nueva tarea.",
                    side: "bottom",
                    align: "start"
                }
            }
        ]
    });

    // Iniciar el tour
    driverObj.drive();
}