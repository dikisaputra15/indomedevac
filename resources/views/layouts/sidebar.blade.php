 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/home" class="brand-link">
      <img src="{{asset('AdminLTE')}}/dist/img/cclogo.jpeg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">MEDEVAC</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-header">Hi, {{auth()->user()->name}}</li>
          <li class="nav-item {{ request()->is('/') ? 'menu-open' : '' }}">
            <a href="/home" class="nav-link">
             <i class="bi bi-house-door-fill"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <!-- @role('admin') -->
           <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Master Data
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('airportdata') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Airport</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('hospitaldata') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Hospital</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{ url('embessydata') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Embassy</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{ url('aircharterdata') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Aircharter</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('roles') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Role</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{ url('user') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- @endrole -->

          <li class="nav-item {{ request()->is('hospital') ? 'menu-open' : '' }}">
            <a href="{{ url('hospital') }}" class="nav-link">
            <i class="bi bi-hospital"></i>
              <p>
                Hospitals
              </p>
            </a>
          </li>

          <li class="nav-item {{ request()->is('airports') ? 'menu-open' : '' }}">
            <a href="{{ url('airports') }}" class="nav-link">
            <i class="bi bi-airplane"></i>
              <p>
                Airports
              </p>
            </a>
          </li>

          <li class="nav-item {{ request()->is('embassiees') ? 'menu-open' : '' }}">
            <a href="{{ url('embassiees') }}" class="nav-link">
            <i class="bi bi-bank"></i>
              <p>
                Embassiees
              </p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
