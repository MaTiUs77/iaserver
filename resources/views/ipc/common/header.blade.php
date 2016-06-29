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
<div style="background-color: #FDFDFD;">

    <table>
        <tr>
            <td style="min-width: 200px;padding: 5px;">
                <img src="{{ asset('vendor/ipc/logo.jpg') }}">
            </td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuCarga" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Certificacion
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuCarga">
                        <li><a href="{{ url('ipc/certificacion') }}">Ver registros</a></li>
                        <li><a href="{{ url('ipc/certificacion/create') }}">Nueva certificacion</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    </table>
</div>
