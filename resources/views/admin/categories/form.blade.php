@extends('layouts.admin')

@section('title', $category ? 'Sửa Danh mục' : 'Thêm Danh mục')

@push('styles')
<style>
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

    .preview-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 18px; border-radius: 14px;
        font-size: 15px; font-weight: 600; color: white;
    }

    .color-preset-btn {
        width: 28px; height: 28px; border-radius: 50%;
        border: 2px solid transparent; cursor: pointer;
        transition: transform .15s, border-color .15s; flex-shrink: 0;
    }

    .color-preset-btn:hover,
    .color-preset-btn.active {
        transform: scale(1.2); border-color: #333;
    }

    .icon-btn {
        font-size: 20px; width: 40px; height: 40px;
        border: 2px solid #dee2e6; border-radius: 8px;
        background: white; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: border-color .15s, background .15s;
    }

    .icon-btn:hover,
    .icon-btn.active { border-color: #003580; background: #f0f4ff; }

    @media (max-width: 991px) {
        .sticky-preview { position: static !important; }
    }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2 class="mb-0" style="font-size:18px;font-weight:700;">
        {{ $category ? 'Sửa danh mục' : 'Thêm danh mục mới' }}
    </h2>
</div>

<form action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
      method="POST">
    @csrf
    @if($category) @method('PUT') @endif

    <div class="row g-3">

        {{-- ── Thông tin ─────────────────────────── --}}
        <div class="col-lg-7">
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-tag text-primary"></i> Thông tin danh mục
                </div>

                {{-- Tên --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">
                        Tên danh mục <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $category?->name) }}"
                           placeholder="VD: Trường học, Cơ quan, Y tế..." required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Thứ tự --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:13px;">Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" class="form-control" style="max-width:110px;"
                           value="{{ old('sort_order', $category?->sort_order ?? 0) }}"
                           min="0" max="255">
                    <div class="form-text">Số nhỏ hiển thị trước.</div>
                </div>

                {{-- Màu --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:13px;">Màu sắc</label>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <input type="color" id="colorPicker"
                               style="width:44px;height:36px;padding:2px;border-radius:8px;border:1px solid #dee2e6;cursor:pointer;"
                               value="{{ old('color', $category?->color ?? '#2E7D32') }}">
                        <input type="text" name="color" id="colorText"
                               class="form-control @error('color') is-invalid @enderror"
                               style="max-width:110px;"
                               value="{{ old('color', $category?->color ?? '#2E7D32') }}"
                               placeholder="#2E7D32">
                        @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(['#1565C0','#2E7D32','#C62828','#E65100','#6A1B9A','#00838F','#F57F17','#4527A0','#283593','#558B2F','#455A64','#BF360C'] as $preset)
                        <button type="button" class="color-preset-btn {{ old('color', $category?->color) === $preset ? 'active' : '' }}"
                                style="background:{{ $preset }};"
                                data-color="{{ $preset }}" title="{{ $preset }}"></button>
                        @endforeach
                    </div>
                </div>

                {{-- Icon --}}
                <div class="mb-2">
                    <label class="form-label fw-semibold" style="font-size:13px;">Icon (emoji)</label>
                    <input type="text" name="icon" id="iconInput"
                           class="form-control @error('icon') is-invalid @enderror"
                           style="max-width:90px;font-size:18px;"
                           value="{{ old('icon', $category?->icon ?? '📍') }}"
                           required maxlength="10">
                    @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach(['🏛️','🎓','🏥','🏪','📸','⛪','🏗️','🌳','🏋️','🎭','🚒','🏦','🌾','🏨','🎯','📍'] as $emoji)
                        <button type="button"
                                class="icon-btn {{ old('icon', $category?->icon) === $emoji ? 'active' : '' }}"
                                data-icon="{{ $emoji }}">{{ $emoji }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Preview + Lưu ─────────────────────── --}}
        <div class="col-lg-5">
            <div class="form-section sticky-preview" style="position:sticky;top:70px;">
                <div class="section-title">
                    <i class="bi bi-eye text-primary"></i> Xem trước
                </div>

                <div class="text-muted mb-2" style="font-size:12px;">Hiển thị trên bản đồ và danh sách:</div>
                <div id="preview" class="preview-pill mb-4" style="background:#2E7D32;">
                    <span id="previewIcon">📍</span>
                    <span id="previewName">Tên danh mục</span>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="bi bi-check-lg me-1"></i>
                        {{ $category ? 'Cập nhật' : 'Thêm danh mục' }}
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x me-1"></i> Hủy
                    </a>
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

    colorPicker.addEventListener('input', () => { colorText.value  = colorPicker.value; updatePreview(); });
    colorText.addEventListener('input',   () => { colorPicker.value = colorText.value;  updatePreview(); });
    document.querySelector('[name=name]').addEventListener('input', updatePreview);
    iconInput.addEventListener('input', updatePreview);

    document.querySelectorAll('.color-preset-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.color-preset-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            colorText.value = colorPicker.value = btn.dataset.color;
            updatePreview();
        });
    });

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
