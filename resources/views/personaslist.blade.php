<!--Modal de listado para asignar personas-->
<div class="modal-body">
    @if(count($personas) === 0)
        <div class="alert alert-warning text-center">
            No hay personas registradas.
        </div>
    @else
        <ul class="list-group">
            @foreach($personas as $p)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="data:image/jpeg;base64,{{ $p->avatar }}" width="40" class="rounded-circle me-3">
                        <span>{{ $p->name }}</span>
                    </div>
                    <button class="btn btn-sm btn-success assignBtn"
                            data-task="{{ request('task_id') }}"
                            data-person="{{ $p->id }}">
                        Asignar
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>