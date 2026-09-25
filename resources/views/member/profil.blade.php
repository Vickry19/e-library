@extends('member.layout.main')

@section('title', 'Profil Saya')

@section('content')
<div class="container pt-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Profil Saya</h3>
                </div>
                <form role="form" action="{{ route('member.profil') }}" method="POST" enctype="multipart/form-data" id="profilForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                   name="nama" id="nama" value="{{ Auth::user()->nama }}"
                                   data-initial-value="{{ Auth::user()->nama }}" readonly>
                            @error('nama')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror"
                                   name="alamat" id="alamat" value="{{ Auth::user()->alamat }}"
                                   data-initial-value="{{ Auth::user()->alamat }}" readonly>
                            @error('alamat')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Gambar</label>
                            <input type="hidden" name="oldImage" value="{{ Auth::user()->image }}">
                            <img class="img-preview img-fluid mb-3 col-sm-5 d-block" src="{{ asset('storage/' . Auth::user()->image) }}">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image" onchange="previewImage()" disabled>
                                <label class="custom-file-label" for="image">Pilih file...</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-secondary" id="toggleButton">Klik untuk Ubah Profil</button>
                        <button type="button" class="btn btn-danger d-none" id="batal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4 d-flex justify-content-center align-items-center">
            <img class="img-preview img-fluid d-block" src="{{ asset('storage/' . Auth::user()->image) }}">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleEditMode(isEdit, batal = false) {
        $('#toggleButton')
            .removeClass(isEdit ? 'btn-secondary' : 'btn-primary')
            .addClass(isEdit ? 'btn-primary' : 'btn-secondary')
            .text(isEdit ? 'Simpan Perubahan' : 'Klik untuk Ubah Profil')
            .attr('type', isEdit ? 'submit' : 'button');
        $('#batal').toggleClass('d-none', !isEdit);
        $('#nama, #alamat').prop('readonly', !isEdit);
        $('#image').prop('disabled', !isEdit);
        if (isEdit) $('#nama').focus();
        if (batal) {
            $('#nama').val($('#nama').data('initial-value'));
            $('#alamat').val($('#alamat').data('initial-value'));
            $('.img-preview').attr('src', '{{ asset("storage/" . Auth::user()->image) }}');
        }
    }

    $('#toggleButton').click(function(e) {
        e.preventDefault();
        if ($(this).hasClass('btn-secondary')) {
            toggleEditMode(true);
        } else {
            toggleEditMode(false);
            $('#profilForm').submit();
        }
    });

    $('#batal').click(function() { toggleEditMode(false, true); });

    function previewImage() {
        const image = document.querySelector('#image');
        const imgPreview = document.querySelector('.img-preview');
        const file = image.files[0];
        const reader = new FileReader();
        reader.onload = function(e) { imgPreview.src = e.target.result; }
        if (file) reader.readAsDataURL(file);
    }
</script>
@endpush