<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                data-toggle="dropdown" aria-expanded="false"> <i class="fas fa-user mx-1"></i></a>
            <!-- Dropdown menu -->
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                    <a class="dropdown-item" href="{{ url('/admin/profile-edit') }}">My account</a>
                </li>

                <li>
                    <a class="dropdown-item" href="{{ url('/logout') }}">Log out</a>
                </li>
            </ul>
        </li>
    </ul>

</nav>
