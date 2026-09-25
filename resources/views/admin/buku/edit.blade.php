@extends('admin.layout.main')

@section('title', 'Edit Buku')

@section('content')
<div class="container-fluid">

    {{-- ==================== PAGE HEADER ==================== --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-header-buku">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="header-icon-wrapper">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="mb-0 font-weight-bold">Edit Buku</h3>
                            <small class="opacity-75">{{ $buku->judul_buku }}</small>
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
                        <i class="fas fa-edit text-warning mr-2"></i>
                        <h5 class="mb-0 font-weight-bold">Form Edit Buku</h5>
                    </div>
                </div>

                <form action="{{ route('admin.master.buku.update', $buku->id) }}" method="POST"
                      id="bukuForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                           value="{{ $buku->judul_buku }}"
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
                                        @foreach ($kategori as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->id == $buku->id_kategori ? 'selected' : '' }}>
                                                {{ $item->nama_kategori }}
                                            </option>
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
                                                   value="{{ $buku->pengarang }}" required>
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
                                                   value="{{ $buku->penerbit }}" required>
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
                                                   value="{{ $buku->isbn }}" required>
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
                                                   value="{{ $buku->tahun_terbit }}"
                                                   min="1900" max="{{ date('Y') }}" required>
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
                                                   value="{{ $buku->stok }}" min="0" required>
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
                                                   value="{{ $buku->dipinjam }}" min="0" required>
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
                                                   value="{{ $buku->dibooking }}" min="0" required>
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

                                    <input type="hidden" name="oldImage" value="{{ $buku->image }}">

                                    <div class="cover-upload-wrapper">
                                        <img class="img-preview" id="imgPreview"
                                             src="{{ \App\Helpers\ImageHelper::url($buku->image, 'cover-buku') }}"
                                             alt="Preview"
                                             style="width: 100%; height: 300px; object-fit: cover; border-radius: 12px; border: 2px dashed #dee2e6;">
                                    </div>

                                    <div class="custom-file mt-2">
                                        <input type="file" class="custom-file-input" id="image" name="image"
                                               accept="image/png,image/jpg,image/jpeg" onchange="previewImage()">
                                        <label class="custom-file-label" for="image">Pilih file baru...</label>
                                    </div>

                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle"></i>
                                        Biarkan kosong jika tidak ingin mengubah cover.
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
                        <button type="submit" class="btn btn-modern btn-warning-modern">
                            <i class="fas fa-save"></i> Update Buku
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