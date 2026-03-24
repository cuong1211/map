@extends('layouts.app')

@section('title', 'Trang chủ - ' . config('app.name'))

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* ── BANNER ───────────────────────────────────── */
        .hero-banner {
            width: 100%;
            height: 700px;
            position: relative;
            z-index: 1;
            overflow: hidden;
            display: block;
            line-height: 0;
            margin-top: -44px;
        }

        .hero-banner svg,
        .hero-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 110px;
            background: linear-gradient(to bottom, transparent 0%, #ffffff 100%);
            z-index: 5;
            pointer-events: none;
        }

        /* ── SEARCH BAR ───────────────────────────────── */
        .search-bar {
            background: #fff;
            padding: 18px 0 22px;
        }

        .search-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .search-input-wrap {
            position: relative;
            flex: 1;
            max-width: 520px;
        }

        .search-input-wrap input {
            width: 100%;
            border: 1.5px solid #1730c2;
            border-radius: 8px;
            padding: 11px 16px;
            font-size: 14px;
            font-family: inherit;
            color: #1a1f36;
            outline: none;
            background: #fff;
            transition: box-shadow .2s;
        }

        .search-input-wrap input::placeholder {
            color: #9ca3af;
        }

        .search-input-wrap input:focus {
            box-shadow: 0 0 0 3px rgba(23, 48, 194, .12);
        }

        .search-select-wrap {
            position: relative;
        }

        .search-select-wrap select {
            border: 1.5px solid #1730c2;
            border-radius: 8px;
            padding: 11px 36px 11px 14px;
            font-size: 14px;
            font-family: inherit;
            color: #1a1f36;
            background: #fff;
            outline: none;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            min-width: 140px;
            transition: box-shadow .2s;
        }

        .search-select-wrap select:focus {
            box-shadow: 0 0 0 3px rgba(23, 48, 194, .12);
        }

        .search-select-wrap::after {
            content: '';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-top-color: #1730c2;
            margin-top: 3px;
            pointer-events: none;
        }

        /* ── 2-COLUMN MAIN LAYOUT ─────────────────────── */
        .main-layout {
            display: flex;
            align-items: flex-start;
            gap: 0;
            background: #f4f6fb;
            min-height: 600px;
        }

        /* Left: Map */
        .map-col {
            flex: 0 0 58%;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 50;
        }

        #map {
            width: 100%;
            height: 100%;
        }

        /* Right: List */
        .list-col {
            flex: 1;
            height: 100vh;
            overflow-y: auto;
            padding: 20px 16px 40px;
            background: #fff;
            border-left: 1px solid #e5eaf5;
        }

        .list-col::-webkit-scrollbar {
            width: 5px;
        }

        .list-col::-webkit-scrollbar-track {
            background: #f0f2f8;
        }

        .list-col::-webkit-scrollbar-thumb {
            background: #c5d0ee;
            border-radius: 4px;
        }

        /* Section header in list */
        .list-section-head {
            font-size: 13px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin: 0 0 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5eaf5;
        }

        /* Location list item */
        .loc-item {
            display: flex;
            gap: 12px;
            padding: 11px 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: background .18s, transform .18s;
            border: 1.5px solid transparent;
            margin-bottom: 8px;
            background: #fff;
            position: relative;
        }

        .loc-item:hover {
            background: #eef2ff;
            border-color: #c5d0ee;
            transform: translateX(3px);
        }

        .loc-item.active {
            background: #eef2ff;
            border-color: #1730c2;
        }

        .loc-thumb {
            width: 72px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
            background: linear-gradient(135deg, #dce8ff, #c8d8ff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .loc-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .loc-info {
            flex: 1;
            min-width: 0;
        }

        .loc-name {
            font-size: 13px;
            font-weight: 700;
            color: #1730c2;
            line-height: 1.35;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .loc-addr {
            font-size: 11.5px;
            color: #6b7280;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .loc-category {
            font-size: 10.5px;
            color: #9ca3af;
            margin-top: 4px;
            font-weight: 600;
        }

        .loc-no-coords {
            opacity: .65;
        }

        .loc-no-coords .loc-name {
            color: #374151;
        }

        /* ── LEAFLET POPUP ────────────────────────────── */
        .lf-popup-inner {
            min-width: 210px;
            font-family: 'Be Vietnam Pro', sans-serif;
        }

        .lf-popup-img {
            width: 100%;
            height: 110px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 8px;
            display: block;
        }

        .lf-popup-name {
            font-size: 14px;
            font-weight: 700;
            color: #1730c2;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .lf-popup-addr {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .lf-popup-btn {
            display: block;
            background: #1730c2;
            color: #fff !important;
            text-align: center;
            padding: 7px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none !important;
        }

        .lf-popup-btn:hover {
            background: #0e22a0;
        }

        /* Override leaflet popup arrow */
        .leaflet-popup-content-wrapper {
            border-radius: 12px !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .18) !important;
            padding: 0 !important;
            overflow: hidden;
        }

        .leaflet-popup-content {
            margin: 14px !important;
        }

        /* Pagination in list */
        .list-pagination {
            display: flex;
            justify-content: center;
            margin-top: 16px;
            padding-top: 12px;
            border-top: 1px solid #e5eaf5;
        }

        /* ── MOBILE TAB SWITCHER ─────────────────────── */
        .mobile-tabs {
            display: none;
            background: #fff;
            border-bottom: 2px solid #e5eaf5;
            position: sticky;
            top: 0;
            z-index: 300;
        }

        .mobile-tabs .tab-btn {
            flex: 1;
            border: none;
            background: transparent;
            padding: 12px 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            color: #6b7280;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: color .2s, border-color .2s;
        }

        .mobile-tabs .tab-btn.active {
            color: #1730c2;
            border-bottom-color: #1730c2;
        }

        /* ── RESPONSIVE ───────────────────────────────── */

        /* Desktop ≥992px — 2 cột ngang, full height */
        @media (min-width: 992px) {
            .mobile-tabs {
                display: none !important;
            }

            .map-col,
            .list-col {
                display: flex !important;
            }

            .list-col {
                flex-direction: column;
            }
        }

        /* Tablet 768–991px — vẫn 2 cột nhưng tỉ lệ nhỏ hơn */
        @media (max-width: 991px) and (min-width: 768px) {
            .hero-banner {
                height: 300px;
                margin-top: -36px;
            }

            .hero-banner::after {
                height: 80px;
            }

            .map-col {
                flex: 0 0 50%;
                height: 100vh;
            }

            .list-col {
                height: 100vh;
            }
        }

        /* Mobile <768px — tab switcher, stack dọc */
        @media (max-width: 767px) {
            .hero-banner {
                height: 210px;
                margin-top: -28px;
            }

            .hero-banner::after {
                height: 60px;
            }

            .search-bar {
                padding: 10px 0 12px;
            }

            .search-row {
                flex-direction: column;
                gap: 8px;
                align-items: stretch;
            }

            .search-input-wrap {
                max-width: 100%;
            }

            .search-select-wrap select {
                width: 100%;
                min-width: 0;
            }

            .mobile-tabs {
                display: flex;
            }

            .main-layout {
                flex-direction: column;
                min-height: 0;
            }

            .map-col {
                flex: none;
                position: relative;
                height: calc(100svh - 160px);
                width: 100%;
            }

            .list-col {
                flex: none;
                height: auto;
                overflow-y: visible;
                border-left: none;
                border-top: 1px solid #e5eaf5;
                padding: 14px 12px 32px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- HERO BANNER --}}
    <div class="hero-banner">
        <img src="{{ asset('assets/banner/banner.jpg') }}" style="width:100%;height:100%;object-fit:cover;">

    </div>

    {{-- SEARCH BAR --}}
    <div class="search-bar">
        <div class="container">
            <form method="GET" action="{{ route('home') }}">
                <div class="search-row">
                    <div class="search-input-wrap">
                        <input type="text" name="search" placeholder="Tìm theo tên/địa chỉ..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="search-select-wrap">
                        <select name="category" onchange="this.form.submit()">
                            <option value="">Tất cả</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (request('search') || request('category'))
                        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- 2-COLUMN: MAP + LIST --}}
    @php
        /* Lấy tất cả địa điểm có tọa độ cho map markers */
        $mapLocations = \App\Models\Location::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'name', 'address', 'latitude', 'longitude', 'category', 'image']);

        $categoryIcons = ['Trường học' => '🎓', 'Cơ quan' => '🏛️'];

        /* Chuẩn bị JSON cho JS — tránh lỗi Blade parse với arrow function */
        $mapLocationsJson = $mapLocations->map(function ($l) {
            return [
                'id' => $l->id,
                'name' => $l->name,
                'address' => $l->address ?: 'Xã Võ Nhai',
                'lat' => (float) $l->latitude,
                'lng' => (float) $l->longitude,
                'category' => $l->category,
                'image' => $l->image ? '/storage/' . $l->image : null,
                'mapsUrl' => 'https://www.google.com/maps?q=' . $l->latitude . ',' . $l->longitude,
            ];
        });
    @endphp

    {{-- Tab switcher (chỉ hiện trên mobile) --}}
    <div class="mobile-tabs" id="mobileTabs">
        <button class="tab-btn active" onclick="switchTab('map', this)">
            🗺️ Bản đồ
        </button>
        <button class="tab-btn" onclick="switchTab('list', this)">
            📋 Danh sách
        </button>
    </div>

    <div class="main-layout">

        {{-- LEFT: MAP --}}
        <div class="map-col">
            <div id="map"></div>
        </div>

        {{-- RIGHT: LOCATIONS LIST --}}
        <div class="list-col">
            <p class="list-section-head">
                {{ $locations->total() }} địa điểm
                @if (request('category'))
                    · {{ request('category') }}
                @endif
                @if (request('search'))
                    · "{{ request('search') }}"
                @endif
            </p>

            @forelse($locations as $loc)
                @php
                    $icon = $categoryIcons[$loc->category] ?? '📍';
                    $hasCoords = $loc->latitude && $loc->longitude;
                    $mapsUrl = $hasCoords
                        ? "https://www.google.com/maps?q={$loc->latitude},{$loc->longitude}"
                        : 'https://www.google.com/maps/search/' . urlencode($loc->name . ' ' . $loc->address);
                @endphp
                <div class="loc-item {{ $hasCoords ? '' : 'loc-no-coords' }}" data-id="{{ $loc->id }}"
                    data-lat="{{ $loc->latitude }}" data-lng="{{ $loc->longitude }}"
                    data-has-coords="{{ $hasCoords ? '1' : '0' }}" data-maps="{{ $mapsUrl }}"
                    onclick="focusLocation(this)">
                    <div class="loc-thumb">
                        @if ($loc->image)
                            <img src="{{ asset('storage/' . $loc->image) }}" alt="{{ $loc->name }}" loading="lazy">
                        @else
                            {{ $icon }}
                        @endif
                    </div>
                    <div class="loc-info">
                        <div class="loc-name">{{ $loc->name }}</div>
                        <div class="loc-addr">{{ $loc->address ?: 'Xã Võ Nhai, Thái Nguyên' }}</div>
                        @if ($loc->category)
                            <div class="loc-category">{{ $icon }} {{ $loc->category }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align:center;padding:60px 20px;color:#9ca3af;">
                    <i class="bi bi-search" style="font-size:40px;display:block;margin-bottom:12px;color:#d1ddf5;"></i>
                    <p>Không tìm thấy địa điểm nào</p>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-primary mt-1">Xem tất cả</a>
                </div>
            @endforelse

            @if ($locations->hasPages())
                <div class="list-pagination">
                    {{ $locations->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        (function() {
            /* ── 1. Dữ liệu địa điểm từ PHP ──────────────── */
            const allLocations = @json($mapLocationsJson);

            /* ── 2. Khởi tạo map ──────────────────────────── */
            const defaultCenter = [21.75182118692702, 106.07343143637803];
            const map = L.map('map', {
                center: defaultCenter,
                zoom: 13,
                zoomControl: true,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>',
                maxZoom: 19,
            }).addTo(map);

            /* ── 3. Custom marker icon ────────────────────── */
            const catColors = {
                'Trường học': '#16a34a',
                'Cơ quan': '#1730c2',
            };

            function makeIcon(category) {
                const color = catColors[category] || '#1730c2';
                const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="36" viewBox="0 0 28 36">
            <path d="M14 0C6.27 0 0 6.27 0 14c0 9.63 14 22 14 22S28 23.63 28 14C28 6.27 21.73 0 14 0z"
                  fill="${color}" stroke="#fff" stroke-width="1.5"/>
            <circle cx="14" cy="14" r="5.5" fill="#fff" opacity=".9"/>
        </svg>`;
                return L.divIcon({
                    html: svg,
                    className: '',
                    iconSize: [28, 36],
                    iconAnchor: [14, 36],
                    popupAnchor: [0, -38],
                });
            }

            /* ── 4. Thêm markers ──────────────────────────── */
            const markers = {};

            allLocations.forEach(loc => {
                const imgHtml = loc.image ?
                    `<img src="${loc.image}" class="lf-popup-img" alt="${loc.name}">` :
                    '';

                const popup = L.popup({
                    maxWidth: 240,
                    closeButton: true
                }).setContent(`
            <div class="lf-popup-inner">
                ${imgHtml}
                <div class="lf-popup-name">${loc.name}</div>
                <div class="lf-popup-addr">${loc.address}</div>
                <a href="${loc.mapsUrl}" target="_blank" class="lf-popup-btn">
                    🗺️ Mở Google Maps
                </a>
            </div>
        `);

                const marker = L.marker([loc.lat, loc.lng], {
                        icon: makeIcon(loc.category)
                    })
                    .addTo(map)
                    .bindPopup(popup);

                markers[loc.id] = marker;
            });

            /* ── 5. Tab switcher (mobile) ─────────────────── */
            window.switchTab = function(tab, btn) {
                const mapCol = document.querySelector('.map-col');
                const listCol = document.querySelector('.list-col');
                document.querySelectorAll('.mobile-tabs .tab-btn').forEach(b => b.classList.remove('active'));
                if (btn) btn.classList.add('active');

                if (tab === 'map') {
                    mapCol.style.display = 'block';
                    listCol.style.display = 'none';
                    /* invalidate map size sau khi show lại */
                    setTimeout(() => map.invalidateSize(), 50);
                } else {
                    mapCol.style.display = 'none';
                    listCol.style.display = 'block';
                }
            };

            /* Khởi tạo trạng thái mobile: ẩn list */
            function initMobileTabs() {
                if (window.innerWidth < 768) {
                    document.querySelector('.list-col').style.display = 'none';
                }
            }
            initMobileTabs();
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    document.querySelector('.map-col').style.display = '';
                    document.querySelector('.list-col').style.display = '';
                    map.invalidateSize();
                }
            });

            /* ── 6. Click item trong list ─────────────────── */
            window.focusLocation = function(el) {
                const id = el.dataset.id;
                const hasCoords = el.dataset.hasCoords === '1';
                const lat = parseFloat(el.dataset.lat);
                const lng = parseFloat(el.dataset.lng);
                const mapsUrl = el.dataset.maps;

                /* highlight active */
                document.querySelectorAll('.loc-item').forEach(i => i.classList.remove('active'));
                el.classList.add('active');

                if (hasCoords && markers[id]) {
                    /* Mobile: tự switch sang tab map trước */
                    if (window.innerWidth < 768) {
                        const mapBtn = document.querySelector('.mobile-tabs .tab-btn:first-child');
                        switchTab('map', mapBtn);
                    }
                    setTimeout(() => {
                        map.flyTo([lat, lng], 17, {
                            animate: true,
                            duration: 1.2
                        });
                    }, window.innerWidth < 768 ? 100 : 0);
                    setTimeout(() => markers[id].openPopup(), window.innerWidth < 768 ? 1100 : 900);
                } else {
                    window.open(mapsUrl, '_blank');
                }
            };

            /* ── 7. Click marker → highlight list item ────── */
            Object.entries(markers).forEach(([id, marker]) => {
                marker.on('click', () => {
                    const el = document.querySelector(`.loc-item[data-id="${id}"]`);
                    if (!el) return;
                    document.querySelectorAll('.loc-item').forEach(i => i.classList.remove('active'));
                    el.classList.add('active');
                    /* Mobile: không switch tab, chỉ scroll ngầm khi user xem list */
                    if (window.innerWidth >= 768) {
                        el.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }
                });
            });
        })();
    </script>
@endpush
