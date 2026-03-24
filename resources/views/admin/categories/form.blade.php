@extends('layouts.admin')

@section('title', $category ? 'Sửa Danh mục' : 'Thêm Danh mục')

@push('styles')
<style>
    .preview-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 14px;
        font-size: 15px;
        font-weight: 600;
        color: white;
        min-width: 120px;
    }
    .color-presets {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }
    .color-preset-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid transparent;
        cursor: pointer;
        transition: transform .15s, border-color .15s;
    }
    .color-preset-btn:hover,
    .color-preset-btn.active {
        transform: scale(1.2);
        border-color: #333;
    }
    .icon-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }
    .icon-btn {
        font-size: 22px;
        width: 42px;
        height: 42px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border-color .15s, background .15s;
    }
    .icon-btn:hover,
    .icon-btn.active {
        border-color: #003580;
        background: #f0f4ff;
    }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="mb-0 fw-bold">{{ $category ? 'Sửa danh mục' : 'Thêm danh mục mới' }}</h4>
    </div>
</div>

<form action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
      method="POST">
    @csrf
    @if($category) @method('PUT') @endif

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="admin-card">
                <h6 class="fw-bold mb-3">Thông tin danh mục</h6>

                {{-- Tên --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $category?->name) }}"
                           placeholder="VD: Trường học, Cơ quan, Y tế..." required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Thứ tự --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" class="form-control" style="max-width:120px;"
                           value="{{ old('sort_order', $category?->sort_order ?? 0) }}" min="0" max="255">
                    <div class="form-text">Số nhỏ hiển thị trước. Mặc định: 0</div>
                </div>

                {{-- Màu sắc --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Màu sắc</label>
                    <div class="d-flex align-items-center gap-3">
                        <input type="color" id="colorPicker" class="form-control form-control-color"
                               style="width:50px;height:38px;padding:2px;"
                               value="{{ old('color', $category?->color ?? '#2E7D32') }}">
                        <input type="text" name="color" id="colorText" class="form-control @error('color') is-invalid @enderror"
                               style="max-width:120px;"
                               value="{{ old('color', $category?->color ?? '#2E7D32') }}"
                               placeholder="#2E7D32">
                        @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="color-presets">
                        @foreach(['#1565C0','#2E7D32','#C62828','#E65100','#6A1B9A','#00838F','#F57F17','#4527A0','#283593','#558B2F','#455A64','#BF360C'] as $preset)
                        <button type="button" class="color-preset-btn {{ old('color', $category?->color) === $preset ? 'active' : '' }}"
                                style="background:{{ $preset }};"
                                data-color="{{ $preset }}" title="{{ $preset }}"></button>
                        @endforeach
                    </div>
                </div>

                {{-- Icon --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Icon (emoji)</label>
                    <input type="text" name="icon" id="iconInput" class="form-control @error('icon') is-invalid @enderror"
                           style="max-width:100px;font-size:20px;"
                           value="{{ old('icon', $category?->icon ?? '📍') }}" required maxlength="10">
                    @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="icon-grid">
                        @foreach(['🏛️','🎓','🏥','🏪','📸','⛪','🏗️','🌳','🏋️','🎭','🚒','🏦','🌾','🏨','🎯','📍'] as $emoji)
                        <button type="button" class="icon-btn {{ old('icon', $category?->icon) === $emoji ? 'active' : '' }}"
                                data-icon="{{ $emoji }}">{{ $emoji }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar preview --}}
        <div class="col-lg-5">
            <div class="admin-card" style="position:sticky;top:80px;">
                <h6 class="fw-bold mb-3">Xem trước</h6>
                <div class="mb-2 text-muted" style="font-size:13px;">Hiển thị trên bản đồ và danh sách:</div>
                <div id="preview" class="preview-pill mb-4" style="background:#2E7D32;">
                    <span id="previewIcon">📍</span>
                    <span id="previewName">Tên danh mục</span>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>
                        {{ $category ? 'Cập nhật' : 'Thêm danh mục' }}
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Hủy</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const colorPicker = document.getElementById('colorPicker');
    const colorText   = document.getElementById('colorText');
    const iconInput   = document.getElementById('iconInput');
    const preview     = document.getElementById('preview');
    const previewIcon = document.getElementById('previewIcon');
    const previewName = document.getElementById('previewName');

    function updatePreview() {
        preview.style.background = colorText.value;
        previewIcon.textContent  = iconInput.value || '📍';
        previewName.textContent  = document.querySelector('[name=name]').value || 'Tên danh mục';
    }

    colorPicker.addEventListener('input', () => {
        colorText.value = colorPicker.value;
        updatePreview();
    });

    colorText.addEventListener('input', () => {
        colorPicker.value = colorText.value;
        updatePreview();
    });

    document.querySelector('[name=name]').addEventListener('input', updatePreview);
    iconInput.addEventListener('input', updatePreview);

    // Color presets
    document.querySelectorAll('.color-preset-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.color-preset-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const c = btn.dataset.color;
            colorText.value  = c;
            colorPicker.value = c;
            updatePreview();
        });
    });

    // Icon presets
    document.querySelectorAll('.icon-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            iconInput.value = btn.dataset.icon;
            updatePreview();
        });
    });

    updatePreview();
</script>
@endpush
