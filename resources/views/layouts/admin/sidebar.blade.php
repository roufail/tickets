<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.tickets.index') }}" class="brand-link">
      <img src="{{ asset('img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{{ __('sidebar.site_title') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
         <a>{{ auth()->user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview {{ Request::is(app()->getLocale().'/admin/tickets') || Request::is(app()->getLocale().'/admin/tickets/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is(app()->getLocale().'/admin/tickets') || Request::is(app()->getLocale().'admin/tickets/*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-ticket-alt"></i>
              <p>
                {{ __('sidebar.menu.tickets') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item">
              <a href="{{ route('admin.tickets.index') }}" class="nav-link {{ Request::is(app()->getLocale().'/admin/tickets') ? 'active' : '' }}">
                  <i class="fas fa-ticket-alt nav-icon"></i>
                  <p>{{ __('sidebar.menu.your_tickets') }}</p>
                </a>
              </li>
              @can('Create Ticket')
              <li class="nav-item">
                <a href="{{ route('admin.tickets.create') }}" class="nav-link {{ Request::is(app()->getLocale().'/admin/tickets/*') ? 'active' : '' }}">
                  <i class="fa fa-plus-circle nav-icon"></i>
                  <p>{{ __('sidebar.menu.create_ticket') }}</p>
                </a>
              </li>
              @endcan


            </ul>
          </li>


          {{-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Simple Link
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li> --}}


          @can('Manage Users')
          <li class="nav-item has-treeview {{ Request::is(app()->getLocale().'/admin/users') || Request::is(app()->getLocale().'/admin/users/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is(app()->getLocale().'/admin/users') || Request::is(app()->getLocale().'/admin/users/*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                {{ __('sidebar.menu.manage_users') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item">
              <a href="{{ route('admin.users.index') }}" class="nav-link {{ Request::is(app()->getLocale().'/admin/users') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                  <p>{{ __('sidebar.menu.users') }}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.users.create') }}" class="nav-link {{ Request::is(app()->getLocale().'/admin/users/*') ? 'active' : '' }}">
                  <i class="fa fa-plus-circle nav-icon"></i>
                  <p>{{ __('sidebar.menu.create_user') }}</p>
                </a>
              </li>
            </ul>
          </li>

          @endcan


          @if(auth()->user()->hasRole(10))

          <li class="nav-item has-treeview {{ Request::is(app()->getLocale().'/admin/roles') || Request::is(app()->getLocale().'/admin/roles/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is(app()->getLocale().'/admin/roles') || Request::is(app()->getLocale().'/admin/roles/*') ? 'active' : '' }}">
              {{-- <i class="nav-icon fas fa-users"></i> --}}
              <i class="nav-icon fas fa-dice"></i>
              <p>
                {{ __('sidebar.menu.manage_roles') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item">
              <a href="{{ route('admin.roles.index') }}" class="nav-link {{ Request::is(app()->getLocale().'/admin/roles') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-dice"></i>
                  <p>{{ __('sidebar.menu.roles') }}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.roles.create') }}" class="nav-link {{ Request::is(app()->getLocale().'/admin/roles/*') ? 'active' : '' }}">
                  <i class="fa fa-plus-circle nav-icon"></i>
                  <p>{{ __('sidebar.menu.create_role') }}</p>
                </a>
              </li>
            </ul>
          </li>
          @endif

          <li class="nav-item">

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="javascript:;"  onclick="$(this).closest('form').submit()" class="nav-link">
                    <i class="fas fa-user-lock nav-icon"></i>
                   <p>
                       {{ __('sidebar.menu.logout') }}
                     </p>
                   </a>
            </form>



          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
