@extends('layouts.admin')

@section('title', $location ? 'Sửa địa điểm' : 'Thêm địa điểm mới')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
    #picker-map {
        height: 360px;
        border-radius: 10px;
        border: 2px solid #dee2e6;
        z-index: 1;
    }

    #picker-map.map-active {
        border-color: #003580;
    }

    .form-section {
        background: white;
        border-radius: 10px;
        padding: 24px;
        border: 1px solid #e9ecef;
        margin-bottom: 20px;
    }

    .form-section-title {
        font-size: 15px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 18px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .image-preview-box {
        width: 100%;
        height: 200px;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f8f9fa;
        transition: border-color 0.2s;
    }

    .image-preview-box:hover {
        border-color: #003580;
    }

    .image-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-preview-box .placeholder-text {
        text-align: center;
        color: #adb5bd;
    }

    .map-instructions {
        background: #e8efff;
        border: 1px solid #c3d4f5;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13px;
        color: #003580;
        margin-bottom: 12px;
    }

    .coord-input-group {
        position: relative;
    }

    .coord-input-group .input-group-text {
        background: #f0f4ff;
        border-color: #dee2e6;
        color: #003580;
        font-size: 12px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h2>
        <i class="bi bi-geo-alt text-primary"></i>
        {{ $location ? 'Sửa địa điểm: ' . $location->name : 'Thêm địa điểm mới' }}
    </h2>
    <a href="{{ route('admin.locations.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<form action="{{ $location ? route('admin.locations.update', $location) : route('admin.locations.store') }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    @if($location) @method('PUT') @endif

    <div class="row g-4">
        <!-- Left Column: Main Info -->
        <div class="col-lg-8">

            <!-- Basic Info -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="bi bi-info-circle text-primary"></i> Thông tin cơ bản
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tên địa điểm <span class="text-danger">*</span></label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $location?->name) }}"
                           placeholder="Nhập tên địa điểm..."
                           required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Danh mục</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach(['Hành chính','Giáo dục','Y tế','Kinh doanh','Du lịch'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $location?->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Địa chỉ</label>
                        <input type="text"
                               name="address"
                               class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address', $location?->address) }}"
                               placeholder="Địa chỉ cụ thể...">
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="4"
                              placeholder="Mô tả chi tiết về địa điểm...">{{ old('description', $location?->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mt-3 d-flex align-items-center gap-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               name="is_active"
                               value="1"
                               id="is_active"
                               {{ old('is_active', $location ? ($location->is_active ? '1' : '') : '1') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">Hiển thị trên trang chủ</label>
                    </div>
                </div>
            </div>

            <!-- Map Picker -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="bi bi-crosshair text-primary"></i> Tọa độ GPS
                </div>

                <div class="map-instructions">
                    <i class="bi bi-info-circle"></i>
                    <strong>Hướng dẫn:</strong> Nhấp vào bản đồ bên dưới để chọn tọa độ tự động, hoặc nhập thủ công vào ô bên dưới.
                </div>

                <!-- Map -->
                <div id="picker-map" class="mb-3"></div>

                <!-- Coordinate Inputs -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Vĩ độ (Latitude)</label>
                        <div class="input-group coord-input-group">
                            <span class="input-group-text">LAT</span>
                            <input type="number"
                                   name="latitude"
                                   id="lat-input"
                                   class="form-control @error('latitude') is-invalid @enderror"
                                   value="{{ old('latitude', $location?->latitude) }}"
                                   step="0.0000001"
                                   placeholder="21.5200000">
                            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kinh độ (Longitude)</label>
                        <div class="input-group coord-input-group">
                            <span class="input-group-text">LNG</span>
                            <input type="number"
                                   name="longitude"
                                   id="lng-input"
                                   class="form-control @error('longitude') is-invalid @enderror"
                                   value="{{ old('longitude', $location?->longitude) }}"
                                   step="0.0000001"
                                   placeholder="107.2000000">
                            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-2 d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetCoords()">
                        <i class="bi bi-x-circle"></i> Xóa tọa độ
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="goToCoords()">
                        <i class="bi bi-crosshair"></i> Đến tọa độ
                    </button>
                </div>
            </div>

        </div>

        <!-- Right Column: Image -->
        <div class="col-lg-4">
            <div class="form-section" style="position: sticky; top: 80px;">
                <div class="form-section-title">
                    <i class="bi bi-image text-primary"></i> Hình ảnh
                </div>

                <!-- Image Preview -->
                <div class="image-preview-box mb-3" id="preview-box">
                    @if($location?->image)
                        <img src="{{ asset('storage/' . $location->image) }}" id="img-preview" alt="Preview">
                    @else
                        <div class="placeholder-text" id="placeholder-content">
                            <i class="bi bi-cloud-upload" style="font-size: 36px; display: block; margin-bottom: 8px;"></i>
                            <div>Chọn ảnh để xem trước</div>
                            <small>JPG, PNG, GIF, WebP — tối đa 5MB</small>
                        </div>
                    @endif
                </div>

                <input type="file"
                       name="image"
                       id="image-input"
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/*">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                @if($location?->image)
                    <div class="mt-2 p-2 bg-light rounded">
                        <small class="text-muted"><i class="bi bi-image"></i> Ảnh hiện tại: <code>{{ basename($location->image) }}</code></small>
                    </div>
                @endif

                <hr>

                <!-- Save Button -->
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                    <i class="bi bi-save"></i>
                    {{ $location ? 'Cập nhật địa điểm' : 'Lưu địa điểm' }}
                </button>
                <a href="{{ route('admin.locations.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="bi bi-x"></i> Hủy
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
    // Image Preview
    document.getElementById('image-input').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (ev) {
            const box = document.getElementById('preview-box');
            box.innerHTML = `<img src="${ev.target.result}" id="img-preview" style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(file);
    });

    // Map Picker
    const defaultLat = {{ old('latitude', $location?->latitude ?? 21.520) }};
    const defaultLng = {{ old('longitude', $location?->longitude ?? 107.200) }};
    const hasCoords = {{ ($location?->latitude && $location?->longitude) || old('latitude') ? 'true' : 'false' }};

    const map = L.map('picker-map').setView([defaultLat, defaultLng], hasCoords ? 15 : 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    let marker = null;

    // If location has coords, add initial marker
    if (hasCoords) {
        marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
        marker.on('dragend', function (e) {
            const pos = e.target.getLatLng();
            updateInputs(pos.lat, pos.lng);
        });
    }

    // Click on map to set coordinates
    map.on('click', function (e) {
        const { lat, lng } = e.latlng;
        updateInputs(lat, lng);

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function (ev) {
                const pos = ev.target.getLatLng();
                updateInputs(pos.lat, pos.lng);
            });
        }
    });

    function updateInputs(lat, lng) {
        document.getElementById('lat-input').value = lat.toFixed(7);
        document.getElementById('lng-input').value = lng.toFixed(7);
    }

    // Manual coordinate change
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
                marker.on('dragend', function (ev) {
                    const pos = ev.target.getLatLng();
                    document.getElementById('lat-input').value = pos.lat.toFixed(7);
                    document.getElementById('lng-input').value = pos.lng.toFixed(7);
                });
            }
        }
    };

    window.resetCoords = function () {
        document.getElementById('lat-input').value = '';
        document.getElementById('lng-input').value = '';
        if (marker) {
            map.removeLayer(marker);
            marker = null;
        }
    };
});
</script>
@endpush
