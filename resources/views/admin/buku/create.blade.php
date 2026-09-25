@extends('admin.layout.main')

@section('title', 'Tambah Buku')

@section('content')
<div class="container-fluid">

    {{-- ==================== PAGE HEADER ==================== --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-header-buku">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="header-icon-wrapper">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="mb-0 font-weight-bold">Tambah Buku Baru</h3>
                            <small class="opacity-75">Isi form di bawah untuk menambah koleksi</small>
                        </div>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <a href="{{ route('admin.master.buku.index') }}" class="btn btn-light btn-modern">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== FORM CARD ==================== --}}
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card card-modern">

                <div class="card-header card-header-modern">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-book-medical text-primary mr-2"></i>
                        <h5 class="mb-0 font-weight-bold">Form Buku</h5>
                    </div>
                </div>

                <form action="{{ route('admin.master.buku.store') }}" method="POST"
                      id="bukuForm" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body p-4">

                        <div class="row">

                            {{-- Left Column --}}
                            <div class="col-lg-8">

                                {{-- Judul Buku --}}
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-book text-primary mr-1"></i>
                                        Judul Buku <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-modern"
                                           name="judul_buku"
                                           placeholder="Contoh: Pemrograman Web Modern"
                                           required>
                                </div>

                                {{-- Kategori --}}
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-tags text-info mr-1"></i>
                                        Kategori <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control form-control-modern select2"
                                            name="id_kategori" id="id_kategori" style="width: 100%;" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($kategori as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Pengarang & Penerbit --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-user-edit text-success mr-1"></i>
                                                Pengarang <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control form-control-modern"
                                                   name="pengarang"
                                                   placeholder="Contoh: Ahmad Hafidzul"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-building text-warning mr-1"></i>
                                                Penerbit <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control form-control-modern"
                                                   name="penerbit"
                                                   placeholder="Contoh: Universitas Nusa Mandiri"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                {{-- ISBN & Tahun --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-barcode text-danger mr-1"></i>
                                                ISBN <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control form-control-modern"
                                                   name="isbn"
                                                   placeholder="Contoh: 978-602-1234-56-7"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-calendar-alt text-primary mr-1"></i>
                                                Tahun Terbit <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" class="form-control form-control-modern"
                                                   name="tahun_terbit"
                                                   placeholder="Contoh: 2024"
                                                   min="1900" max="{{ date('Y') }}"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                {{-- Stok, Dipinjam, Dibooking --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-cubes text-success mr-1"></i>
                                                Stok <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" class="form-control form-control-modern"
                                                   name="stok"
                                                   placeholder="0" min="0"
                                                   value="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-book-reader text-warning mr-1"></i>
                                                Dipinjam
                                            </label>
                                            <input type="number" class="form-control form-control-modern"
                                                   name="dipinjam"
                                                   placeholder="0" min="0"
                                                   value="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-receipt text-info mr-1"></i>
                                                Dibooking
                                            </label>
                                            <input type="number" class="form-control form-control-modern"
                                                   name="dibooking"
                                                   placeholder="0" min="0"
                                                   value="0" required>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- Right Column: Cover --}}
                            <div class="col-lg-4">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-image text-primary mr-1"></i>
                                        Cover Buku
                                    </label>
                                    <div class="cover-upload-wrapper">
                                        <img class="img-preview" id="imgPreview"
                                             src="{{ asset('storage/cover-buku/book-default-cover.jpg') }}"
                                             alt="Preview"
                                             style="width: 100%; height: 300px; object-fit: cover; border-radius: 12px; border: 2px dashed #dee2e6;">
                                    </div>
                                    <div class="custom-file mt-2">
                                        <input type="file" class="custom-file-input" id="image" name="image"
                                               accept="image/png,image/jpg,image/jpeg" onchange="previewImage()">
                                        <label class="custom-file-label" for="image">Pilih file...</label>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle"></i>
                                        Format: JPG, JPEG, PNG. Max: 1 MB.
                                    </small>
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="card-footer-modern">
                        <a href="{{ route('admin.master.buku.index') }}" class="btn btn-modern btn-light-modern">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-modern btn-primary-modern">
                            <i class="fas fa-save"></i> Simpan Buku
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        bsCustomFileInput.init();
        $('.select2').select2({ theme: 'bootstrap4' });
    });

    function previewImage() {
        const image = document.querySelector('#image');
        const imgPreview = document.querySelector('#imgPreview');
        const file = image.files[0];
        const reader = new FileReader();
        reader.onload = function(e) { imgPreview.src = e.target.result; }
        if (file) reader.readAsDataURL(file);
    }
</script>
@endpush