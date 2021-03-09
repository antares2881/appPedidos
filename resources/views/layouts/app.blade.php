<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">

        {{-- Estilos admin --}}
        <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}">
        <link href="{{asset('assets/vendor/fonts/circular-std/style.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('assets/libs/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('assets/vendor/fonts/fontawesome/css/fontawesome-all.css')}}">
        <link rel="stylesheet" href="{{asset('assets/vendor/vector-map/jqvmap.css')}}">
        <link rel="stylesheet" href="{{asset('assets/vendor/jvectormap/jquery-jvectormap-2.0.2.css')}}">
        <link rel="stylesheet" href="{{asset('assets/vendor/fonts/flag-icon-css/flag-icon.min.css')}}">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="dashboard-main-wrapper" id="app">
            <div class="dashboard-header">
                <nav class="navbar navbar-expand-lg bg-white fixed-top">
                    <a class="navbar-brand" href="/">Pedidos App</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse " id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto navbar-right-top">
                                <li class="nav-item dropdown nav-user">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }} <i class="fas fa-angle-down ml-2"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt mr-2"></i>{{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            {{-- <li class="nav-item dropdown nav-user">
                                <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="assets/images/avatar-1.jpg" alt="" class="user-avatar-md rounded-circle">{{ Auth::user()->name }}</a>
                                <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                    <div class="nav-user-info">
                                        <h5 class="mb-0 text-white nav-user-name">John Abraham </h5>
                                        <span class="status"></span><span class="ml-2">Available</span>
                                    </div>
                                    <a class="dropdown-item" href="#"><i class="fas fa-user mr-2"></i>Account</a>
                                    <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Setting</a>
                                    <a class="dropdown-item" href="#"><i class="fas fa-power-off mr-2"></i>Logout</a>
                                </div>
                            </li> --}}
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="nav-left-sidebar sidebar-dark">
                <div class="menu-list">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="d-xl-none d-lg-none" href="#"></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav flex-column">
                                <li class="nav-divider">
                                    Menu
                                </li>
                                @if (Auth::user()->role_id == 1)
                                    <li class="nav-item">
                                        <a class="nav-link @yield('admin')" href="#" data-toggle="collapse" aria-expanded="false" data-target="#admin" aria-controls="admin"><i class="fas fa-cog"></i>Admin</a>
                                        <div id="admin" class="collapse submenu" style="">
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/clientes">Clientes</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/combos">Combos</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/productos">Productos </a>
                                                </li> 
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/productos-combo">Productos con combo</a>
                                                </li>                                        
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/usuarios">Usuarios </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>                                    
                                @endif

                                <li class="nav-item">
                                    <a class="nav-link @yield('facturas')" href="#" data-toggle="collapse" aria-expanded="false" data-target="#facturas" aria-controls="facturas"><i class="fas fa-dollar-sign"></i>Facturas</a>
                                    <div id="facturas" class="collapse submenu" style="">
                                        <ul class="nav flex-column">
                                            @if (Auth::user()->role_id == 1)
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/realizar-facturas">Realizar facturas</a>
                                                </li>
                                            @endif
                                            <li class="nav-item">
                                                <a class="nav-link" href="/stocks">Historial facturas</a>
                                            </li>                                        
                                        </ul>
                                    </div>
                                </li>

                                <li class="nav-item">

                                    <a class="nav-link @yield('historial')" href="#" data-toggle="collapse" aria-expanded="false" data-target="#historial" aria-controls="historial"><i class="fas fa-file-alt"></i>Historial</a>
                                    <div id="historial" class="collapse submenu" style="">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link" href="/facturacion">Facturacion</a>
                                            </li>                                        
                                            <li class="nav-item">
                                                <a class="nav-link" href="/listado-calox">Pedidos</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="/transferencias">Transferencias</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @if (Auth::user()->role_id == 1)
                                    <li class="nav-item">

                                        <a class="nav-link @yield('inventario')" href="#" data-toggle="collapse" aria-expanded="false" data-target="#inventario" aria-controls="inventario"><i class="fas fa-dolly-flatbed"></i>Inventario</a>
                                        <div id="inventario" class="collapse submenu" style="">
                                            <ul class="nav flex-column">
                                                
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/stocks">Productos en Stock</a>
                                                </li>                                        
                                            </ul>
                                        </div>
                                    </li>
                                @endif          
                                <li class="nav-item">

                                        <a class="nav-link @yield('pedidos')" href="#" data-toggle="collapse" aria-expanded="false" data-target="#pedidos" aria-controls="pedidos"><i class="fas fa-shopping-cart"></i>Pedidos</a>
                                        <div id="pedidos" class="collapse submenu" style="">
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/home">Pedidos Clientes </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/pedidos-calox">Pedidos Calox</a>
                                                </li>                                        
                                            </ul>
                                        </div>
                                    </li>  
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
            <div class="dashboard-wrapper">            
                @yield('content')
            </div>
        </div>
    </body>
</html>
