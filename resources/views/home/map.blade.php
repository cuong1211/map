@extends('layouts.app')

@section('title', 'Bản đồ địa điểm - ' . config('app.name'))

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .map-section {
        background: white;
        border-bottom: 1px solid #dee2e6;
        padding: 16px 0;
    }

    #map {
        height: 600px;
        width: 100%;
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        z-index: 1;
    }

    .map-controls {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .map-controls .btn-filter {
        border-radius: 20px;
        padding: 5px 16px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid;
    }

    /* Legend */
    .map-legend {
        background: white;
        border-radius: 10px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
    }

    .map-legend h6 {
        font-size: 13px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 5px 0;
        font-size: 13px;
        color: #495057;
    }

    .legend-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        flex-shrink: 0;
        border: 2px solid rgba(0,0,0,0.15);
    }

    .legend-count {
        margin-left: auto;
        background: #f0f2f5;
        border-radius: 10px;
        padding: 1px 8px;
        font-size: 11px;
        font-weight: 600;
        color: #6c757d;
    }

    /* Map info panel */
    .map-info-bar {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 13px;
        color: #495057;
        border: 1px solid #e9ecef;
    }

    /* Leaflet popup custom */
    .leaflet-popup-content-wrapper {
        border-radius: 10px !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2) !important;
        padding: 0 !important;
        overflow: hidden;
    }

    .leaflet-popup-content {
        margin: 0 !important;
        width: 220px !important;
    }

    .popup-card {
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    .popup-image {
        width: 100%;
        height: 110px;
        object-fit: cover;
        display: block;
    }

    .popup-image-placeholder {
        width: 100%;
        height: 80px;
        background: linear-gradient(135deg, #e8efff, #dce8f5);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }

    .popup-body {
        padding: 12px;
    }

    .popup-name {
        font-size: 14px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .popup-category {
        display: inline-block;
        font-size: 10px;
        font-weight: 600;
        color: white;
        border-radius: 12px;
        padding: 2px 8px;
        margin-bottom: 6px;
    }

    .popup-address {
        font-size: 11px;
        color: #6c757d;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .popup-btn {
        display: block;
        width: 100%;
        background: #003580;
        color: white;
        text-align: center;
        padding: 7px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
    }

    .popup-btn:hover {
        background: #0056b3;
        color: white;
    }
</style>
@endpush

@section('content')

<!-- Page Header -->
<div class="map-section">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h2 class="mb-1" style="font-size: 22px; font-weight: 700; color: #212529;">
                    <i class="bi bi-map text-primary"></i> Bản đồ địa điểm
                </h2>
                <p class="text-muted mb-0" style="font-size: 14px;">Xã Ba Chẽ, Huyện Ba Chẽ, Tỉnh Quảng Ninh</p>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-grid"></i> Xem dạng danh sách
            </a>
        </div>
    </div>
</div>

<!-- Map Content -->
<section class="py-4">
    <div class="container-fluid px-4">
        <div class="row g-4">

            <!-- Map -->
            <div class="col-lg-9">
                <!-- Info Bar -->
                <div class="map-info-bar mb-3">
                    <i class="bi bi-info-circle text-primary"></i>
                    Nhấn vào điểm đánh dấu trên bản đồ để xem thông tin chi tiết.
                    <span id="marker-count" class="fw-bold text-primary">Đang tải...</span>
                </div>

                <!-- Map Container -->
                <div id="map"></div>
            </div>

            <!-- Sidebar: Legend + Category Filter -->
            <div class="col-lg-3">
                <!-- Category Filter -->
                <div class="map-legend mb-3">
                    <h6><i class="bi bi-funnel"></i> Lọc danh mục</h6>
                    <div id="category-filters">
                        <label class="legend-item" style="cursor: pointer;">
                            <input type="checkbox" checked data-category="all" id="filter-all" style="display:none">
                            <div class="legend-dot" style="background: #003580;"></div>
                            <span>Tất cả</span>
                            <span class="legend-count" id="count-all">0</span>
                        </label>
                        @php
                            $categoryColors = [
                                'Hành chính' => '#1565C0',
                                'Giáo dục' => '#2E7D32',
                                'Y tế' => '#C62828',
                                'Kinh doanh' => '#E65100',
                                'Du lịch' => '#6A1B9A',
                            ];
                        @endphp
                        @foreach($categories as $cat)
                        <label class="legend-item" style="cursor: pointer;">
                            <input type="checkbox" checked class="category-filter-input" data-category="{{ $cat }}" style="display:none">
                            <div class="legend-dot" style="background: {{ $categoryColors[$cat] ?? '#455A64' }};"></div>
                            <span>{{ $cat }}</span>
                            <span class="legend-count" id="count-{{ Str::slug($cat) }}">0</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Legend -->
                <div class="map-legend">
                    <h6><i class="bi bi-bookmark"></i> Chú thích màu sắc</h6>
                    @php
                        $legendItems = [
                            ['Hành chính', '#1565C0', '🏛️'],
                            ['Giáo dục', '#2E7D32', '🎓'],
                            ['Y tế', '#C62828', '🏥'],
                            ['Kinh doanh', '#E65100', '🏪'],
                            ['Du lịch', '#6A1B9A', '📸'],
                            ['Khác', '#455A64', '📍'],
                        ];
                    @endphp
                    @foreach($legendItems as [$label, $color, $emoji])
                    <div class="legend-item">
                        <div class="legend-dot" style="background: {{ $color }};"></div>
                        <span>{{ $emoji }} {{ $label }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Quick Stats -->
                <div class="map-legend mt-3">
                    <h6><i class="bi bi-bar-chart"></i> Thống kê</h6>
                    <div id="map-stats" class="text-muted" style="font-size: 13px;">
                        Đang tải dữ liệu...
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/WLcE=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize map centered on Ba Che area
    const map = L.map('map').setView([21.5200, 107.2000], 13);

    // Tile layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    const categoryColors = {
        'Hành chính': '#1565C0',
        'Giáo dục': '#2E7D32',
        'Y tế': '#C62828',
        'Kinh doanh': '#E65100',
        'Du lịch': '#6A1B9A',
    };

    const categoryIcons = {
        'Hành chính': '🏛️',
        'Giáo dục': '🎓',
        'Y tế': '🏥',
        'Kinh doanh': '🏪',
        'Du lịch': '📸',
    };

    function getColor(category) {
        return categoryColors[category] || '#455A64';
    }

    function getIcon(category) {
        return categoryIcons[category] || '📍';
    }

    function createMarkerIcon(color) {
        return L.divIcon({
            className: '',
            html: `<div style="
                width: 28px; height: 28px;
                background: ${color};
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                border: 3px solid white;
                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            "></div>`,
            iconSize: [28, 28],
            iconAnchor: [14, 28],
            popupAnchor: [0, -30],
        });
    }

    let allMarkers = [];
    let markersByCategory = {};
    let totalWithCoords = 0;

    // Fetch locations
    fetch('{{ route('api.locations') }}')
        .then(res => res.json())
        .then(data => {
            const locations = data.locations;
            totalWithCoords = 0;

            const categoryCounts = {};

            locations.forEach(loc => {
                if (!loc.latitude || !loc.longitude) return;
                totalWithCoords++;

                const color = getColor(loc.category);
                const icon = getIcon(loc.category);
                const marker = L.marker([loc.latitude, loc.longitude], {
                    icon: createMarkerIcon(color),
                    title: loc.name,
                });

                // Build popup content
                const mapsUrl = `https://www.google.com/maps?q=${loc.latitude},${loc.longitude}`;
                const imgHtml = loc.image
                    ? `<img src="${loc.image}" alt="${loc.name}" class="popup-image">`
                    : `<div class="popup-image-placeholder">${icon}</div>`;

                const catBadge = loc.category
                    ? `<span class="popup-category" style="background:${color}">${loc.category}</span>`
                    : '';

                const addrHtml = loc.address
                    ? `<div class="popup-address"><i>📍</i> ${loc.address}</div>`
                    : '';

                marker.bindPopup(`
                    <div class="popup-card">
                        ${imgHtml}
                        <div class="popup-body">
                            ${catBadge}
                            <div class="popup-name">${loc.name}</div>
                            ${addrHtml}
                            <a href="${mapsUrl}" target="_blank" class="popup-btn">
                                🗺️ Mở Google Maps
                            </a>
                        </div>
                    </div>
                `, { maxWidth: 240 });

                allMarkers.push({ marker, category: loc.category });

                if (loc.category) {
                    if (!markersByCategory[loc.category]) {
                        markersByCategory[loc.category] = [];
                    }
                    markersByCategory[loc.category].push(marker);
                    categoryCounts[loc.category] = (categoryCounts[loc.category] || 0) + 1;
                }

                marker.addTo(map);
            });

            // Update counts
            document.getElementById('count-all').textContent = totalWithCoords;
            document.getElementById('marker-count').textContent = `${totalWithCoords} địa điểm trên bản đồ`;

            Object.entries(categoryCounts).forEach(([cat, count]) => {
                const el = document.getElementById('count-' + cat.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, ''));
                // Try with Laravel's Str::slug equivalent pattern
                const slug = cat.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
                const elSlug = document.getElementById('count-' + slug);
                if (elSlug) elSlug.textContent = count;
            });

            // Stats panel
            let statsHtml = `<p class="mb-1"><strong>${locations.length}</strong> tổng địa điểm</p>`;
            statsHtml += `<p class="mb-1"><strong>${totalWithCoords}</strong> có tọa độ GPS</p>`;
            statsHtml += `<p class="mb-0"><strong>${locations.length - totalWithCoords}</strong> chưa có tọa độ</p>`;
            document.getElementById('map-stats').innerHTML = statsHtml;

            // Fit map to markers if any
            if (allMarkers.length > 0) {
                const group = new L.featureGroup(allMarkers.map(m => m.marker));
                map.fitBounds(group.getBounds().pad(0.1));
            }
        })
        .catch(err => {
            console.error('Failed to load locations:', err);
            document.getElementById('marker-count').textContent = 'Lỗi tải dữ liệu';
        });

    // Category filter logic
    document.querySelectorAll('.category-filter-input').forEach(checkbox => {
        checkbox.closest('label').addEventListener('click', function () {
            const cat = checkbox.dataset.category;
            const isActive = this.style.opacity !== '0.4';

            if (isActive) {
                this.style.opacity = '0.4';
                // Hide markers for this category
                allMarkers.forEach(({ marker, category }) => {
                    if (category === cat) map.removeLayer(marker);
                });
            } else {
                this.style.opacity = '1';
                // Show markers for this category
                allMarkers.forEach(({ marker, category }) => {
                    if (category === cat) marker.addTo(map);
                });
            }
        });
    });
});
</script>
@endpush
