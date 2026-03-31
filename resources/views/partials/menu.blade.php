<nav class="sidebar-nav">

    {{-- Calendar --}}
    <div class="sidebar-section-label">Principal</div>

    <a href="{{ route('admin.systemCalendar') }}"
       class="sidebar-nav-link {{ request()->is('admin/system-calendar') || request()->is('admin/system-calendar/*') ? 'active' : '' }}">
        <i class="fas fa-calendar nav-icon"></i>
        {{ trans('global.systemCalendar') }}
    </a>

    @can('user_management_access')
        {{-- User Management group --}}
        <div class="sidebar-section-label">Gestión</div>

        <button class="sidebar-dropdown-btn"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menu-users"
                aria-expanded="{{ request()->is('admin/permissions*') || request()->is('admin/roles*') || request()->is('admin/users*') ? 'true' : 'false' }}"
                aria-controls="menu-users">
            <i class="fas fa-users nav-icon"></i>
            {{ trans('cruds.userManagement.title') }}
            <i class="fas fa-chevron-down chevron"></i>
        </button>

        <div class="collapse sidebar-sub-items {{ request()->is('admin/permissions*') || request()->is('admin/roles*') || request()->is('admin/users*') ? 'show' : '' }}"
             id="menu-users">
            @can('permission_access')
                <a href="{{ route('admin.permissions.index') }}"
                   class="sidebar-nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                    <i class="fas fa-unlock-alt nav-icon"></i>
                    {{ trans('cruds.permission.title') }}
                </a>
            @endcan

            @can('role_access')
                <a href="{{ route('admin.roles.index') }}"
                   class="sidebar-nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase nav-icon"></i>
                    {{ trans('cruds.role.title') }}
                </a>
            @endcan

            @can('user_access')
                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                    <i class="fas fa-user nav-icon"></i>
                    {{ trans('cruds.user.title') }}
                </a>
            @endcan
        </div>
    @endcan

    {{-- Resources --}}
    <div class="sidebar-section-label">Recursos</div>

    @can('establishment_access')
        <a href="{{ route('admin.establishments.index') }}"
           class="sidebar-nav-link {{ request()->is('admin/establishments') || request()->is('admin/establishments/*') ? 'active' : '' }}">
            <i class="fas fa-city nav-icon"></i>
            Establecimientos
        </a>
    @endcan

    @can('venue_access')
        <a href="{{ route('admin.venues.index') }}"
           class="sidebar-nav-link {{ request()->is('admin/venues') || request()->is('admin/venues/*') ? 'active' : '' }}">
            <i class="fas fa-building nav-icon"></i>
            {{ trans('cruds.venue.title') }}
        </a>
    @endcan

    @can('equipment_access')
        <a href="{{ route('admin.equipment.index') }}"
           class="sidebar-nav-link {{ request()->is('admin/equipment') || request()->is('admin/equipment/*') ? 'active' : '' }}">
            <i class="fas fa-tools nav-icon"></i>
            {{ trans('cruds.equipment.title') }}
        </a>
    @endcan

    {{-- Scheduling --}}
    <div class="sidebar-section-label">Agenda</div>

    @can('event_access')
        <a href="{{ route('admin.events.index') }}"
           class="sidebar-nav-link {{ request()->is('admin/events') || request()->is('admin/events/*') ? 'active' : '' }}">
            <i class="fas fa-calendar-day nav-icon"></i>
            {{ trans('cruds.event.title') }}
        </a>
    @endcan

    @can('meeting_access')
        <a href="{{ route('admin.meetings.index') }}"
           class="sidebar-nav-link {{ request()->is('admin/meetings') || request()->is('admin/meetings/*') ? 'active' : '' }}">
            <i class="fas fa-users nav-icon"></i>
            {{ trans('cruds.meeting.title') }}
        </a>
    @endcan

    @can('equipment_loan_access')
        <a href="{{ route('admin.equipment-loans.index') }}"
           class="sidebar-nav-link {{ request()->is('admin/equipment-loans') || request()->is('admin/equipment-loans/*') ? 'active' : '' }}">
            <i class="fas fa-hand-holding nav-icon"></i>
            {{ trans('cruds.equipmentLoan.title') }}
        </a>
    @endcan

</nav>

