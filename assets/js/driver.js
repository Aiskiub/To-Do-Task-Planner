
function initializeDriver() {
    const driver = window.driver.js.driver;


    const driverObj = driver({
        showProgress: true,
        steps: [
            {
                element: "#sidebar",
                popover: {
                    title: "Barra Lateral",
                    description: "Aquí puedes acceder a las principales funciones, cerrar sesion y ver tus tareas filtradas.",
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
                element: "#myDay",
                popover: {
                    title: "Ver las tareas del dia",
                    description: "Aqui podras ver tus tareas del dia.",
                    side: "bottom",
                    align: "start"
                }
            },
            {
                element: "#next7Days",
                popover: {
                    title: "Ver las tareas de los proximos 7 dias",
                    description: "Aqui podras ver tus tareas de los proximos 7 dias.",
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
                element: "#myCalendar",
                popover: {
                    title: "Aqui tienes tu calendario",
                    description: "Aqui podras ver tus tareas en el calendario (proximamente).",
                    side: "right",
                    align: "start"
                }
            },
            {
                element: "#tags",
                popover: {
                    title: "Categorias",
                    description: "Usa las categorias para organizar tus tareas.",
                    side: "right",
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
            },
            {
                element: "#searchInput",
                popover: {
                    title: "Buscador",
                    description: "Busca tus tareas por nombre.",
                    side: "right",
                    align: "start"
                }
            },
            {
                element: "#taskColumns",
                popover: {
                    title: "Aqui estan tus tareas separadas por columnas",
                    description: "Las columnas son: Asignadas, En progreso y Completadas.",
                    side: "right",
                    align: "start"
                }
            },
            {
                element: "#taskInfoSection",
                popover: {
                    title: "Aqui puedes ver mas informacion de la tarea",
                    description: "Selecciona una tarea para ver mas informacion.",
                    side: "right",
                    align: "start"
                }
            }
        ]
    });

    // Iniciar el tour
    driverObj.drive();
}