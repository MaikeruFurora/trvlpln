<div class="header-bg">
            <!-- Navigation Bar-->
            <header id="topnav">
                <div class="topbar-main">
                    <div class="container-fluid">

                        <!-- Logo-->
                        <div>
                            
                            <a href="index.html" class="logo">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="" height="30"> 
                            </a>

                        </div>
                        <!-- End Logo-->

                        <div class="menu-extras topbar-custom navbar p-0">

                            <ul class="list-inline ml-auto mb-0">
                        
                                <li class="menu-item list-inline-item">
                                    <!-- Mobile menu toggle-->
                                    <a class="navbar-toggle nav-link">
                                        <div class="lines">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </a>
                                    <!-- End mobile menu toggle-->
                                </li>

                            </ul>

                        </div>
                        <!-- end menu-extras -->

                        <div class="clearfix"></div>

                    </div> <!-- end container -->
                </div>
                <!-- end topbar-main -->

                <!-- MENU Start -->
                <div class="navbar-custom">
                    <div class="container-fluid">
                        
                        <div id="navigation">

                            <!-- Navigation Menu-->
                            <ul class="navigation-menu">

                                <li class="has-submenu">
                                    <a href="{{ auth()->user()->type=="admin"?route('authenticate.admin') : route('authenticate.supervisor') }}"><i class="far fa-calendar-alt"></i> Activity Callendar</a>
                                </li>
                                @if (auth()->user()->type=="admin")
                                    <li class="has-submenu">
                                        <a href="{{ route('authenticate.user') }}"><i class="fas fa-users"></i> Users</a>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="{{ route('authenticate.actvtylist') }}"><i class="fas fa-th-list"></i> Activity</a>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="{{ route('authenticate.audit') }}"><i class="fas fa-th-list"></i> Audit Trail</a>
                                    </li>
                                    @endif
                                    <li class="has-submenu">
                                        <a class="text-danger" style="cursor:pointer"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="dripicons-exit"></i>Sign out</a>
                                        <form id="logout-form" action="{{ route('authenticate.signout') }}" method="POST" class="d-none">@csrf</form>
                                    </li>
                                </ul>
                                <!-- End navigation menu -->
                        </div> <!-- end #navigation -->
                    </div> <!-- end container -->
                </div> <!-- end navbar-custom -->
            </header>
            <!-- End Navigation Bar-->

        </div>