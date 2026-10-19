@csrf
<div class="form-group">
    <label>Título</label>
    <input class="form-control" name="titulo" value="{{old('titulo',$medio->titulo ?? '')}}">
</div>
<div class="form-group">
    <label>Subdimensión</label>
    <select class="form-control" name="id_subdimension">
        @foreach($subdimensiones as $sub)
            <option value="{{$sub->id}}" @if(isset($medio) && $medio->id_subdimension==$sub->id ) selected @endif>
                {{$sub->dimension->nombre}} - {{$sub->nombre}}
            </option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Descripción</label>
    <textarea class="form-control" name="descripcion"> {{old('descripcion',$medio->descripcion ?? '')}} </textarea>
</div>