<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" name="csrf-token" content="{{ csrf_token() }}"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Lista de tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
<div class="container py-4">
    <h1 class="mb-4 text-center">Gestión de Tareas</h1>

    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-primary" id="addTaskBtn">Nueva Tarea</button>
        <button class="btn btn-secondary" id="showPersonFormBtn">Registrar Persona</button>
    </div>
    <!-- Estructura de la tabla -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white" id="taskTable">
            <thead class="table-dark">
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Status</th>
                    <th>Personas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Modal para formulari de tarea -->
    <div class="modal fade" id="taskModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" id="taskForm">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Tarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="task_id">
                    <div class="mb-3">
                        <label for="title" class="form-label">Título</label>
                        <input type="text" id="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea id="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" class="form-select" required>
                            <option value="1">Sin completar</option>
                            <option value="2">En progreso</option>
                            <option value="3">Completada</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para formulario persona -->
    <div class="modal fade" id="personModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="personModalContent">
                <div class="modal-body text-center py-5">
                    <p>Cargando formulario...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para asignar persona -->
    <div class="modal fade" id="assignPersonModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title">Asignar persona a la tarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="assignPersonBody">
                    <p>Cargando personas...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    fetchTasks();
    
    function statusText(status) {
        switch(parseInt(status)) {
            case 1: return 'Sin completar'; //en lugar de mostrar numero, muestra un estatus en texto
            case 2: return 'En progreso';
            case 3: return 'Completada';
            default: return status;
        }
    }
    
function fetchTasks() {
    $.get('/tasks', function(data) {
        let rows = '';
        data.forEach(task => {
            // Renderizar personas asignadas
            let persons = '';
            if (task.people.length > 0) {
                persons = task.people.map(p => `
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2 person-block" data-person="${p.id}">
                        <div class="d-flex align-items-center">
                            <img src="data:image/jpeg;base64,${p.avatar}" width="30" class="rounded-circle me-2">
                            <span>${p.name}</span>
                        </div>
                        <button class="btn btn-sm btn-danger unassignBtn" data-task="${task.id}" data-person="${p.id}">Quitar</button>
                    </div>
                `).join('');
            }

            // para mostrar siempre el botón asignar persona
            const assignBtn = `<button class="btn btn-sm btn-info assignPersonBtn mt-2" data-id="${task.id}">Asignar persona</button>`;

            rows += `<tr id="taskRow_${task.id}">
                <td>${task.title}</td>
                <td>${task.description}</td>
                <td>${statusText(task.status)}</td>
                <td>${persons}${assignBtn}</td>
                <td>
                    <button class="btn btn-sm btn-warning editTask" data-id="${task.id}">Editar</button>
                    <button class="btn btn-sm btn-danger deleteTask" data-id="${task.id}">Eliminar</button>
                </td>
            </tr>`;
        });

        $('#taskTable tbody').html(rows);
    });
}

    $('#addTaskBtn').click(function() {
        $('#taskForm')[0].reset();
        $('#task_id').val('');
        new bootstrap.Modal(document.getElementById('taskModal')).show();
    });

    $('#showPersonFormBtn').click(function() {
    $('#personModalContent').html('<div class="modal-body text-center py-5"><p>Cargando formulario...</p></div>');

    $.get('/personas/create', function(html) {
        $('#personModalContent').html(html);
        new bootstrap.Modal(document.getElementById('personModal')).show();
    }).fail(function() {
        $('#personModalContent').html('<div class="modal-body"><p>Error cargando el formulario.</p></div>');
    });
});

    $('#taskForm').submit(function(e) {
        e.preventDefault();
        let taskId = $('#task_id').val();
        let url = taskId ? `/tasks/${taskId}` : '/tasks';
        let data = {
            title: $('#title').val(),
            description: $('#description').val(),
            status: $('#status').val(),
            _token: $('meta[name="csrf-token"]').attr('content') //token de laravel
        };
    
        if (taskId) {
            data._method = 'PUT';
        }
    
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(newTask) {
                bootstrap.Modal.getInstance(document.getElementById('taskModal')).hide();
            
                const personsColumn = `<button class="btn btn-sm btn-info assignPersonBtn" data-id="${newTask.id}">Asignar persona</button>`;
                const row = `
                    <tr id="taskRow_${newTask.id}">
                        <td>${newTask.title}</td>
                        <td>${newTask.description}</td>
                        <td>${statusText(newTask.status)}</td>
                        <td>${personsColumn}</td>
                        <td>
                            <button class="btn btn-sm btn-warning editTask" data-id="${newTask.id}">Editar</button>
                            <button class="btn btn-sm btn-danger deleteTask" data-id="${newTask.id}">Eliminar</button>
                        </td>
                    </tr>`;
            
                if ($(`#taskRow_${newTask.id}`).length) {
                    $(`#taskRow_${newTask.id}`).replaceWith(row); // actualiza solo l fila existente
                } else {
                    $('#taskTable tbody').prepend(row); // agrega nueva fila al crear uno nuevo
                }
            
                alert('Guardado exitosamente');
            },
            error: function() {
                alert('Error al guardar');
            }
        });
    });

