<ul>
    <li><a href="{{ route($guard . '.dashboard.index') }}"
            class="{{ request()->routeIs('*.dashboard.*') ? 'active' : '' }}">
            <i class="ri-dashboard-3-line"></i>{{ __('Dashboard') }}</a></li>

    <!-- Core Business Operations -->
    <li><a href="{{ route($guard . '.inspections.index') }}"
            class="{{ request()->routeIs('*.inspections.*') ? 'active' : '' }}">
            <i class="ri-file-text-line"></i>{{ __('Keuringen') }}</a></li>

    <li><a href="{{ route('documents.index') }}" class="{{ request()->routeIs('documents.*') ? 'active' : '' }}">
            <i class="ri-folder-line"></i> {{ __('Documenten') }}</a></li>

    {{-- <li><a href="{{ route('agenda.index') }}" class="{{ request()->routeIs('agenda.*') ? 'active' : '' }}">
            <i class="ri-calendar-2-line"></i> {{ __('Agenda') }}</a></li> --}}

    <!-- Business Services -->
    <li class="menu-group">
        <span class="menu-title"><i class="ri-stack-line"></i> {{ __('Diensten Beheer') }}</span>
        <ul class="submenu">
            <li><a href="{{ route('dienst.index') }}" class="{{ request()->routeIs('dienst.*') ? 'active' : '' }}">
                    <i class="ri-list-check"></i> {{ __('Alle Diensten') }}</a></li>
            <li><a href="{{ route('service.index') }}" class="{{ request()->routeIs('service.*') ? 'active' : '' }}">
                    <i class="ri-price-tag-line"></i> {{ __('Service Pakketten') }}</a></li>
        </ul>
    </li>

    <!-- User Management -->
    <li class="menu-group">
        <span class="menu-title"><i class="ri-team-line"></i> {{ __('Gebruikers') }}</span>
        <ul class="submenu">
            <li><a href="{{ route('client.index') }}" class="{{ request()->routeIs('client.*') ? 'active' : '' }}">
                    <i class="ri-user-star-line"></i> {{ __('Klanten') }}</a></li>
            <li><a href="{{ route('employee.index') }}"
                    class="{{ request()->routeIs('employee.*') ? 'active' : '' }}">
                    <i class="ri-user-settings-line"></i> {{ __('Medewerkers') }}</a></li>
        </ul>
    </li>

    <!-- Marketing -->
    <li class="menu-group">
        <span class="menu-title"><i class="ri-advertisement-line"></i> {{ __('Marketing') }}</span>
        <ul class="submenu">
            <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">
                    <i class="ri-article-line"></i> {{ __('Blog') }}</a></li>
            <li><a href="{{ route('coupon.index') }}" class="{{ request()->routeIs('coupon.*') ? 'active' : '' }}">
                    <i class="ri-coupon-line"></i> {{ __('Coupons') }}</a></li>
            <li><a href="{{ route('combi_discount.index') }}" class="{{ request()->routeIs('combi_discount.*') ? 'active' : '' }}">
                    <i class="ri-price-tag-3-line"></i> {{ __('Combi-kortingen') }}</a></li>
        </ul>
    </li>

    <!-- Settings -->
    <li class="menu-group">
        <span class="menu-title"><i class="ri-settings-5-line"></i> {{ __('Configuratie') }}</span>
        <ul class="submenu">
            <li><a href="{{ route('setting.index') }}" class="{{ request()->routeIs('setting.*') ? 'active' : '' }}">
                    <i class="ri-tools-line"></i> {{ __('Algemeen') }}</a></li>
            <li><a href="{{ route('status.index') }}" class="{{ request()->routeIs('status.*') ? 'active' : '' }}">
                    <i class="ri-toggle-fill"></i> {{ __('Status Types') }}</a></li>
            {{-- <li><a href="{{ route('role.index') }}" class="{{ request()->routeIs('role.*') ? 'active' : '' }}">
                    <i class="ri-shield-user-line"></i> {{ __('Gebruikersrollen') }}</a></li> --}}
        </ul>
    </li>
</ul>
