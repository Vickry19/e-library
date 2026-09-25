<a href="{{ route('admin.dashboard') }}" class="brand-link">
    <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
         class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">E-Library UNM</span>
</a>

<div class="sidebar">
    {{-- User Panel --}}
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            @php
                $avatar = \App\Helpers\AvatarHelper::generate(Auth::user()->nama);
            @endphp
            <div class="avatar-initial"
                 style="width: 35px; height: 35px; background-color: {{ $avatar['color'] }}; font-size: 13px;">
                {{ $avatar['initial'] }}
            </div>
        </div>
        <div class="info">
            <a href="{{ route('admin.profil') }}" class="d-block">{{ Auth::user()->nama }}</a>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" role="menu">

            {{-- Dashboard --}}
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            {{-- Header Data Master --}}
            <li class="nav-header text-uppercase"
                style="font-size: 0.75rem; letter-spacing: 1px; color: #6c757d; padding: 10px 15px;">
                DATA MASTER
            </li>

            {{-- Kategori --}}
            <li class="nav-item">
                <a href="{{ route('admin.master.kategori.index') }}"
                   class="nav-link {{ Request::segment(3) == 'kategori' ? 'active' : '' }}">
                    <i class="fas fa-tags nav-icon"></i>
                    <p>Kategori</p>
                </a>
            </li>

            {{-- Buku --}}
            <li class="nav-item">
                <a href="{{ route('admin.master.buku.index') }}"
                   class="nav-link {{ Request::segment(3) == 'buku' ? 'active' : '' }}">
                    <i class="fas fa-book nav-icon"></i>
                    <p>Buku</p>
                </a>
            </li>

            {{-- User --}}
            <li class="nav-item">
                <a href="{{ route('admin.master.user.index') }}"
                   class="nav-link {{ Request::segment(3) == 'user' ? 'active' : '' }}">
                    <i class="fas fa-users nav-icon"></i>
                    <p>User</p>
                </a>
            </li>

            {{-- Header Data Transaksi --}}
            <li class="nav-header text-uppercase"
                style="font-size: 0.75rem; letter-spacing: 1px; color: #6c757d; padding: 10px 15px;">
                DATA TRANSAKSI
            </li>

            {{-- Booking --}}
            <li class="nav-item">
                <a href="{{ route('admin.transaksi.booking.index') }}"
                   class="nav-link {{ Request::segment(3) == 'booking' ? 'active' : '' }}">
                    <i class="fas fa-receipt nav-icon"></i>
                    <p>Booking</p>
                </a>
            </li>

            {{-- Peminjaman --}}
            <li class="nav-item">
                <a href="{{ route('admin.transaksi.peminjaman.index') }}"
                   class="nav-link {{ Request::segment(3) == 'peminjaman' ? 'active' : '' }}">
                    <i class="fas fa-book-reader nav-icon"></i>
                    <p>Peminjaman Buku</p>
                </a>
            </li>

            {{-- Pengembalian --}}
            <li class="nav-item">
                <a href="{{ route('admin.transaksi.peminjaman.pengembalian') }}"
                   class="nav-link {{ Request::segment(3) == 'pengembalian' ? 'active' : '' }}">
                    <i class="fas fa-undo-alt nav-icon"></i>
                    <p>Pengembalian Buku</p>
                </a>
            </li>

        </ul>
    </nav>
</div>