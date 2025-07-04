# Evaluacion_Tecnica
Sistema web para gestionar una lista de tareas implementado con Laravel, jQuery y AJAX
Esta aplicación permite registrar tareas, asignar personas con imagen/avatar, y gestionar el estado de cada tarea

## 🚀 Instrucciones de Instalación
- Clonar el repositorio
- git clone https://github.com/JatziriHernan12/Evaluacion_Tecnica.git
- cd evaluacion_tecnica

- Edita el archivo .env con la respectiva configuración de base de datos sql server
- Generar la clave de la aplicación: php artisan key:generate

- Ejecutar migraciones: php artisan migrate
- Correr el servidor: php artisan serve

📮 Endpoints disponibles
Tareas
- GET /tasks - Ver tareas
- POST /tasks - Crear tarea
- PUT /tasks/{id} - Actualizar tarea
- DELETE /tasks/{id} - Eliminar tarea
- POST /tasks/{task_id}/assign - Asignar persona
- POST /tasks/{task_id}/unassign - Quitar persona

Personas
- GET /personas - Listar personas
- POST /personas - Crear persona (requiere nombre y avatar)

🛠️ Tecnologías Usadas
- Laravel Framework 9.52.20
- Bootstrap 5
- jQuery
- Blade
- Sql Server Management Studio 21
- Postman
- PHP 8.1.25
