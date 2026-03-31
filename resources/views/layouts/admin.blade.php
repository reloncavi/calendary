<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Calendary') }}</title>

    {{-- Font Awesome 6 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    {{-- DataTables Bootstrap 5 --}}
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/select/1.7.0/css/select.bootstrap5.min.css" rel="stylesheet">
    {{-- Select2 Bootstrap 5 theme --}}
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    {{-- Flatpickr --}}
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

    {{-- Compiled CSS (Bootstrap 5 + custom theme) --}}
    @vite('resources/sass/app.scss')

    @yield('styles')
</head>

<body>

    {{-- ====== Header ====== --}}
    <header id="app-header">
        <button class="header-icon-btn d-lg-none" id="sidebar-toggle" aria-label="Menú">
            <i class="fas fa-bars"></i>
        </button>

        <a href="{{ route('admin.systemCalendar') }}" class="header-brand">
            <div class="brand-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <span class="d-none d-sm-inline">{{ config('app.name', 'Calendary') }}</span>
        </a>

        <div class="header-actions">
            @if(count(config('panel.available_languages', [])) > 1)
                <div class="dropdown">
                    <button class="header-icon-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Idioma">
                        <i class="fas fa-globe"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width:140px;border-radius:.875rem;border-color:var(--border);box-shadow:0 8px 24px rgba(0,0,0,.08)">
                        @foreach(config('panel.available_languages') as $langLocale => $langName)
                            <li>
                                <a class="dropdown-item" href="{{ url()->current() }}?change_language={{ $langLocale }}" style="font-size:.875rem;">
                                    {{ strtoupper($langLocale) }} — {{ $langName }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="dropdown">
                <a class="header-user" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="d-none d-md-inline" style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ auth()->user()->name ?? 'Usuario' }}
                    </span>
                    <i class="fas fa-chevron-down" style="font-size:.65rem;color:var(--text-muted);"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width:180px;border-radius:.875rem;border-color:var(--border);box-shadow:0 8px 24px rgba(0,0,0,.08)">
                    <li>
                        <a class="dropdown-item text-danger" style="font-size:.875rem;" href="#"
                           onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>{{ trans('global.logout') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    {{-- ====== Sidebar ====== --}}
    <div id="app-sidebar">
        @include('partials.menu')
    </div>

    {{-- Mobile overlay --}}
    <div id="sidebar-overlay"></div>

    {{-- ====== Main content ====== --}}
    <main id="app-content">
        @if(Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-{{ Session::get('alert-class') === 'alert-success' ? 'check-circle' : (Session::get('alert-class') === 'alert-danger' ? 'exclamation-circle' : 'info-circle') }} me-2"></i>
                {{ Session::get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->count() > 0)
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <form id="logoutform" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

    {{-- ====== Scripts — loaded in order at end of body ====== --}}
    {{-- jQuery (must be first, before DataTables/Select2) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- DataTables + plugins --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    {{-- Select2 --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.full.min.js"></script>
    {{-- Flatpickr + Spanish locale --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <script>
    (function ($) {
        var locale = '{{ app()->getLocale() }}';
        var langUrl = locale === 'es'
            ? 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            : 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/en-GB.json';

        // DataTables global defaults
        $.extend(true, $.fn.dataTable.defaults, {
            language: { url: langUrl },
            columnDefs: [
                { orderable: false, className: 'select-checkbox', targets: 0 },
                { orderable: false, searchable: false, targets: -1 },
            ],
            select: { style: 'multi+shift', selector: 'td:first-child' },
            order: [],
            scrollX: true,
            pageLength: 100,
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copy',   className: 'btn btn-sm btn-outline-secondary' },
                { extend: 'csv',    className: 'btn btn-sm btn-outline-secondary' },
                { extend: 'excel',  className: 'btn btn-sm btn-outline-secondary' },
                { extend: 'pdf',    className: 'btn btn-sm btn-outline-secondary' },
                { extend: 'print',  className: 'btn btn-sm btn-outline-secondary' },
                { extend: 'colvis', className: 'btn btn-sm btn-outline-secondary' },
            ],
        });

        $(function () {
            // Flatpickr datetime pickers
            var fpLocale = flatpickr.l10ns.es;
            var dtConfig = { enableTime: true, dateFormat: 'Y-m-d H:i:S', time_24hr: true, locale: fpLocale };
            var dConfig  = { enableTime: false, dateFormat: 'Y-m-d', locale: fpLocale };
            document.querySelectorAll('.flatpickr-datetime').forEach(function (el) { flatpickr(el, dtConfig); });
            document.querySelectorAll('.flatpickr-date').forEach(function (el) { flatpickr(el, dConfig); });

            // Select2
            $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });

            // Sidebar toggle (mobile)
            var $sidebar = $('#app-sidebar');
            var $overlay = $('#sidebar-overlay');

            $('#sidebar-toggle').on('click', function () {
                $sidebar.toggleClass('show');
                $overlay.toggleClass('show');
            });
            $overlay.on('click', function () {
                $sidebar.removeClass('show');
                $overlay.removeClass('show');
            });
        });
    })(jQuery);
    </script>

    @yield('scripts')
</body>
</html>

