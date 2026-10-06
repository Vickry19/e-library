<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Library UNM | @yield('title')</title>

    {{-- ==================== CSS ==================== --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* ==================== GENERAL ==================== */
        .info-box {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .info-box:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .booking {
            height: 200px;
            object-fit: cover;
        }

        /* ==================== AVATAR INITIAL ==================== */
        .avatar-initial {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            border-radius: 50%;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
            user-select: none;
            border: 2px solid rgba(255,255,255,0.3);
        }

        /* ==================== PAGE HEADER PEMINJAMAN ==================== */
        .page-header-pinjam {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 25px 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .page-header-pinjam::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }

        .header-icon-wrapper {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.2);
        }

        .badge-lg {
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 20px;
        }

        /* ==================== MINI STAT CARDS ==================== */
        .mini-stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            margin-bottom: 15px;
        }

        .mini-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .mini-stat-primary { border-left-color: #007bff; }
        .mini-stat-warning { border-left-color: #ffc107; }
        .mini-stat-success { border-left-color: #28a745; }
        .mini-stat-info    { border-left-color: #17a2b8; }
        .mini-stat-danger  { border-left-color: #dc3545; }

        .mini-stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .mini-stat-primary .mini-stat-icon { background: #e7f1ff; color: #007bff; }
        .mini-stat-warning .mini-stat-icon { background: #fff8e1; color: #ffc107; }
        .mini-stat-success .mini-stat-icon { background: #e9f7ef; color: #28a745; }
        .mini-stat-info .mini-stat-icon    { background: #e7f6f9; color: #17a2b8; }
        .mini-stat-danger .mini-stat-icon  { background: #fdecef; color: #dc3545; }

        .mini-stat-content {
            display: flex;
            flex-direction: column;
        }

        .mini-stat-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .mini-stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: #212529;
            line-height: 1;
        }

        /* ==================== CARD MODERN ==================== */
        .card-modern {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .card-header-modern {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 18px 24px;
        }

        /* ==================== FILTER BAR ==================== */
        .filter-bar {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px dashed #dee2e6;
        }

        .filter-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 6px;
            display: block;
        }

        .form-control-modern {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .form-control-modern:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }

        /* ==================== BUTTONS MODERN ==================== */
        .btn-modern {
            padding: 8px 16px;
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 8px;
            border: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: #fff;
        }
        .btn-primary-modern:hover { color: #fff; }

        .btn-light-modern {
            background: #fff;
            color: #495057;
            border: 1px solid #dee2e6;
        }
        .btn-light-modern:hover { color: #212529; }

        .btn-danger-modern {
            background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
            color: #fff;
        }
        .btn-danger-modern:hover { color: #fff; }

        .btn-success-modern {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: #fff;
        }
        .btn-success-modern:hover { color: #fff; }

        /* ==================== TABLE MODERN ==================== */
        .table-modern-wrapper {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }

        .table-modern {
            margin-bottom: 0;
        }

        .table-modern thead th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 12px;
            border-bottom: 2px solid #dee2e6;
            white-space: nowrap;
        }

        .table-modern tbody td {
            padding: 12px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f5;
            font-size: 0.88rem;
        }

        .table-modern tbody tr {
            transition: background 0.15s;
        }

        .table-modern tbody tr:hover {
            background: #f8f9fa;
        }

        /* DataTables styling */
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 6px 12px;
            margin-left: 8px;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 6px;
            border: 1px solid #dee2e6;
            padding: 4px 8px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px !important;
            margin: 0 2px;
            border: 1px solid #dee2e6 !important;
            padding: 4px 10px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
            color: #fff !important;
            border-color: #007bff !important;
        }

        /* Spinner modern */
        .spinner-modern {
            display: inline-block;
            width: 30px;
            height: 30px;
            border: 3px solid #e9ecef;
            border-top-color: #007bff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ==================== INFO BOX MODERN ==================== */
        .info-box-modern {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .info-box-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .info-box-modern .icon-wrapper {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 768px) {
            .page-header-pinjam {
                padding: 20px;
            }
            .page-header-pinjam h3 {
                font-size: 1.1rem;
            }
            .mini-stat-card {
                padding: 12px 15px;
            }
            .mini-stat-value {
                font-size: 1.3rem;
            }
            .btn-modern {
                font-size: 0.8rem;
                padding: 6px 12px;
            }
        }

        /* ==================== PAGE HEADER BUKU ==================== */
.page-header-buku {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 25px 30px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.page-header-buku::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
}

/* ==================== FORM MODERN ==================== */
.form-group-modern {
    margin-bottom: 20px;
}

.form-label-modern {
    font-size: 0.85rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    display: block;
}

.form-control-modern {
    border-radius: 10px;
    border: 1px solid #dee2e6;
    padding: 10px 14px;
    font-size: 0.9rem;
    transition: all 0.2s;
    height: auto;
}

.form-control-modern:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.cover-upload-wrapper {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    background: #f8f9fa;
}

/* ==================== CARD FOOTER MODERN ==================== */
.card-footer-modern {
    padding: 18px 30px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* ==================== DETAIL BUKU ==================== */
.detail-cover-wrapper {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    aspect-ratio: 3/4;
    display: flex;
    align-items: center;
    justify-content: center;
}

.detail-cover-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.detail-title-book {
    font-size: 1.5rem;
    font-weight: 800;
    color: #212529;
    line-height: 1.3;
    margin-bottom: 12px;
}

.detail-divider-book {
    height: 3px;
    width: 60px;
    background: linear-gradient(90deg, #007bff, #0056b3);
    border-radius: 2px;
    margin-bottom: 20px;
}

/* Info Row */
.info-row {
    display: flex;
    align-items: center;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 10px;
    margin-bottom: 10px;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}

.info-row:hover {
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-left-color: #007bff;
    transform: translateX(4px);
}

.info-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

.info-content {
    margin-left: 12px;
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 0;
}

.info-label {
    font-size: 0.7rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-bottom: 2px;
}

.info-value {
    font-size: 0.95rem;
    color: #212529;
    font-weight: 600;
}

/* Stock Info Card */
.stock-info-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 20px;
    color: #fff;
}

.stock-info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,0.15);
}

.stock-info-item:last-child {
    border-bottom: none;
}

.stock-info-label {
    font-size: 0.85rem;
    opacity: 0.9;
}

.stock-info-value {
    font-size: 1.5rem;
    font-weight: 800;
}

/* ==================== BUTTON MODERN ==================== */
.btn-warning-modern {
    background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%);
    color: #fff;
    border: none;
}

.btn-warning-modern:hover {
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255,193,7,0.4);
}

/* ==================== RESPONSIVE ==================== */
@media (max-width: 768px) {
    .page-header-buku {
        padding: 20px;
    }
    .page-header-buku h3 {
        font-size: 1.1rem;
    }
    .detail-title-book {
        font-size: 1.2rem;
        margin-top: 15px;
    }
    .card-footer-modern {
        flex-direction: column;
    }
    .card-footer-modern .btn {
        width: 100%;
        margin-bottom: 8px;
    }
}

/* ==================== WELCOME BANNER ==================== */
.welcome-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 25px 30px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.welcome-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
}

.welcome-banner::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: 20%;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.welcome-avatar {
    width: 65px !important;
    height: 65px !important;
    font-size: 26px !important;
    border: 3px solid rgba(255,255,255,0.3) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* ==================== STAT CARD ==================== */
.stat-card {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    color: #fff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.15);
}

.stat-card-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-card-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.stat-card-warning {
    background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
}

.stat-card-danger {
    background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
}

.stat-card-bg {
    position: absolute;
    top: -40%;
    right: -20%;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.stat-card-content {
    display: flex;
    align-items: center;
    padding: 24px;
    position: relative;
    z-index: 1;
}

.stat-card-icon {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.2);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    backdrop-filter: blur(10px);
    flex-shrink: 0;
    border: 2px solid rgba(255,255,255,0.2);
}

.stat-card-info {
    margin-left: 18px;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.stat-card-label {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    opacity: 0.9;
}

.stat-card-value {
    font-size: 2.2rem;
    font-weight: 800;
    line-height: 1;
    margin: 6px 0;
}

.stat-card-desc {
    font-size: 0.75rem;
    opacity: 0.85;
}

.stat-card-footer {
    display: block;
    padding: 12px 24px;
    background: rgba(0,0,0,0.15);
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.85rem;
    text-align: right;
    transition: all 0.2s;
    position: relative;
    z-index: 1;
}

.stat-card-footer:hover {
    background: rgba(0,0,0,0.25);
    color: #fff;
    text-decoration: none;
    padding-right: 30px;
}

.stat-card-footer i {
    transition: transform 0.2s;
}

.stat-card-footer:hover i {
    transform: translateX(5px);
}

/* ==================== TOP BOOK LIST ==================== */
.top-book-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.top-book-item {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #f1f3f5;
    transition: background 0.2s;
}

.top-book-item:hover {
    background: #f8f9fa;
}

.top-book-item:last-child {
    border-bottom: none;
}

.top-book-rank {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 16px;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.rank-1 { background: linear-gradient(135deg, #ffd700 0%, #ffb300 100%); }
.rank-2 { background: linear-gradient(135deg, #c0c0c0 0%, #9e9e9e 100%); }
.rank-3 { background: linear-gradient(135deg, #cd7f32 0%, #a0522d 100%); }
.rank-4 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.rank-5 { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }

.top-book-info {
    margin-left: 15px;
    display: flex;
    flex-direction: column;
    min-width: 0;
    flex: 1;
}

.top-book-title {
    font-weight: 700;
    color: #212529;
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.top-book-meta {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 2px;
}

/* ==================== QUICK ACTION ==================== */
.quick-action {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 25px 15px;
    border-radius: 12px;
    text-decoration: none !important;
    transition: all 0.3s ease;
    font-weight: 600;
    gap: 12px;
    color: #fff;
}

.quick-action:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    color: #fff;
}

.quick-action i {
    font-size: 32px;
    opacity: 0.95;
}

.quick-action-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.quick-action-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.quick-action-warning { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); }
.quick-action-info    { background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%); }

/* ==================== RESPONSIVE DASHBOARD ==================== */
@media (max-width: 768px) {
    .welcome-banner {
        padding: 20px;
    }

    .welcome-banner h3 {
        font-size: 1.1rem;
    }

    .welcome-avatar {
        width: 50px !important;
        height: 50px !important;
        font-size: 20px !important;
    }

    .stat-card-content {
        padding: 18px;
    }

    .stat-card-icon {
        width: 50px;
        height: 50px;
        font-size: 22px;
    }

    .stat-card-value {
        font-size: 1.8rem;
    }

    .quick-action {
        padding: 20px 10px;
    }

    .quick-action i {
        font-size: 26px;
    }
}

/* Highlight baris terlambat */
.tr-danger {
    background-color: #fdecef !important;
}

.tr-danger:hover {
    background-color: #fbdce0 !important;
}

.tr-danger td {
    border-bottom-color: #f5c2c7 !important;
}

.tr-warning {
    background-color: #fff8e1 !important;
}

.tr-warning:hover {
    background-color: #ffecb3 !important;
}
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        @include('admin.layout.navbar')
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        @include('admin.layout.sidebar')
    </aside>

    <div class="content-wrapper">
        <div class="content-header">
            @include('admin.layout.header')
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>

    <footer class="main-footer">
        @include('admin.layout.footer')
    </footer>
</div>

{{-- ==================== JS ==================== --}}
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

{{-- SweetAlert2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')

<script>
    // ==================== TOASTR ====================
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000",
    };

    @if (Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif
    @if (Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif
    @if (Session::has('info'))
        toastr.info("{{ Session::get('info') }}");
    @endif

    // ==================== SELECT2 ====================
    $(function() {
        $('.select2').select2({ theme: 'bootstrap4' });
    });

    // ==================== CONST ====================
    const APP_URL = {!! json_encode(url('/')) !!};

    // ==================== SWEETALERT DEFAULT ====================
    const SwalConfirm = (title, text, callback) => {
        Swal.fire({
            title: title || 'Konfirmasi',
            text: text || 'Apakah Anda yakin?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check"></i> Ya',
            cancelButtonText: '<i class="fas fa-times"></i> Batal'
        }).then((result) => {
            if (result.isConfirmed && typeof callback === 'function') {
                callback();
            }
        });
    };

    const SwalSuccess = (msg) => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: msg || 'Operasi berhasil.',
            timer: 2000,
            showConfirmButton: false
        });
    };

    const SwalError = (msg) => {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: msg || 'Terjadi kesalahan.'
        });
    };
</script>
</body>
</html>