<nav class="navbar navbar-expand navbar-dark navbar-member sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-book-reader mr-2"></i>E-Library <b>UNM</b>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample02">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExample02">
            <ul class="navbar-nav mr-auto">
                @auth
                    <li class="nav-item {{ Request::segment(1) == 'member' && Request::segment(2) == 'data-keranjang' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('member.dataKeranjang', auth()->user()->id) }}">
                            <i class="fas fa-shopping-cart"></i> Keranjang
                            <span class="badge badge-danger">{{ Auth::user()->totalKeranjang() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('member.dataBooking', auth()->user()->id) }}">
                            <i class="fas fa-receipt"></i> Booking
                            <span class="badge badge-primary">{{ Auth::user()->totalBooking() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('member.sedangPinjam', auth()->user()->id) }}">
                            <i class="fas fa-book-reader"></i> Sedang Pinjam
                            <span class="badge badge-light">{{ Auth::user()->totalSedangPinjam() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('member.riwayatPinjam', auth()->user()->id) }}">
                            <i class="fas fa-history"></i> Riwayat Peminjaman
                            <span class="badge badge-success">{{ Auth::user()->totalRiwayatPinjam() }}</span>
                        </a>
                    </li>
                @endauth
            </ul>

            <ul class="navbar-nav ml-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                           data-toggle="dropdown" aria-expanded="false">
                            <span class="font-weight-bold mr-2">{{ Auth::user()->nama }}</span>
                            {{-- Avatar Huruf --}}
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
                            <a class="dropdown-item" href="{{ route('member.profil') }}">
                                <i class="fas fa-user mr-2"></i> Profil Saya
                            </a>
                            <a class="dropdown-item" href="{{ route('member.ganti-password') }}">
                                <i class="fas fa-lock mr-2"></i> Ganti Password
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#loginModal">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>