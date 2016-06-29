<style>
    .table tbody tr td {
        text-align: center;
    }

    thead.panel th {
        background-color: #2D6CA2;
        color: white;
        text-align: center;
    }

    .dropdown {
        padding: 5px;
    }
</style>
<table>
    <tr>
        <td style="min-width: 200px;padding: 5px;">
            <img src="{{ asset('vendor/p2i/p2i_logo.jpg') }}">
        </td>
        <td>
            <div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuSecador" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Secador
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuSecador">
                    <li><a href="{{ url('p2i/secador') }}">Ver registros</a></li>
                    <li><a href="{{ url('p2i/secador/create') }}">Nuevo registro</a></li>
                </ul>
            </div>
        </td>
        <td>
            <div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuCarga" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Carga
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuCarga">
                    <li><a href="{{ url('p2i/carga') }}">Ver registros</a></li>
                    <li><a href="{{ url('p2i/carga/create') }}">Nuevo registro</a></li>
                    <li><a href="{{ url('p2i/carga/stat') }}">Estadisticas</a></li>
                </ul>
            </div>
        </td>
        <td>
            <div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuLimpieza" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Consumibles y limpieza
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLimpieza">
                    <li><a href="{{ url('p2i/limpieza') }}">Ver registros</a></li>
                    <li><a href="{{ url('p2i/limpieza/create') }}">Nuevo registro</a></li>
                </ul>
            </div>
        </td>
    </tr>
</table>