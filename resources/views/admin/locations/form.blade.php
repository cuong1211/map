@extends('layouts.admin')

@section('title', $location ? 'Sửa địa điểm' : 'Thêm địa điểm mới')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
    #picker-map {
        height: 300px; border-radius: 10px;
        border: 2px solid #dee2e6; z-index: 1;
    }

    #picker-map.map-active { border-color: #003580; }

    .form-section {
        background: white; border-radius: 10px;
        padding: 20px; border: 1px solid #e9ecef; margin-bottom: 16px;
    }

    .section-title {
        font-size: 14px; font-weight: 700; color: #212529;
        margin-bottom: 16px; padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
        display: flex; align-items: center; gap: 8px;
    }

    .image-preview-box {
        width: 100%; height: 180px;
        border: 2px dashed #dee2e6; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden; background: #f8f9fa; cursor: pointer;
        transition: border-color .2s;
    }

    .image-preview-box:hover { border-color: #003580; }

    .image-preview-box img { width: 100%; height: 100%; object-fit: cover; }

    .image-preview-box .placeholder-text {
        text-align: center; color: #adb5bd; padding: 16px;
    }

    .map-instructions {
        background: #e8efff; border: 1px solid #c3d4f5;
        border-radius: 8px; padding: 9px 13px;
        font-size: 12px; color: #003580; margin-bottom: 12px;
    }

    .coord-label {
        background: #f0f4ff; border: 1px solid #dee2e6;
        padding: 8px 10px; border-radius: 8px 0 0 8px;
        font-size: 11px; font-weight: 700; color: #003580;
        display: flex; align-items: center;
    }

    @media (max-width: 991px) {
        .sticky-sidebar { position: static !important; }
    }

    @media (max-width: 575px) {
        #picker-map { height: 240px; }
        .form-section { padding: 14px; }
    }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('admin.locations.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2 class="mb-0" style="font-size:18px;font-weight:700;">
        {{ $location ? 'Sửa: ' . Str::limit($location->name, 40) : 'Thêm địa điểm mới' }}
    </h2>
</div>

<form action="{{ $location ? route('admin.locations.update', $location) : route('admin.locations.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($location) @method('PUT') @endif

    <div class="row g-3">

        {{-- ── CỘT TRÁI: Thông tin ──────────────────── --}}
        <div class="col-lg-8 order-2 order-lg-1">

            {{-- Thông tin cơ bản --}}
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-info-circle text-primary"></i> Thông tin cơ bản
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">
                        Tên địa điểm <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $location?->name) }}"
                           placeholder="Nhập tên địa điểm..." required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold" style="font-size:13px;">Danh mục</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->name }}"
                                    @selected(old('category', $location?->category) === $cat->name)>
                                    {{ $cat->icon }} {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold" style="font-size:13px;">Địa chỉ</label>
                        <input type="text" name="address"
                               class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address', $location?->address) }}"
                               placeholder="Địa chỉ cụ thể...">
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">Mô tả</label>
                    <textarea name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="3"
                              placeholder="Mô tả chi tiết về địa điểm...">{{ old('description', $location?->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active"
                           value="1" id="is_active"
                           @checked(old('is_active', $location ? $location->is_active : true))>
                    <label class="form-check-label fw-semibold" for="is_active" style="font-size:13px;">
                        Hiển thị trên trang chủ
                    </label>
                </div>
            </div>

            {{-- Tọa độ GPS --}}
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-crosshair text-primary"></i> Tọa độ GPS
                </div>

                <div class="map-instructions">
                    <i class="bi bi-info-circle me-1"></i>
                    Nhấp vào bản đồ để chọn tọa độ, hoặc nhập thủ công bên dưới.
                </div>

                <div id="picker-map" class="mb-3"></div>

                <div class="row g-3 mb-2">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold" style="font-size:13px;">Vĩ độ (Latitude)</label>
                        <div class="input-group">
                            <span class="coord-label">LAT</span>
                            <input type="number" name="latitude" id="lat-input"
                                   class="form-control @error('latitude') is-invalid @enderror"
                                   value="{{ old('latitude', $location?->latitude) }}"
                                   step="0.0000001" placeholder="21.7518...">
                            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold" style="font-size:13px;">Kinh độ (Longitude)</label>
                        <div class="input-group">
                            <span class="coord-label">LNG</span>
                            <input type="number" name="longitude" id="lng-input"
                                   class="form-control @error('longitude') is-invalid @enderror"
                                   value="{{ old('longitude', $location?->longitude) }}"
                                   step="0.0000001" placeholder="106.0734...">
                            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetCoords()">
                        <i class="bi bi-x-circle"></i> Xóa tọa độ
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="goToCoords()">
                        <i class="bi bi-crosshair"></i> Đến tọa độ
                    </button>
                </div>
            </div>
        </div>

        {{-- ── CỘT PHẢI: Ảnh + Lưu ─────────────────── --}}
        <div class="col-lg-4 order-1 order-lg-2">
            <div class="form-section sticky-sidebar" style="position:sticky;top:70px;">
                <div class="section-title">
                    <i class="bi bi-image text-primary"></i> Hình ảnh
                </div>

                <label for="image-input" class="image-preview-box mb-3" id="preview-box">
                    @if($location?->image)
                        <img src="{{ asset("storage/{$location->image}") }}" id="img-preview" alt="Preview">
                    @else
                        <div class="placeholder-text" id="placeholder-content">
                            <i class="bi bi-cloud-upload" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                            <div style="font-size:13px;">Nhấp để chọn ảnh</div>
                            <small>JPG, PNG, WebP — tối đa 5MB</small>
                        </div>
                    @endif
                </label>

                <input type="file" name="image" id="image-input"
                       class="form-control form-control-sm @error('image') is-invalid @enderror"
                       accept="image/*">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                @if($location?->image)
                    <div class="mt-2 p-2 bg-light rounded" style="font-size:12px;">
                        <i class="bi bi-image text-muted"></i>
                        Ảnh hiện tại: <code>{{ basename($location->image) }}</code>
                    </div>
                @endif

                <hr class="my-3">

                <button type="submit" class="btn btn-primary w-100 fw-bold mb-2">
                    <i class="bi bi-save me-1"></i>
                    {{ $location ? 'Cập nhật địa điểm' : 'Lưu địa điểm' }}
                </button>
                <a href="{{ route('admin.locations.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x me-1"></i> Hủy
                </a>
            </div>
        </div>

    </div>
</form>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Image preview ────────────────────────────────
    document.getElementById('image-input').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = ev => {
            document.getElementById('preview-box').innerHTML =
                `<img src="${ev.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(file);
    });

    // ── Map picker ───────────────────────────────────
    const defaultLat = {{ old('latitude', $location?->latitude ?? 21.75182118692702) }};
    const defaultLng = {{ old('longitude', $location?->longitude ?? 106.07343143637803) }};
    const hasCoords  = {{ $location?->latitude && $location?->longitude || old('latitude') ? 'true' : 'false' }};

    const map = L.map('picker-map').setView([defaultLat, defaultLng], hasCoords ? 15 : 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors', maxZoom: 19,
    }).addTo(map);

    let marker = null;

    if (hasCoords) {
        marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
        marker.on('dragend', e => {
            const p = e.target.getLatLng();
            updateInputs(p.lat, p.lng);
        });
    }

    map.on('click', function (e) {
        const { lat, lng } = e.latlng;
        updateInputs(lat, lng);
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', ev => {
                const p = ev.target.getLatLng();
                updateInputs(p.lat, p.lng);
            });
        }
    });

    function updateInputs(lat, lng) {
        document.getElementById('lat-input').value = lat.toFixed(7);
        document.getElementById('lng-input').value = lng.toFixed(7);
    }

    document.getElementById('lat-input').addEventListener('change', goToCoords);
    document.getElementById('lng-input').addEventListener('change', goToCoords);

    window.goToCoords = function () {
        const lat = parseFloat(document.getElementById('lat-input').value);
        const lng = parseFloat(document.getElementById('lng-input').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            map.setView([lat, lng], 16);
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                marker.on('dragend', ev => {
                    const p = ev.target.getLatLng();
                    updateInputs(p.lat, p.lng);
                });
            }
        }
    };

    window.resetCoords = function () {
        document.getElementById('lat-input').value  = '';
        document.getElementById('lng-input').value  = '';
        if (marker) { map.removeLayer(marker); marker = null; }
    };
});
</script>
@endpush
