<div style="font-family: Verdana, Arial, sans-serif;">
    <h1>Solicitudes Activas</h1>
    <p><strong>Area: </strong>{{ $data->area }}</p>
    <p><strong>Solicitud: </strong>{{$data->solicitud}}</p>
    <p><strong>Línea: </strong>{{$data->linea}}</p>
    <p><strong>Mensaje: </strong></p>
        <blockquote> La solicitud lleva más de <strong>{{$data->mins}}</strong> minuto/s activa</blockquote>
</div>
