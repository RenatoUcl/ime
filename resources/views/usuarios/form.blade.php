<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-outline mb-1">
            <label>Nombre (*)</label>
            <input type="text"
                   name="nombre"
                   class="form-control form-control-sm"
                   value="{{ old('nombre', $user->nombre ?? '') }}">
        </div>

        <div class="form-outline mb-1">
            <label>Apellido Paterno (*)</label>
            <input type="text"
                   name="ap_paterno"
                   class="form-control form-control-sm"
                   value="{{ old('ap_paterno', $user->ap_paterno ?? '') }}">
        </div>

        <div class="form-outline mb-1">
            <label>Apellido Materno</label>
            <input type="text"
                   name="ap_materno"
                   class="form-control form-control-sm"
                   value="{{ old('ap_materno', $user->ap_materno ?? '') }}">
        </div>

        <div class="form-outline mb-1">
            <label>Email (*)</label>
            <input type="email"
                   name="email"
                   class="form-control form-control-sm"
                   value="{{ old('email', $user->email ?? '') }}">
        </div>

        <hr>

        <div class="form-outline mb-1">
            <label>Nueva contraseña (opcional)</label>
            <input type="password"
                   name="password"
                   class="form-control form-control-sm"
                   placeholder="Dejar en blanco para no cambiar">
        </div>

        <div class="form-outline mb-1">
            <label>Confirmar nueva contraseña</label>
            <input type="password"
                   name="password_confirmation"
                   class="form-control form-control-sm">
        </div>

        <div class="form-outline mb-1">
            <label>Teléfono</label>
            <input type="text"
                   name="telefono"
                   class="form-control form-control-sm"
                   value="{{ old('telefono', $user->telefono ?? '') }}">
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary btn-sm">
                Guardar cambios
            </button>
        </div>
    </div>
</div>