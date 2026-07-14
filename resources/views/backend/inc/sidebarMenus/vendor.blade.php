                <!-- @role('vendor')
    <li><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
    @can('view_products')
        <li><a href="{{ route('vendor.products.index') }}">My Products</a></li>
    @endcan
    @can('view_orders')
        <li><a href="{{ route('vendor.orders.index') }}">My Orders</a></li>
    @endcan
@endrole -->
{{-- resources/views/backend/inc/sidebarMenus/vendor.blade.php --}}

<li class="nav-item">
    <a class="nav-link" href="{{ route('vendor.dashboard') }}">
        <i data-feather="home"></i>
        <span>{{ localize('Vendor Dashboard') }}</span>
    </a>
</li>

@can('view_products')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('vendor.products.index') }}">
            <i data-feather="box"></i>
            <span>{{ localize('My Products') }}</span>
        </a>
    </li>
@endcan

{{-- Add more vendor-specific items here if needed --}}
