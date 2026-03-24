<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icon/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/icon/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
    <style>
        :root {
            --blue-deep: #00297a;
            --blue-main: #1a56db;
            --blue-soft: #e8f0fe;
            --red-badge: #c0392b;
            --warm-gray: #f7f8fa;
            --text-main: #1a1f36;
            --text-sub: #6b7280;
            --radius-card: 12px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background: var(--warm-gray);
            color: var(--text-main);
        }

        /* ── HEADER ──────────────────────────────────── */
        .site-header {
            background: #1730c2;
            position: relative;
            z-index: 10;
            padding: 20px 0 28px;
        }

        /* Fade dưới header hòa vào banner */
        .site-header::after {
            content: '';
            position: absolute;
            bottom: -44px;
            left: 0;
            right: 0;
            height: 44px;
            background: linear-gradient(to bottom, #1730c2, transparent);
            z-index: 10;
            pointer-events: none;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        /* Logo + text trái */
        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 18px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .logo-img-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            flex-shrink: 0;
        }

        .logo-img-col img {
            width: 72px;
            height: auto;
            display: block;
        }

        .logo-place {
            font-size: 10px;
            font-weight: 700;
            color: rgba(255, 255, 255, .8);
            text-transform: uppercase;
            letter-spacing: .8px;
            text-align: center;
        }

        .logo-titles {
            line-height: 1;
        }

        .logo-supt {
            font-size: 12px;
            font-weight: 500;
            color: rgba(255, 255, 255, .8);
            text-transform: uppercase;
            letter-spacing: .5px;
            display: block;
            margin-bottom: 8px;
        }

        .logo-main {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            display: block;
            letter-spacing: -.4px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, .25);
        }

        /* Badge phải */
        .tn-badge {
            display: block;
            flex-shrink: 0;
            line-height: 0;
        }

        .tn-badge img {
            height: 78px;
            width: auto;
            display: block;
        }

        /* ── HEADER RESPONSIVE ────────────────────────── */
        @media (max-width: 991px) {
            .site-header {
                padding: 14px 0 20px;
            }

            .logo-img-col img {
                width: 58px;
            }

            .logo-main {
                font-size: 22px;
            }

            .tn-badge img {
                height: 62px;
            }

            .site-header::after {
                bottom: -36px;
                height: 36px;
            }
        }

        @media (max-width: 575px) {
            .site-header {
                padding: 10px 0 14px;
            }

            .logo-wrap {
                gap: 10px;
            }

            .logo-img-col img {
                width: 46px;
            }

            .logo-supt {
                display: none;
            }

            .logo-main {
                font-size: 17px;
            }

            .logo-place {
                font-size: 8px;
            }

            .tn-badge img {
                height: 50px;
            }

            .site-header::after {
                bottom: -28px;
                height: 28px;
            }
        }

        /* ── FOOTER ──────────────────────────────────── */
        .site-footer {
            background: var(--blue-deep);
            color: rgba(255, 255, 255, .75);
            margin-top: 48px;
        }

        .footer-body {
            padding: 36px 0 24px;
        }

        .footer-col-title {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(255, 255, 255, .12);
        }

        .footer-info-row {
            display: flex;
            gap: 8px;
            font-size: 12.5px;
            margin-bottom: 8px;
            align-items: flex-start;
        }

        .footer-info-row i {
            margin-top: 2px;
            flex-shrink: 0;
            color: rgba(255, 255, 255, .45);
            font-size: 12px;
        }

        .footer-about-text {
            font-size: 12.5px;
            line-height: 1.7;
            color: rgba(255, 255, 255, .65);
        }

        .footer-map-box {
            width: 100%;
            height: 160px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(255, 255, 255, .05);
        }

        .footer-map-box iframe {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
        }

        /* Music bar */
        .footer-music {
            border-top: 1px solid rgba(255, 255, 255, .1);
            padding: 10px 0;
            font-size: 12px;
            color: rgba(255, 255, 255, .5);
        }

        .footer-music .label {
            white-space: nowrap;
            font-weight: 600;
            color: rgba(255, 255, 255, .6);
            margin-right: 12px;
            flex-shrink: 0;
        }

        /* Footer bottom */
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, .08);
            padding: 12px 0;
            font-size: 11.5px;
            color: rgba(255, 255, 255, .4);
            text-align: center;
        }

        /* ── FOOTER RESPONSIVE ────────────────────────── */

        /* Tablet: ẩn col About, chỉ còn Contact + Map */
        @media (max-width: 991px) and (min-width: 768px) {
            .site-footer { margin-top: 0; }
            .footer-body { padding: 28px 0 20px; }
            .footer-col-about { display: none; }
            .footer-map-box { height: 140px; }
        }

        /* Mobile: stack dọc, tối giản */
        @media (max-width: 767px) {
            .site-footer { margin-top: 0; }
            .footer-body { padding: 22px 0 12px; }

            .footer-col-title {
                font-size: 12px;
                margin-bottom: 10px;
                padding-bottom: 6px;
            }

            /* Ẩn col About & Map trên mobile — chỉ giữ Contact */
            .footer-col-about,
            .footer-col-map { display: none; }

            /* Contact: hiện dạng 2 cột nhỏ */
            .footer-info-row {
                font-size: 12px;
                margin-bottom: 6px;
            }

            /* Music bar: ẩn label, chỉ marquee */
            .footer-music { padding: 8px 0; font-size: 11px; }
            .footer-music .label { display: none; }

            .footer-bottom { padding: 10px 0; font-size: 11px; }
        }
    </style>
