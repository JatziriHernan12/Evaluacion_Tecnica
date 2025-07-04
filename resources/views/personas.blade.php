<!--Modal de form para registrar personas-->
<div class="form-container p-3">
    <h2>Registrar Persona</h2>
    <form id="personaForm">
        <div class="form-group mb-3">
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="avatar">Avatar (imagen):</label>
            <input type="file" id="avatar" name="avatar" accept="image/*" class="form-control" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Guardar Persona</button>
        </div>
    </form>

    <div id="alertBox" class="mt-3"></div>
</div>

<script>
    $('#personaForm').on('submit', function(e) {
        e.preventDefault();

        const file = document.getElementById('avatar').files[0];

        if (!file) {
            showAlert('Selecciona una imagen.', 'error');
            return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {
            const base64 = e.target.result.split(',')[1]; //encriptacion

            $.ajax({
                url: '/personas',
                type: 'POST',
                data: {
                    name: $('#name').val(),
                    avatar: base64
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showAlert('Persona guardada correctamente.', 'success');
                    $('#personaForm')[0].reset();
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Error al guardar persona.';
                    showAlert(msg, 'error');
                }
            });
        };

        reader.readAsDataURL(file);
    });

    function showAlert(message, type) {
        const alertBox = $('#alertBox');
        const className = type === 'success' ? 'alert alert-success' : 'alert alert-danger';
        alertBox.html(`<div class="${className}">${message}</div>`);
    }
</script>