@extends('layouts.admin')

@section('title', 'Quản lý Địa điểm')

@push('styles')
<style>
    /* ── TABLE (desktop) ─────────────────────────────── */
    .table th {
        background: #f8f9fa; font-size: 12px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .4px; color: #495057;
        border-top: none; white-space: nowrap;
    }

    .table td { font-size: 13px; vertical-align: middle; }

    .thumb-img {
        width: 52px; height: 40px; object-fit: cover;
        border-radius: 6px; border: 1px solid #dee2e6;
    }

    .thumb-placeholder {
        width: 52px; height: 40px; background: #f0f4ff; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; border: 1px solid #dee2e6;
    }

    .category-pill {
        display: inline-block; padding: 2px 9px; border-radius: 12px;
        font-size: 11px; font-weight: 600; color: white; white-space: nowrap;
    }

    .status-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 12px; font-size: 11px; font-weight: 600;
    }

    .coords-text { font-size: 11px; color: #6c757d; font-family: monospace; line-height: 1.5; }

    .btn-action { padding: 4px 9px; font-size: 12px; border-radius: 6px; }

    .filter-card {
        background: white; border-radius: 10px; padding: 14px 16px;
        margin-bottom: 16px; border: 1px solid #e9ecef;
    }

    /* ── MOBILE CARDS ────────────────────────────────── */
    .loc-card {
        background: white; border-radius: 10px; border: 1px solid #e9ecef;
        padding: 14px; margin-bottom: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
    }

    .loc-card-thumb {
        width: 48px; height: 48px; border-radius: 8px; overflow: hidden;
        border: 1px solid #dee2e6; flex-shrink: 0;
        background: #f0f4ff; display: flex; align-items: center;
        justify-content: center; font-size: 20px;
    }

    .loc-card-thumb img { width: 100%; height: 100%; object-fit: cover; }

    .loc-card-name { font-size: 14px; font-weight: 600; color: #1a1f36; margin-bottom: 4px; }

    .loc-card-meta { font-size: 12px; color: #6b7280; line-height: 1.5; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0" style="font-size:20px;font-weight:700;">
        <i class="bi bi-geo-alt text-primary"></i>
        <span class="d-none d-sm-inline">Quản lý </span>Địa điểm
    </h2>
    <a href="{{ route('admin.locations.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        <span class="d-none d-sm-inline"> Thêm mới</span>
    </a>
</div>

<!-- Filter -->
<form method="GET" class="filter-card">
    <div class="row g-2 align-items-end">
        <div class="col-12 col-sm-5 col-md-4">
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="🔍 Tìm tên địa điểm..." value="{{ request('search') }}">
        </div>
        <div class="col-8 col-sm-4 col-md-3">
            <select name="category" class="form-select form-select-sm">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>
                        {{ $cat->icon }} {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-4 col-sm-3 col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                <i class="bi bi-search"></i>
            </button>
            @if(request('search') || request('category'))
                <a href="{{ route('admin.locations.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x"></i>
                </a>
            @endif
        </div>
    </div>
</form>

{{-- ── DESKTOP TABLE ── d-none d-md-block ───────────── --}}
<div class="admin-card d-none d-md-block">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:60px;">Ảnh</th>
                    <th>Tên địa điểm</th>
                    <th style="width:130px;">Danh mục</th>
                    <th>Địa chỉ</th>
                    <th style="width:120px;">Tọa độ</th>
                    <th style="width:85px;">Trạng thái</th>
                    <th style="width:100px;text-align:center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $location)
                    @php
                        $cat   = $categories[$location->category] ?? null;
                        $color = $cat?->color ?? '#455A64';
                        $icon  = $cat?->icon  ?? '📍';
                    @endphp
                    <tr>
                        <td>
                            @if($location->image)
                                <img src="{{ asset('storage/' . $location->image) }}" class="thumb-img" alt="">
                            @else
                                <div class="thumb-placeholder">{{ $icon }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $location->name }}</div>
                            @if($location->description)
                                <small class="text-muted">{{ Str::limit($location->description, 55) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($location->category)
                                <span class="category-pill" style="background:{{ $color }};">
                                    {{ $icon }} {{ $location->category }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><span style="font-size:12px;">{{ $location->address ?? '—' }}</span></td>
                        <td>
                            @if($location->latitude && $location->longitude)
                                <div class="coords-text">
                                    <i class="bi bi-crosshair text-success"></i>
                                    {{ number_format($location->latitude, 4) }},
                                    {{ number_format($location->longitude, 4) }}
                                </div>
                            @else
                                <span class="text-muted" style="font-size:12px;">
                                    <i class="bi bi-x-circle text-danger"></i> Chưa có
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($location->is_active)
                                <span class="status-badge" style="background:#d1fae5;color:#065f46;">
                                    <i class="bi bi-check-circle-fill"></i> Hiện
                                </span>
                            @else
                                <span class="status-badge" style="background:#f3f4f6;color:#6b7280;">
                                    <i class="bi bi-dash-circle"></i> Ẩn
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.locations.edit', $location) }}"
                                   class="btn btn-outline-primary btn-action" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.locations.destroy', $location) }}" method="POST"
                                      onsubmit="return confirm('Xác nhận xóa?')">
                                    @csrf @method('DELETE')
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
                            <i class="bi bi-geo-alt" style="font-size:36px;display:block;margin-bottom:8px;opacity:.3;"></i>
                            Chưa có địa điểm nào.
                            <a href="{{ route('admin.locations.create') }}">Thêm ngay</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($locations->hasPages())
        <div class="p-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-2">
            <small class="text-muted">
                Hiển thị {{ $locations->firstItem() }}–{{ $locations->lastItem() }} / {{ $locations->total() }}
            </small>
            {{ $locations->links() }}
        </div>
    @else
        <div class="px-3 py-2 border-top">
            <small class="text-muted">Tổng: {{ $locations->total() }} địa điểm</small>
        </div>
    @endif
</div>

{{-- ── MOBILE CARDS ── d-md-none ───────────────────── --}}
<div class="d-md-none">
    @forelse($locations as $location)
        @php
            $cat   = $categories[$location->category] ?? null;
            $color = $cat?->color ?? '#455A64';
            $icon  = $cat?->icon  ?? '📍';
        @endphp
        <div class="loc-card">
            <div class="d-flex gap-3 align-items-start">
                {{-- thumb --}}
                <div class="loc-card-thumb">
                    @if($location->image)
                        <img src="{{ asset("storage/{$location->image}") }}" alt="">
                    @else
                        {{ $icon }}
                    @endif
                </div>

                {{-- info --}}
                <div class="flex-fill min-width-0">
                    <div class="loc-card-name">{{ $location->name }}</div>
                    <div class="loc-card-meta">
                        @if($location->category)
                            <span class="category-pill me-1" style="background:{{ $color }};">
                                {{ $icon }} {{ $location->category }}
                            </span>
                        @endif
                        @if($location->is_active)
                            <span class="status-badge" style="background:#d1fae5;color:#065f46;">
                                <i class="bi bi-check-circle-fill"></i> Hiện
                            </span>
                        @else
                            <span class="status-badge" style="background:#f3f4f6;color:#6b7280;">
                                <i class="bi bi-dash-circle"></i> Ẩn
                            </span>
                        @endif
                    </div>
                    @if($location->address)
                        <div class="loc-card-meta mt-1">
                            <i class="bi bi-geo-alt text-muted"></i> {{ $location->address }}
                        </div>
                    @endif
                </div>

                {{-- actions --}}
                <div class="d-flex flex-column gap-1" style="flex-shrink:0;">
                    <a href="{{ route('admin.locations.edit', $location) }}"
                       class="btn btn-outline-primary btn-sm" style="padding:4px 10px;">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('admin.locations.destroy', $location) }}" method="POST"
                          onsubmit="return confirm('Xóa địa điểm này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100" style="padding:4px 10px;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-geo-alt" style="font-size:36px;display:block;margin-bottom:8px;opacity:.3;"></i>
            Chưa có địa điểm nào. <a href="{{ route('admin.locations.create') }}">Thêm ngay</a>
        </div>
    @endforelse

    @if($locations->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $locations->links() }}
        </div>
    @endif
</div>

@endsection
