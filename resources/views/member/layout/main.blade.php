<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Library UNM | @yield('title')</title>

    {{-- ==================== CSS ==================== --}}
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* ==================== GLOBAL ==================== */
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            background: #f4f6f9;
        }
        .content {
            flex: 1;
        }
        .footer {
            background-color: #343a40;
            color: white;
        }
        img {
            height: 200px;
            object-fit: cover;
        }
        .img-pinjam {
            height: 100px;
            object-fit: cover;
        }
        .profil-img {
            height: 40px;
            width: 40px;
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

        /* ==================== KATALOG BUKU ==================== */
        .book-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
            background: #fff;
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }

        .book-cover-wrapper {
            position: relative;
            width: 100%;
            height: 260px;
            overflow: hidden;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .book-cover-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .book-card:hover .book-cover-wrapper img {
            transform: scale(1.08);
        }

        .book-badge-category {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0, 123, 255, 0.95);
            color: #fff;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            z-index: 2;
        }

        .book-badge-stock {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            color: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            z-index: 2;
        }

        .book-badge-stock.available { background: #28a745; }
        .book-badge-stock.out { background: #dc3545; }

        .book-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .book-title {
            font-size: 1rem;
            font-weight: 700;
            color: #212529;
            margin: 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.8em;
        }

        .book-meta {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 0.8rem;
            color: #6c757d;
        }

        .book-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .book-meta-item i {
            width: 14px;
            text-align: center;
            color: #adb5bd;
        }

        .book-meta-item span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .book-footer {
            padding: 12px 16px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .book-footer .btn {
            flex: 1;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 8px 10px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .book-footer .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        /* Book Hover Overlay */
        .book-hover-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 5;
        }

        .book-card:hover .book-hover-overlay {
            opacity: 1;
        }

        .btn-hover-detail {
            background: #fff;
            color: #212529;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-hover-detail:hover {
            background: #007bff;
            color: #fff;
            transform: scale(1.05);
        }

        /* ==================== HERO SECTION ==================== */
        .hero-section {
            background: linear-gradient(135deg, #007bff 0%, #17a2b8 100%);
            padding: 60px 0 80px;
            color: #fff;
            position: relative;
            overflow: hidden;
            margin-bottom: -50px;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.15);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 15px;
            color: #fff;
        }

        .text-gradient {
            background: linear-gradient(135deg, #ffd700 0%, #ffb300 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.05rem;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 30px;
            max-width: 550px;
        }

        /* Hero Search */
        .hero-search {
            margin-bottom: 35px;
        }

        .search-wrapper {
            position: relative;
            display: flex;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            max-width: 550px;
        }

        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            font-size: 1.1rem;
            z-index: 2;
        }

        .search-input {
            flex: 1;
            border: none;
            padding: 18px 20px 18px 55px;
            font-size: 0.95rem;
            outline: none;
            background: transparent;
            color: #212529;
        }

        .search-input::placeholder {
            color: #adb5bd;
        }

        .search-btn {
            background: linear-gradient(135deg, #007bff 0%, #17a2b8 100%);
            border: none;
            color: #fff;
            padding: 0 30px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1.1rem;
        }

        .search-btn:hover {
            background: linear-gradient(135deg, #17a2b8 0%, #007bff 100%);
            padding: 0 35px;
        }

        /* Hero Stats */
        .hero-stats {
            display: flex;
            align-items: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .hero-stat {
            display: flex;
            flex-direction: column;
        }

        .hero-stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1;
        }

        .hero-stat-label {
            font-size: 0.8rem;
            opacity: 0.85;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        .hero-stat-divider {
            width: 1px;
            height: 40px;
            background: rgba(255,255,255,0.25);
        }

        /* Hero Illustration */
        .hero-illustration {
            position: relative;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-circle {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            position: absolute;
            animation: pulse 4s infinite ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .floating-book {
            position: absolute;
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #fff;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.3);
            animation: floatBook 6s infinite ease-in-out;
        }

        .book-1 { top: 10%; left: 20%; animation-delay: 0s; }
        .book-2 { top: 20%; right: 15%; animation-delay: 1.5s; }
        .book-3 { bottom: 20%; left: 15%; animation-delay: 3s; }
        .book-4 { bottom: 15%; right: 20%; animation-delay: 4.5s; }

        @keyframes floatBook {
            0%, 100% { transform: translateY(0) rotate(-5deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        /* ==================== KATEGORI FILTER ==================== */
        .kategori-filter-section {
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            margin-bottom: 25px;
            position: relative;
            z-index: 5;
        }

        .kategori-chips {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .kategori-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: #f8f9fa;
            border-radius: 25px;
            text-decoration: none !important;
            color: #495057;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .kategori-chip:hover {
            background: #e7f1ff;
            color: #007bff;
            transform: translateY(-2px);
        }

        .kategori-chip.active {
            background: linear-gradient(135deg, #007bff 0%, #17a2b8 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(0,123,255,0.4);
        }

        .chip-count {
            background: rgba(0,0,0,0.1);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .kategori-chip.active .chip-count {
            background: rgba(255,255,255,0.25);
        }

        /* ==================== HERO COMPACT (untuk halaman member) ==================== */
        .hero-section-compact {
            background: linear-gradient(135deg, #007bff 0%, #17a2b8 100%);
            padding: 30px 0;
            color: #fff;
            position: relative;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .hero-section-compact::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }

        .hero-section-compact.hero-section-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        }

        .hero-section-compact.hero-section-info {
            background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
        }

        .hero-section-compact.hero-section-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }

        .hero-icon-compact {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.25);
        }

        /* ==================== INFO BANNER ==================== */
        .info-banner {
            background: linear-gradient(135deg, #e7f1ff 0%, #d0e7ff 100%);
            border-left: 4px solid #007bff;
            border-radius: 12px;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,123,255,0.1);
        }

        .info-banner.info-banner-warning {
            background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
            border-left-color: #ffc107;
        }

        .info-banner-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #007bff 0%, #17a2b8 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        }

        .info-banner-warning .info-banner-icon {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        }

        .info-banner-content {
            color: #495057;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* ==================== BOOKING INFO CARD ==================== */
        .booking-info-card {
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            margin-bottom: 25px;
        }

        .booking-info-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .booking-info-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
            background: #fff;
        }

        .booking-info-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .booking-info-content {
            display: flex;
            flex-direction: column;
            min-width: 0;
            flex: 1;
        }

        .booking-info-label {
            font-size: 0.7rem;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .booking-info-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: #212529;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ==================== MINI STAT CARD ==================== */
        .mini-stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .mini-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .mini-stat-primary { border-left-color: #007bff; }
        .mini-stat-success { border-left-color: #28a745; }
        .mini-stat-warning { border-left-color: #ffc107; }
        .mini-stat-info { border-left-color: #17a2b8; }
        .mini-stat-danger { border-left-color: #dc3545; }

        .mini-stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .mini-stat-primary .mini-stat-icon { background: #e7f1ff; color: #007bff; }
        .mini-stat-success .mini-stat-icon { background: #e9f7ef; color: #28a745; }
        .mini-stat-warning .mini-stat-icon { background: #fff8e1; color: #ffc107; }
        .mini-stat-info .mini-stat-icon { background: #e7f6f9; color: #17a2b8; }
        .mini-stat-danger .mini-stat-icon { background: #fdecef; color: #dc3545; }

        .mini-stat-content {
            display: flex;
            flex-direction: column;
        }

        .mini-stat-label {
            font-size: 0.7rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .mini-stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #212529;
            line-height: 1;
        }

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

        .tr-danger { background: #fdecef !important; }
        .tr-warning { background: #fff8e1 !important; }

        .badge-lg {
            padding: 6px 12px;
            font-size: 0.78rem;
            font-weight: 600;
            border-radius: 8px;
        }

        /* ==================== EMPTY STATE ==================== */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }

        .empty-state-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #e7f1ff 0%, #d0e7ff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #007bff;
            margin: 0 auto 25px;
        }

        .empty-state-title {
            font-weight: 800;
            color: #212529;
            margin-bottom: 10px;
        }

        .empty-state-text {
            color: #6c757d;
            margin-bottom: 25px;
        }

        /* ==================== PAGE HEADER ==================== */
        .page-header {
            background: linear-gradient(135deg, #007bff 0%, #17a2b8 100%);
            color: #fff;
            padding: 40px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .page-header h1 {
            font-weight: 800;
            font-size: 2rem;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .page-header p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 1rem;
        }

        /* ==================== MODAL DETAIL BUKU ==================== */
        .detail-modal {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .detail-modal-header {
            background: linear-gradient(135deg, #007bff 0%, #17a2b8 100%);
            color: #fff;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .detail-icon-wrapper {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            backdrop-filter: blur(10px);
        }

        .detail-modal-header .close-btn {
            background: rgba(255,255,255,0.15);
            border: none;
            color: #fff;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .detail-modal-header .close-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg);
        }

        .detail-modal-body {
            padding: 30px;
            background: #fff;
        }

        .cover-wrapper-detail {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            aspect-ratio: 3/4;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cover-detail-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cover-overlay {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            display: flex;
            justify-content: space-between;
        }

        .badge-stock-detail {
            background: #28a745;
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-stock-detail.out-of-stock {
            background: #dc3545;
        }

        .detail-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #212529;
            line-height: 1.3;
            margin-bottom: 12px;
        }

        .detail-divider {
            height: 3px;
            width: 60px;
            background: linear-gradient(90deg, #007bff, #17a2b8);
            border-radius: 2px;
            margin-bottom: 20px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .detail-item:hover {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left-color: #007bff;
            transform: translateX(4px);
        }

        .detail-item-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .bg-info-light { background: #e7f6f9; }
        .bg-primary-light { background: #e7f1ff; }
        .bg-danger-light { background: #fdecef; }
        .bg-success-light { background: #e9f7ef; }
        .bg-secondary-light { background: #f1f3f5; }
        .bg-warning-light { background: #fff8e1; }

        .detail-item-content {
            margin-left: 12px;
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
        }

        .detail-label {
            font-size: 0.7rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .detail-value {
            font-size: 0.9rem;
            color: #212529;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stock-pill {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #fff;
            padding: 4px 14px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.8rem;
            display: inline-block;
            box-shadow: 0 2px 6px rgba(255,193,7,0.4);
        }

        .detail-modal-footer {
            padding: 18px 30px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-modern {
            padding: 10px 22px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s;
            border: none;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-modern-sm {
            padding: 8px 16px;
            font-weight: 600;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .btn-light-modern {
            background: #fff;
            color: #495057;
            border: 1px solid #dee2e6;
        }

        .btn-light-modern:hover {
            color: #212529;
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: #fff;
        }

        .btn-success-modern:hover {
            color: #fff;
            box-shadow: 0 6px 16px rgba(40,167,69,0.4);
        }

        .detail-modal-footer .btn-success {
            background: linear-gradient(135deg, #007bff 0%, #17a2b8 100%);
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
            color: #fff;
        }

        .detail-modal-footer .btn-success:hover {
            box-shadow: 0 6px 16px rgba(0,123,255,0.4);
        }

        /* ==================== CARD MODERN ==================== */
        .card-modern {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header-modern {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 18px 24px;
        }

        /* ==================== NAVBAR MEMBER ==================== */
        .navbar-member {
            background: linear-gradient(135deg, #343a40 0%, #212529 100%) !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .navbar-member .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
        }

        .navbar-member .nav-link {
            font-weight: 500;
            transition: color 0.2s;
            padding: 8px 12px !important;
        }

        .navbar-member .nav-link:hover {
            color: #4dabf7 !important;
        }

        .navbar-member .badge {
            font-size: 0.7rem;
            padding: 3px 6px;
            margin-left: 4px;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 992px) {
            .hero-title { font-size: 2.2rem; }
            .hero-section { padding: 40px 0 60px; }
            .hero-stats { gap: 20px; }
            .hero-stat-value { font-size: 1.4rem; }
        }

        @media (max-width: 768px) {
            .detail-modal-body { padding: 20px; }
            .detail-title { font-size: 1.2rem; margin-top: 15px; }
            .detail-modal-footer { padding: 15px; }
            .detail-modal-footer .btn-modern { flex: 1; }
            .page-header { padding: 25px 0; }
            .page-header h1 { font-size: 1.5rem; }
            .book-cover-wrapper { height: 220px; }
            .hero-title { font-size: 1.8rem; }
            .hero-subtitle { font-size: 0.95rem; }
            .hero-section { padding: 30px 0 50px; }
            .hero-stat-divider { height: 30px; }
            .search-input { padding: 15px 15px 15px 50px; font-size: 0.85rem; }
            .search-btn { padding: 0 20px; }
            .kategori-filter-section { padding: 18px; }
            .kategori-chip { padding: 8px 14px; font-size: 0.8rem; }
            .hero-section-compact { padding: 20px 0; }
            .hero-icon-compact { width: 50px; height: 50px; font-size: 22px; }
            .hero-section-compact h2 { font-size: 1.2rem; }
            .booking-info-card { padding: 15px; }
            .mini-stat-value { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <div class="content">
        @include('member.layout.navbar')

        @if(!View::hasSection('hide-page-header'))
            <header class="container pt-4">
                <h1>@yield('title')</h1>
            </header>
        @endif

        @yield('content')
    </div>

    @include('member.layout.footer')

    {{-- ==================== JS ==================== --}}
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

    <script>
        // ==================== CONST ====================
        const APP_URL = {!! json_encode(url('/')) !!};

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

        // ==================== SWEETALERT HELPERS ====================
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