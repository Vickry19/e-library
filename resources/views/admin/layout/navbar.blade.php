<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
        </a>
    </li>
</ul>

<ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
           id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false">
            <b class="mr-2">{{ Auth::user()->nama }}</b>
            @php
                $avatar = \App\Helpers\AvatarHelper::generate(Auth::user()->nama);
            @endphp
            <div class="avatar-initial"
                 style="width: 38px; height: 38px; background-color: {{ $avatar['color'] }}; font-size: 15px;">
                {{ $avatar['initial'] }}
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow-lg" style="min-width: 220px;">
            <div class="dropdown-header text-center">
                <div class="avatar-initial mx-auto mb-2"
                     style="width: 60px; height: 60px; background-color: {{ $avatar['color'] }}; font-size: 22px;">
                    {{ $avatar['initial'] }}
                </div>
                <strong>{{ Auth::user()->nama }}</strong><br>
                <small class="text-muted">{{ Auth::user()->email }}</small>
            </div>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('admin.profil') }}">
                <i class="fas fa-user mr-2"></i> Profil Saya
            </a>
            <a class="dropdown-item" href="{{ route('admin.ganti-password') }}">
                <i class="fas fa-lock mr-2"></i> Ganti Password
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
        </div>
    </li>
</ul>