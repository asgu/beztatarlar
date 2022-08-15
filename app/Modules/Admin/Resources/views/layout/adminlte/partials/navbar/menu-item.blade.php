@inject('menuItemHelper', \JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper::class)

@if ($menuItemHelper->isSubmenu($item))

    {{-- Dropdown menu --}}
    @include('Admin::layout.adminlte.partials.navbar.menu-item-dropdown-menu')

@elseif ($menuItemHelper->isLink($item))

    {{-- Link --}}
    @include('Admin::layout.adminlte.partials.navbar.menu-item-link')

@endif
