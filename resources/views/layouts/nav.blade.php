<div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="{{ asset('assets/images/logo-dark-sm.png') }}" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text">Lamonte</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-first-page'></i>
                </div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <div class="parent-icon"><i class='bx bx-home'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>
                <li class="menu-label">Menu</li>
                <li>
                    <a href="{{ route('campaign') }}">
                        <div class="parent-icon"><i class='bx bx-link' ></i>
                        </div>
                        <div class="menu-title">Campaign</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('campaign-payment') }}">
                        <div class="parent-icon"><i class='bx bx-link' ></i>
                        </div>
                        <div class="menu-title">Campaign Payment</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer-service') }}">
                        <div class="parent-icon"><i class='bx bx-user-pin' ></i>
                        </div>
                        <div class="menu-title">Customer Service</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('produk') }}">
                        <div class="parent-icon"><i class='bx bx-box' ></i>
                        </div>
                        <div class="menu-title">Paket</div>
                    </a>
                </li>
                <li class="menu-label">Fitur</li>
                <li>
                    <a href="{{ route('logout') }}">
                        <div class="parent-icon"><i class='bx bx-log-out' ></i>
                        </div>
                        <div class="menu-title">Keluar</div>
                    </a>
                </li>
            </ul>
            <!--end navigation-->
        </div>