$(document).on('click', '.editTask', function() {
    let id = $(this).data('id'); //obtener la tarea de forma individual
    $.get(`/tasks/${id}`, function(task) {
        $('#title').val(task.title);
        $('#description').val(task.description);
        $('#status').val(task.status);
        $('#task_id').val(task.id);
        new bootstrap.Modal(document.getElementById('taskModal')).show();
    });
});

    $(document).on('click', '.deleteTask', function() {
        let id = $(this).data('id');
        if (confirm('¿Eliminar tarea?')) {
            $.ajax({
                url: `/tasks/${id}`,
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function() {
                    $(`#taskRow_${id}`).remove();
                    alert('Eliminado');
                }
            });
        }
    });

    $(document).on('click', '.editTask', function() {
        let id = $(this).data('id');
        $.get('/tasks', function(data) {
            const task = data.find(t => t.id == id);
            $('#title').val(task.title);
            $('#description').val(task.description);
            $('#status').val(task.status);
            $('#task_id').val(task.id);
            new bootstrap.Modal(document.getElementById('taskModal')).show();
        });
    });

    $(document).on('click', '.assignPersonBtn', function () {
        const taskId = $(this).data('id');
        $('#assignPersonBody').html('<p>Cargando personas...</p>');
        const modal = new bootstrap.Modal(document.getElementById('assignPersonModal'));
        modal.show();
        $.get(`/personas/lista?task_id=${taskId}`, function (html) {
        $('#assignPersonBody').html(html);
    });
});

    $(document).on('click', '#goRegisterPerson', function() {
        bootstrap.Modal.getInstance(document.getElementById('assignPersonModal')).hide();
        new bootstrap.Modal(document.getElementById('personModal')).show();
    });

    $(document).on('click', '.assignBtn', function () {
    const taskId = $(this).data('task');
    const personId = $(this).data('person');

    $.post(`/tasks/${taskId}/assign`, {
        person_id: personId,
        _token: $('meta[name="csrf-token"]').attr('content')
    }, function () {
        alert('Persona asignada');
        bootstrap.Modal.getInstance(document.getElementById('assignPersonModal')).hide();
        fetchTasks(); // actualiza la tabla
    });
});

$(document).on('click', '.unassignBtn', function () {
    const taskId = $(this).data('task');
    const personId = $(this).data('person');

    $.post(`/tasks/${taskId}/unassign`, {
        person_id: personId,
        _token: $('meta[name="csrf-token"]').attr('content')
    }, function () {
        $(`#taskRow_${taskId} .person-block[data-person="${personId}"]`).remove();
    }).fail(function () {
        alert('Error al desasignar persona.');
    });
    });
});
</script>
</body>
</html>