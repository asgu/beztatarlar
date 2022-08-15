@inject('menuItemHelper', \JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper::class)

@if ($menuItemHelper->isSubmenu($item))

    {{-- Dropdown submenu --}}
    @include('Admin::layout.adminlte.partials.navbar.dropdown-item-submenu')

@elseif ($menuItemHelper->isLink($item))

    {{-- Dropdown link --}}
    @include('Admin::layout.adminlte.partials.navbar.dropdown-item-link')

@endif
