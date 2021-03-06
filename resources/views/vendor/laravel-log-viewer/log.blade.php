@extends('layouts.architectui')

@section('page_title', 'Logs')

@section('module_title', 'Logs de evpiu')

@section('subtitle', 'Este modulo permite ver toda la informacion contenida en los logs de la aplicacion.')

@section('content')
    @can('gestion_clientes.view')
        <div class="card">
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col sidebar mb-3">
                            <h1><i class="fa fa-calendar" aria-hidden="true"></i> Logs</h1>
                            <div class="list-group div-scroll">
                                @foreach($folders as $folder)
                                    <div class="list-group-item">
                                        <a href="?f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}">
                                            <span class="fa fa-folder"></span> {{$folder}}
                                        </a>
                                        @if ($current_folder == $folder)
                                            <div class="list-group folder">
                                                @foreach($folder_files as $file)
                                                    <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}&f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}"
                                                       class="list-group-item @if ($current_file == $file) llv-active @endif">
                                                        {{$file}}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                @foreach($files as $file)
                                    <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}"
                                       class="list-group-item @if ($current_file == $file) llv-active @endif">
                                        {{$file}}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <br>
                        <div class="col-10 table-container">
                            @if ($logs === null)
                                <div>
                                    log mayor a 50MB, por favor descarguelo.
                                </div>
                            @else
                                <table id="table-log" class="table table-striped" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
                                    <thead>
                                        <tr>
                                            @if ($standardFormat)
                                                <th>Nivel</th>
                                                <th>Contexto</th>
                                                <th>Fecha</th>
                                            @else
                                                <th>Numero de lineas</th>
                                            @endif
                                            <th>Contenido</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($logs as $key => $log)
                                        <tr data-display="stack{{{$key}}}">
                                            @if ($standardFormat)
                                                <td class="nowrap text-{{{$log['level_class']}}}">
                                                    <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                                                </td>
                                                <td class="text">{{$log['context']}}</td>
                                            @endif
                                            <td class="date">{{{$log['date']}}}</td>
                                            <td class="text">
                                                @if ($log['stack'])
                                                    <button type="button"
                                                            class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2"
                                                            data-display="stack{{{$key}}}">
                                                        <span class="fa fa-search"></span>
                                                    </button>
                                                @endif
                                                {{{$log['text']}}}
                                                @if (isset($log['in_file']))
                                                    <br/>{{{$log['in_file']}}}
                                                @endif
                                                @if ($log['stack'])
                                                    <div class="stack" id="stack{{{$key}}}"
                                                         style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            <div class="p-3">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                @if($current_file)
                    <a  class="btn btn-success" href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                        <span class="fa fa-download"></span> Descargar archivo
                    </a>
                    -
                    <a id="clean-log" class="btn btn-primary" href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                        <span class="fa fa-sync"></span> Limpiar archivo
                    </a>
                    -
                    <a id="delete-log" class="btn btn-danger" href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                        <span class="fa fa-trash"></span> Eliminar archivo
                    </a>
                    @if(count($files) > 1)
                        -
                        <a id="delete-all-log" class="btn btn-danger" href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                            <span class="fa fa-trash-alt"></span> Eliminar todos los archivos
                        </a>
                    @endif
                @endif
            </div>
        </div>
        <style>
            h1 {
                font-size: 1.5em;
                margin-top: 0;
            }

            #table-log {
                font-size: 0.85rem;
            }

            .sidebar {
                font-size: 0.85rem;
                line-height: 1;
            }

            .btn {
                font-size: 0.7rem;
            }

            .stack {
                font-size: 0.85em;
            }

            .date {
                min-width: 75px;
            }

            .text {
                word-break: break-all;
            }

            a.llv-active {
                z-index: 2;
                background-color: #f5f5f5;
                border-color: #777;
            }

            .list-group-item {
                word-wrap: break-word;
            }

            .folder {
                padding-top: 15px;
            }

            .div-scroll {
                height: 80vh;
                overflow: hidden auto;
            }
            .nowrap {
                white-space: nowrap;
            }

        </style>
    @else
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-4x" style="color: red"></i>
                <h3 class="card-title" style="color: red"> ACCESO DENEGADO </h3>
                <h3 class="card-text" style="color: red">No tiene permiso para usar esta aplicación, por favor comuníquese a la ext: 102 o escribanos al correo electrónico: auxsistemas@estradavelasquez.com para obtener acceso.</h3>
            </div>
        </div>
    @endcan


    @push('javascript')
        <script>
            $(document).ready(function () {
                $('.table-container tr').on('click', function () {
                    $('#' + $(this).data('display')).toggle();
                });
                $('#table-log').DataTable({
                    "order": [$('#table-log').data('orderingIndex'), 'desc'],
                    "stateSave": true,
                    "stateSaveCallback": function (settings, data) {
                        window.localStorage.setItem("datatable", JSON.stringify(data));
                    },
                    "stateLoadCallback": function (settings) {
                        var data = JSON.parse(window.localStorage.getItem("datatable"));
                        if (data) data.start = 0;
                        return data;
                    },
                    language: {
                        processing: "Procesando...",
                        search: "Buscar&nbsp;:",
                        lengthMenu: "Mostrar _MENU_ registros",
                        info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                        infoFiltered: "(filtrado de un total de _MAX_ registros)",
                        infoPostFix: "",
                        loadingRecords: "Cargando...",
                        zeroRecords: "No se encontraron resultados",
                        emptyTable: "Ningún registro disponible en esta tabla :C",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "Ultimo"
                        },
                        aria: {
                            sortAscending: ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    },
                });
                $('#delete-log, #clean-log, #delete-all-log').click(function () {
                    return confirm('¿Esta seguro?');
                });
            });
        </script>
    @endpush
@stop


{{--
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="robots" content="noindex, nofollow">
  <title>Logs de evpiu</title>
  <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">

</head>
<body>

<!-- jQuery for Bootstrap -->

</body>
</html>
--}}
