@extends('adminlte::page')
@section('template_title')Responder Encuesta
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-primary mt-3">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Responder Encuestas</span>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <div id="pregunta-container">
                            @include('encuesta.pregunta', ['pregunta' => $pregunta])
                        </div>
                        
                        <script>
                        function enviarRespuesta(e) {
                            e.preventDefault();
                            let form = document.getElementById('form-respuesta');
                            let data = new FormData(form);
                        
                            fetch("{{ route('encuesta.responder') }}", {
                                method: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: data
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.terminado) {
                                    document.getElementById('pregunta-container').innerHTML = "<h3>¡Gracias por responder!</h3>";
                                } else {
                                    document.getElementById('pregunta-container').innerHTML = data.html;
                                }
                            });
                        }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
