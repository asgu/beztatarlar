@inject('menuItemHelper', \JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper::class)

@if ($menuItemHelper->isHeader($item))

    {{-- Header --}}
    @include('Admin::layout.adminlte.partials.sidebar.menu-item-header')

@elseif ($menuItemHelper->isSubmenu($item))

    {{-- Treeview menu --}}
    @include('Admin::layout.adminlte.partials.sidebar.menu-item-treeview-menu')

@elseif ($menuItemHelper->isLink($item))

    {{-- Link --}}
    @include('Admin::layout.adminlte.partials.sidebar.menu-item-link')

@endif
