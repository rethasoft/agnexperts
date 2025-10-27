<ul>
    @can('read_keuringen')
        <li><a href="{{ route('tenant.inspections.index') }}" class="{{ Request::is('inspections') ? 'active' : '' }}"><i
                    class="ri-file-text-line"></i>{{ __('Keuringen') }}</a></li>
    @endcan
    @can('read_status')
        <li><a href="{{ route('status.index') }}" class="{{ Request::is('statussen') ? 'active' : '' }}"><i
                    class="ri-toggle-fill"></i> {{ __('Statussen') }}</a></li>
    @endcan
    @can('read_dienst')
        <li><a href="{{ route('dienst.index') }}" class="{{ Request::is('dienst') ? 'active' : '' }}"><i
                    class="ri-stack-line"></i> {{ __('Diensten') }}</a></li>
    @endcan
    @can('read_client')
        <li><a href="{{ route('client.index') }}" class="{{ Request::is('client') ? 'active' : '' }}"><i
                    class="ri-group-line"></i> {{ __('Klanten') }}</a></li>
    @endcan
</ul>
