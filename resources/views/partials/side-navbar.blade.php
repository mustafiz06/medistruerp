<aside class="main-sidebar elevation-4 sidebar-light-primary">
    <div class="sidebar pt-0 mt-0">

        <!-- Logo -->
        <div class="user-panel text-center py-3">
            <a href="{{ route('home') }}" class="d-block">
                <img src="{{ asset('logo.png') }}" alt="Logo" style="max-height:50px;">
            </a>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('home') }}"
                        class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>

                <!-- Product Settings -->
                <li class="nav-item has-treeview {{ request()->is('productsetting/*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('productsetting/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box-open"></i>
                        <p>
                            {{ __('Products Setting') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('product.index') }}"
                                class="nav-link {{ request()->routeIs('product.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('products') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('category.index') }}"
                                class="nav-link {{ request()->routeIs('category.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Category') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('unit.index') }}"
                                class="nav-link {{ request()->routeIs('unit.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Unit') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('brand.index') }}"
                                class="nav-link {{ request()->routeIs('brand.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Brand') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('country.index') }}"
                                class="nav-link {{ request()->routeIs('country.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Country') }}</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- Stock -->
                <li class="nav-item has-treeview {{ request()->routeIs('stock.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('stock.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            {{ __('Stock') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('stock.openingForm') }}"
                                class="nav-link {{ request()->routeIs('stock.openingForm') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Opening Stock') }}</p>
                            </a>
                        </li>

                        

                        <li class="nav-item">
                            <a href="{{ route('stock.report') }}"
                                class="nav-link {{ request()->routeIs('stock.report') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Stock Report') }}</p>
                            </a>
                        </li>

                        

                    </ul>
                </li>

                <!-- Accounts Settings -->
                <li class="nav-item has-treeview {{ request()->is('accountsetting/*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('accountsetting/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-coins"></i>
                        <p>
                            {{ __('Accounts Setting') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('paymentMethod.index') }}"
                                class="nav-link {{ request()->routeIs('paymentMethod.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Payment Method') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('expenseHead.index') }}"
                                class="nav-link {{ request()->routeIs('expenseHead.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Expense Head') }}</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- Customer -->
                <li class="nav-item has-treeview {{ request()->routeIs('customer.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('customer.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            {{ __('Customers') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('customer.add') }}"
                                class="nav-link {{ request()->routeIs('customer.add') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Add Customer') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('customer.index') }}"
                                class="nav-link {{ request()->routeIs('customer.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Customers list') }}</p>
                            </a>
                        </li>

                    </ul>
                </li>


                <!-- Supplier -->
                <li class="nav-item has-treeview {{ request()->routeIs('supplier.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-store"></i>
                        <p>
                            {{ __('Suppliers') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('supplier.add') }}"
                                class="nav-link {{ request()->routeIs('supplier.add') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Add Supplier') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('supplier.index') }}"
                                class="nav-link {{ request()->routeIs('supplier.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Suppliers list') }}</p>
                            </a>
                        </li>

                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>