@csrf
<div class="form-group">
    <label>Término o frase clave</label>
    <input class="form-control" name="termino" value="{{old('termino',$termino->termino ?? '')}}">
</div>
<div class="form-group">
    <label>Subdimensiones</label>
    <select class="form-control select2" multiple name="subdimensiones[]">
        @foreach($subdimensiones as $sub)
            <option value="{{$sub->id}}" @if(isset($termino) && $termino->subdimensiones ->contains($sub->id) ) selected @endif >{{$sub->dimension->nombre}} - {{$sub->nombre}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Peso</label>
    <input type="number" class="form-control" name="peso" value="{{old('peso',1)}}">
</div>

<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input class="custom-control-input" type="checkbox" id="obligatorio" name="obligatorio" @if(isset($termino) && $termino->pivot?->obligatorio ) checked @endif >
        <label for="obligatorio" class="custom-control-label">Obligatorio</label>
    </div>
</div>