@extends('adminlte::page')
@section('title', 'Medios de Verificación')
@section('content')
<h2>Medios de Verificación</h2>
<a href="{{route('medios-verificacion.create')}}" class="btn btn-primary">Nuevo</a>
<table class="table">
<tr>
<th>Subdimensión</th>
<th>Estado</th>
<th>Documentos</th>
<th></th>
</tr>
@foreach($medios as $m)
<tr>
<td>{{$m->subdimension->nombre}}</td>
<td>{{$m->estado}}</td>
<td>{{ $m->archivos->count() }}</td>
<td>
<a href="{{route('medios-verificacion.show',$m->id)}}" class="btn btn-info">Ver</a>
</td>
</tr>
@endforeach
</table>
@endsection
