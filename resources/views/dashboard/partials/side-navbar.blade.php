<aside class="main-sidebar elevation-4 main-sidebar elevation-4 sidebar-light-primary">
    <!-- Sidebar -->
    <div class="sidebar pt-0 mt-0">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <a href="" class="name text-dark" target="_blank">
                <img src="" alt="Logo">
            </a>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="#"
                        class="nav-link @if(request()->path() == '/') active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            {{ __('Dashboard') }}
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link
                @if(request()->path() == '/') active
                @endif">
                        <i class="nav-icon fas fas fa-cog"></i>
                        <p>
                            {{ __('General Setting') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{route('category.index')}}"
                                class="nav-link @if(request()->path() == '/category') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Category') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('unit.index')}}"
                                class="nav-link @if(request()->path() == '/unit') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Unit') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('brand.index')}}"
                                class="nav-link @if(request()->path() == '/brand') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Brand') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>