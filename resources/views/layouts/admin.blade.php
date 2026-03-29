<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Calendary') }}</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome 6 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    {{-- CoreUI 4 --}}
    <link href="https://unpkg.com/@coreui/coreui@4.3.0/dist/css/coreui.min.css" rel="stylesheet">
    {{-- DataTables Bootstrap 5 --}}
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/select/1.7.0/css/select.bootstrap5.min.css" rel="stylesheet">
    {{-- Select2 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    {{-- Flatpickr --}}
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

    @yield('styles')
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed pace-done sidebar-lg-show">
    <header class="app-header navbar">
        <button class="navbar-toggler sidebar-toggler d-lg-none me-auto" type="button" data-coreui-toggle="sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">
            <i class="fas fa-calendar-alt me-2"></i>
            <span class="d-none d-md-inline">{{ config('app.name', 'Calendary') }}</span>
        </a>
        <button class="navbar-toggler sidebar-toggler d-none d-lg-block" type="button" data-coreui-toggle="sidebar-lg">
            <span class="navbar-toggler-icon"></span>
        </button>

        <ul class="nav navbar-nav ms-auto">
            @if(count(config('panel.available_languages', [])) > 1)
                <li class="nav-item dropdown d-none d-md-block">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                        {{ strtoupper(app()->getLocale()) }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        @foreach(config('panel.available_languages') as $langLocale => $langName)
                            <a class="dropdown-item" href="{{ url()->current() }}?change_language={{ $langLocale }}">
                                {{ strtoupper($langLocale) }} ({{ $langName }})
                            </a>
                        @endforeach
                    </div>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </li>
        </ul>
    </header>

    <div class="app-body">
        @include('partials.menu')
        <main class="main">
            <div class="container-fluid pt-4">

                @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">
                        {{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->count() > 0)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    {{-- jQuery (required by DataTables + Select2) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- CoreUI 4 --}}
    <script src="https://unpkg.com/@coreui/coreui@4.3.0/dist/js/coreui.bundle.min.js"></script>
    {{-- DataTables --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    {{-- Select2 --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.full.min.js"></script>
    {{-- Flatpickr --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <script>
        // DataTables global defaults
        $(function() {
            let locale = '{{ app()->getLocale() }}';
            let langUrl = locale === 'es'
                ? 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                : 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/en-GB.json';

            $.extend(true, $.fn.dataTable.defaults, {
                language: { url: langUrl },
                columnDefs: [
                    { orderable: false, className: 'select-checkbox', targets: 0 },
                    { orderable: false, searchable: false, targets: -1 }
                ],
                select: { style: 'multi+shift', selector: 'td:first-child' },
                order: [],
                scrollX: true,
                pageLength: 100,
                dom: 'lBfrtip',
                buttons: [
                    { extend: 'copy',   className: 'btn btn-sm btn-secondary' },
                    { extend: 'csv',    className: 'btn btn-sm btn-secondary' },
                    { extend: 'excel',  className: 'btn btn-sm btn-secondary' },
                    { extend: 'pdf',    className: 'btn btn-sm btn-secondary' },
                    { extend: 'print',  className: 'btn btn-sm btn-secondary' },
                    { extend: 'colvis', className: 'btn btn-sm btn-secondary' },
                ]
            });
        });

        // Flatpickr datetime pickers
        document.addEventListener('DOMContentLoaded', function() {
            const locale = flatpickr.l10ns.es;
            const dtConfig = { enableTime: true, dateFormat: 'Y-m-d H:i:S', time_24hr: true, locale: locale };
            const dConfig  = { enableTime: false, dateFormat: 'Y-m-d', locale: locale };

            document.querySelectorAll('.flatpickr-datetime').forEach(el => flatpickr(el, dtConfig));
            document.querySelectorAll('.flatpickr-date').forEach(el => flatpickr(el, dConfig));

            // Select2
            $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });
        });
    </script>

    @yield('scripts')
</body>

</html>
