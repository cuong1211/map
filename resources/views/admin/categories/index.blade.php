@extends('layouts.admin')

@section('title', 'Quản lý Danh mục')

@push('styles')
<style>
    .table th {
        background: #f8f9fa; font-size: 12px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .4px; color: #495057;
        border-top: none; white-space: nowrap;
    }
    .table td { font-size: 13px; vertical-align: middle; }

    .category-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 12px;
        font-size: 12px; font-weight: 600; color: white;
    }

    .color-dot {
        width: 16px; height: 16px; border-radius: 50%;
        display: inline-block; border: 2px solid rgba(0,0,0,.1);
        flex-shrink: 0;
    }

    .usage-badge {
        background: #f3f4f6; color: #6b7280;
        padding: 2px 8px; border-radius: 10px; font-size: 12px; font-weight: 600;
    }

    .usage-badge.has-items { background: #d1fae5; color: #065f46; }

    .btn-action { padding: 4px 9px; font-size: 12px; border-radius: 6px; }

    /* Mobile cards */
    .cat-card {
        background: white; border-radius: 10px; border: 1px solid #e9ecef;
        padding: 14px; margin-bottom: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0" style="font-size:20px;font-weight:700;">
        <i class="bi bi-tags text-primary"></i>
        <span class="d-none d-sm-inline">Quản lý </span>Danh mục
        <span class="badge bg-secondary ms-1" style="font-size:12px;">{{ $categories->count() }}</span>
    </h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        <span class="d-none d-sm-inline"> Thêm danh mục</span>
    </a>
</div>

@if($categories->isEmpty())
    <div class="admin-card">
        <div class="text-center py-5 text-muted">
            <i class="bi bi-tag" style="font-size:40px;display:block;margin-bottom:12px;opacity:.3;"></i>
            <p>Chưa có danh mục nào. <a href="{{ route('admin.categories.create') }}">Thêm ngay</a></p>
        </div>
    </div>
@else

    {{-- ── DESKTOP TABLE ─────────────────────────── --}}
    <div class="admin-card d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Tên danh mục</th>
                        <th style="width:110px;">Màu sắc</th>
                        <th style="width:60px;">Icon</th>
                        <th style="width:70px;">Thứ tự</th>
                        <th style="width:110px;">Địa điểm</th>
                        <th style="width:100px;text-align:center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <span class="category-pill" style="background:{{ $cat->color }};">
                                {{ $cat->icon }} {{ $cat->name }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="color-dot" style="background:{{ $cat->color }};"></span>
                                <code style="font-size:11px;">{{ $cat->color }}</code>
                            </div>
                        </td>
                        <td style="font-size:20px;">{{ $cat->icon }}</td>
                        <td class="text-muted">{{ $cat->sort_order }}</td>
                        <td>
                            @php $count = $usageCounts[$cat->name] ?? 0; @endphp
                            <span class="usage-badge {{ $count > 0 ? 'has-items' : '' }}">
                                {{ $count }} địa điểm
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.categories.edit', $cat) }}"
                                   class="btn btn-outline-primary btn-action">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                      onsubmit="return confirm('Xóa danh mục \"{{ $cat->name }}\"?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-action"
                                        {{ ($usageCounts[$cat->name] ?? 0) > 0 ? 'disabled' : '' }}
                                        title="{{ ($usageCounts[$cat->name] ?? 0) > 0 ? 'Đang có địa điểm sử dụng' : 'Xóa' }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── MOBILE CARDS ──────────────────────────── --}}
    <div class="d-md-none">
        @foreach($categories as $cat)
        @php $count = $usageCounts[$cat->name] ?? 0; @endphp
        <div class="cat-card">
            <div class="d-flex align-items-center gap-3">
                {{-- Preview --}}
                <span class="category-pill" style="background:{{ $cat->color }};white-space:nowrap;">
                    {{ $cat->icon }} {{ $cat->name }}
                </span>

                {{-- Info --}}
                <div class="flex-fill">
                    <div class="d-flex align-items-center gap-2">
                        <span class="color-dot" style="background:{{ $cat->color }};"></span>
                        <code style="font-size:11px;color:#6b7280;">{{ $cat->color }}</code>
                        <span class="usage-badge {{ $count > 0 ? 'has-items' : '' }} ms-1">
                            {{ $count }}
                        </span>
                    </div>
                    <div style="font-size:12px;color:#9ca3af;margin-top:3px;">Thứ tự: {{ $cat->sort_order }}</div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-1">
                    <a href="{{ route('admin.categories.edit', $cat) }}"
                       class="btn btn-outline-primary btn-sm" style="padding:4px 10px;">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                          onsubmit="return confirm('Xóa danh mục này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                style="padding:4px 10px;"
                                {{ $count > 0 ? 'disabled' : '' }}>
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

@endif
@endsection