</head>

<body>

    <!-- ═══ HEADER ═══ -->
    <header class="site-header">
        <div class="container-fluid px-4">
            <div class="header-inner">

                <!-- Logo trái -->
                <a href="{{ route('home') }}" class="logo-wrap">
                    <div class="logo-img-col">
                        <img src="{{ asset('assets/icon/logo.png') }}" alt="Logo Ba Chẽ">
                        <span class="logo-place">Xã Ba Chẽ</span>
                    </div>
                    <div class="logo-titles">
                        <span class="logo-supt">Công trình Thanh niên số xã Ba Chẽ</span>
                        <span class="logo-main">Bản đồ số xã Ba Chẽ</span>
                    </div>
                </a>

                <!-- Badge phải -->
                <a href="#" class="tn-badge">
                    <img src="{{ asset('assets/icon/logo-right.png') }}" alt="Thanh niên Việt Nam">
                </a>

            </div>
        </div>
    </header>

    <!-- ═══ CONTENT ═══ -->
    <main>@yield('content')</main>

    <!-- ═══ FOOTER ═══ -->
    <footer class="site-footer">
        <div class="footer-body">
            <div class="container">
                <div class="row g-4 align-items-start">

                    <!-- Col 1: Liên hệ — hiện tất cả breakpoints -->
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="footer-col-title">
                            <i class="bi bi-geo-alt-fill me-1"></i>Xã Ba Chẽ
                        </div>
                        <div class="footer-info-row">
                            <i class="bi bi-geo-alt"></i>
                            <span>Xã Ba Chẽ, Huyện Ba Chẽ, Tỉnh Quảng Ninh</span>
                        </div>
                        <div class="footer-info-row">
                            <i class="bi bi-telephone"></i>
                            <span>Điện thoại: (0203) xxx-xxxx</span>
                        </div>
                        <div class="footer-info-row">
                            <i class="bi bi-envelope"></i>
                            <span>ubnd.bache@quangninh.gov.vn</span>
                        </div>
                        <div class="footer-info-row">
                            <i class="bi bi-headset"></i>
                            <span>Hotline: 1900 xxxx</span>
                        </div>
                        <div class="d-flex gap-3 mt-3">
                            <a href="{{ route('home') }}" style="font-size:12px;color:rgba(255,255,255,.5);text-decoration:none;">
                                <i class="bi bi-house me-1"></i>Trang chủ
                            </a>
                            <a href="{{ route('map') }}" style="font-size:12px;color:rgba(255,255,255,.5);text-decoration:none;">
                                <i class="bi bi-map me-1"></i>Bản đồ
                            </a>
                        </div>
                    </div>

                    <!-- Col 2: Giới thiệu — ẩn tablet/mobile -->
                    <div class="col-lg-5 footer-col-about">
                        <div class="footer-col-title">Giới thiệu về Ba Chẽ</div>
                        <p class="footer-about-text">
                            Ba Chẽ là huyện miền núi phía Tây Bắc tỉnh Quảng Ninh, cách thành phố Hạ Long khoảng 80 km.
                            Huyện có diện tích tự nhiên 1.232 km², địa hình chủ yếu là đồi núi với nhiều cánh rừng
                            nguyên sinh phong phú, nơi sinh sống của nhiều dân tộc anh em như Kinh, Dao, Sán Chỉ, Tày...
                        </p>
                        <p class="footer-about-text mb-0">
                            Xã Ba Chẽ là trung tâm hành chính của huyện, tập trung các cơ quan, trường học, bệnh viện
                            và các dịch vụ thiết yếu phục vụ đời sống nhân dân.
                        </p>
                    </div>

                    <!-- Col 3: Bản đồ — ẩn mobile, chiếm toàn tablet -->
                    <div class="col-md-9 col-lg-4 footer-col-map">
                        <div class="footer-col-title">Vị trí trên bản đồ</div>
                        <div class="footer-map-box">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d59484.34!2d107.25!3d21.38!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314a83d0a2635da9%3A0x96e59b5978e43e42!2zaMO0aSBCYSBDaMG-!5e0!3m2!1svi!2svn!4v1"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Music bar -->
        <div class="footer-music">
            <div class="container d-flex align-items-center" style="overflow:hidden;">
                <span class="label"><i class="bi bi-music-note-beamed me-1"></i>Âm nhạc địa phương</span>
                <marquee scrollamount="3" style="flex:1;min-width:0;">
                    ♫ Hát then – Đàn tính &nbsp;·&nbsp; ♫ Soóng cọ dân tộc Sán Chỉ &nbsp;·&nbsp; ♫ Dân ca người Dao &nbsp;·&nbsp; ♫ Hát ru miền núi Đông Bắc &nbsp;·&nbsp; ♫ Bài ca về Ba Chẽ
                </marquee>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p class="mb-0">Bản quyền &copy; {{ date('Y') }} | Đoàn Thanh niên Cộng sản Hồ Chí Minh xã Ba Chẽ</p>
                <p class="mb-0 mt-1">Người thực hiện: Phạm Tuấn Duy</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
