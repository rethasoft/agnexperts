<ul>
    <li><a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
        <i class="ri-dashboard-3-line"></i>{{ __('Dashboard') }}</a></li>
    
    <li><a href="{{ route($guard . '.inspections.index') }}" class="{{ request()->routeIs('*.inspections.*') ? 'active' : '' }}">
        <i class="ri-file-text-line"></i>{{ __('Keuringen') }}</a></li>
</ul>
