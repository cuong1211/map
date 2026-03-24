@extends('layouts.admin')

@section('title', 'Quản lý Địa điểm')

@push('styles')
<style>
    .table th {
        background: #f8f9fa;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #495057;
        border-top: none;
    }

    .table td {
        font-size: 14px;
        vertical-align: middle;
    }

    .thumb-img {
        width: 56px;
        height: 42px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }

    .thumb-placeholder {
        width: 56px;
        height: 42px;
        background: #f0f4ff;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        border: 1px solid #dee2e6;
    }

    .category-pill {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        color: white;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .coords-text {
        font-size: 11px;
        color: #6c757d;
        font-family: monospace;
    }

    .btn-action {
        padding: 4px 10px;
        font-size: 12px;
        border-radius: 6px;
    }

    .filter-bar {
        background: white;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 16px;
        border: 1px solid #e9ecef;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h2><i class="bi bi-geo-alt text-primary"></i> Quản lý Địa điểm</h2>
    <a href="{{ route('admin.locations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Thêm địa điểm mới
    </a>
</div>

<!-- Filter Bar -->
<form method="GET" class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted mb-1">TÌM KIẾM</label>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Tên địa điểm..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted mb-1">DANH MỤC</label>
            <select name="category" class="form-select form-select-sm">
                <option value="">Tất cả danh mục</option>
                @foreach(['Hành chính','Giáo dục','Y tế','Kinh doanh','Du lịch'] as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-search"></i> Lọc
            </button>
            @if(request('search') || request('category'))
                <a href="{{ route('admin.locations.index') }}" class="btn btn-outline-secondary btn-sm ms-1">
                    <i class="bi bi-x"></i>
                </a>
            @endif
        </div>
    </div>
</form>

<!-- Locations Table -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:70px;">Ảnh</th>
                    <th>Tên địa điểm</th>
                    <th style="width:120px;">Danh mục</th>
                    <th>Địa chỉ</th>
                    <th style="width:130px;">Tọa độ</th>
                    <th style="width:90px;">Trạng thái</th>
                    <th style="width:120px; text-align:center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $location)
                    @php
                        $categoryColors = [
                            'Hành chính' => '#1565C0',
                            'Giáo dục' => '#2E7D32',
                            'Y tế' => '#C62828',
                            'Kinh doanh' => '#E65100',
                            'Du lịch' => '#6A1B9A',
                        ];
                        $color = $categoryColors[$location->category] ?? '#455A64';
                        $catIcons = ['Hành chính'=>'🏛️','Giáo dục'=>'🎓','Y tế'=>'🏥','Kinh doanh'=>'🏪','Du lịch'=>'📸'];
                        $icon = $catIcons[$location->category] ?? '📍';
                    @endphp
                    <tr>
                        <td>
                            @if($location->image)
                                <img src="{{ asset('storage/' . $location->image) }}" class="thumb-img" alt="{{ $location->name }}">
                            @else
                                <div class="thumb-placeholder">{{ $icon }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $location->name }}</div>
                            @if($location->description)
                                <small class="text-muted">{{ Str::limit($location->description, 60) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($location->category)
                                <span class="category-pill" style="background: {{ $color }};">{{ $location->category }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-size: 13px;">{{ $location->address ?? '—' }}</span>
                        </td>
                        <td>
                            @if($location->latitude && $location->longitude)
                                <div class="coords-text">
                                    <i class="bi bi-crosshair text-success"></i>
                                    {{ number_format($location->latitude, 4) }},<br>
                                    {{ number_format($location->longitude, 4) }}
                                </div>
                            @else
                                <span class="text-muted small"><i class="bi bi-x-circle text-danger"></i> Chưa có</span>
                            @endif
                        </td>
                        <td>
                            @if($location->is_active)
                                <span class="status-badge bg-success bg-opacity-15 text-success">
                                    <i class="bi bi-check-circle-fill"></i> Hiển thị
                                </span>
                            @else
                                <span class="status-badge bg-secondary bg-opacity-15 text-secondary">
                                    <i class="bi bi-dash-circle"></i> Ẩn
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.locations.edit', $location) }}" class="btn btn-outline-primary btn-action" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" onsubmit="return confirm('Xác nhận xóa địa điểm này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-action" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-geo-alt" style="font-size: 40px; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                            Chưa có địa điểm nào. <a href="{{ route('admin.locations.create') }}">Thêm ngay</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($locations->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <small class="text-muted">Hiển thị {{ $locations->firstItem() }}–{{ $locations->lastItem() }} / {{ $locations->total() }} địa điểm</small>
            {{ $locations->links() }}
        </div>
    @else
        <div class="px-3 py-2 border-top">
            <small class="text-muted">Tổng: {{ $locations->total() }} địa điểm</small>
        </div>
    @endif
</div>
@endsection
