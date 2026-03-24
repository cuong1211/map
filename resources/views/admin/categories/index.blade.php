@extends('layouts.admin')

@section('title', 'Quản lý Danh mục')

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
    .category-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        color: white;
    }
    .color-dot {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: inline-block;
        border: 2px solid rgba(0,0,0,0.1);
    }
    .usage-badge {
        background: #e9ecef;
        color: #495057;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
    }
    .usage-badge.has-items {
        background: #d1e7dd;
        color: #0f5132;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Quản lý Danh mục</h4>
        <p class="text-muted mb-0" style="font-size:14px;">{{ $categories->count() }} danh mục</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Thêm danh mục
    </a>
</div>

<div class="admin-card">
    @if($categories->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-tag" style="font-size:40px;display:block;margin-bottom:12px;color:#d1ddf5;"></i>
            <p>Chưa có danh mục nào. <a href="{{ route('admin.categories.create') }}">Thêm ngay</a></p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Tên danh mục</th>
                        <th style="width:100px;">Màu sắc</th>
                        <th style="width:80px;">Icon</th>
                        <th style="width:80px;">Thứ tự</th>
                        <th style="width:120px;">Địa điểm</th>
                        <th style="width:120px;text-align:center;">Thao tác</th>
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
                                <code style="font-size:12px;">{{ $cat->color }}</code>
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
                            <a href="{{ route('admin.categories.edit', $cat) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}"
                                  method="POST" style="display:inline;"
                                  onsubmit="return confirm('Xóa danh mục \"{{ $cat->name }}\"?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    {{ ($usageCounts[$cat->name] ?? 0) > 0 ? 'disabled title=Đang có địa điểm sử dụng' : '' }}>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
