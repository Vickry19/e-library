@extends('admin.layout.main')

@section('title', 'Master Kategori')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-3">
        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
            <div class="info-box shadow-sm" style="cursor: pointer;" data-toggle="modal" data-target="#categoryModal">
                <span class="info-box-icon bg-gradient-primary">
                    <i class="fas fa-plus"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">Tambah Kategori</span>
                    <span class="info-box-number text-muted small">Klik untuk menambah</span>
                </div>
            </div>
        </div>
    </div>

    {{-- List Kategori --}}
    <div class="row">
        @forelse ($kategori as $index => $item)
            @php
                $colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
                $color = $colors[$index % count($colors)];
            @endphp
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="small-box bg-gradient-{{ $color }} shadow-sm"
                     style="cursor: pointer;"
                     data-toggle="modal" data-target="#categoryModal"
                     data-kategori="{{ $item->nama_kategori }}"
                     data-id="{{ $item->id }}">
                    <div class="inner">
                        <h3>{{ $loop->iteration }}</h3>
                        <p class="text-truncate">{{ $item->nama_kategori }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        {{ $item->buku_count }} Buku
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Belum ada kategori. Klik "Tambah Kategori" untuk memulai.
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- Modal Kategori --}}
<div class="modal fade" id="categoryModal" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h4 class="modal-title" id="categoryModalLabel">
                    <i class="fas fa-plus-circle"></i> Tambah Kategori Baru
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.master.kategori.store') }}" method="POST" id="kategoriForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Nama Kategori</label>
                        <input type="text" class="form-control form-control-lg"
                               id="nama_kategori" name="nama_kategori"
                               placeholder="Contoh: Sains, Teknologi, Sejarah" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <button type="submit" class="btn btn-primary simpan-ubah">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('.small-box').click(function() {
        var attr = $(this).attr('data-kategori');
        var form = $('#kategoriForm');
        var methodInput = form.find('input[name="_method"]');
        var idInput = form.find('input[name="id"]');
        var formHapus = form.find('#hapusKategoriForm');

        if (typeof attr !== 'undefined' && attr !== false) {
            var nama_kategori = $(this).attr('data-kategori');
            var id_kategori = $(this).attr('data-id');
            $('#categoryModalLabel').html('<i class="fas fa-edit"></i> Ubah Kategori');
            $('.simpan-ubah').html('<i class="fas fa-save"></i> Ubah');
            $('#nama_kategori').val(nama_kategori);
            form.attr('action', '{{ url("admin/master/kategori") }}');

            if (methodInput.length === 0) {
                form.append('<input type="hidden" name="_method" value="PUT">');
                form.append('<input type="hidden" name="id" value="' + id_kategori + '">');
                $('<form action="{{ url("admin/master/kategori") }}/' + id_kategori + '" method="POST" id="hapusKategoriForm">@csrf<input type="hidden" name="_method" value="DELETE" class="method_delete"><button type="button" class="btn btn-danger hapus-data-kategori"><i class="fas fa-trash"></i> Hapus</button></form>').insertAfter(".simpan-ubah");
            } else {
                methodInput.val('PUT');
                $('.method_delete').val('DELETE');
                if (idInput.length === 0) {
                    form.append('<input type="hidden" name="id" value="' + id_kategori + '">');
                } else {
                    idInput.val(id_kategori);
                }
            }
        } else {
            $('#categoryModalLabel').html('<i class="fas fa-plus-circle"></i> Tambah Kategori Baru');
            $('.simpan-ubah').html('<i class="fas fa-save"></i> Simpan');
            $('#nama_kategori').val('');
            form.attr('action', '{{ route("admin.master.kategori.store") }}');

            if (methodInput.length > 0) methodInput.remove();
            if (idInput.length > 0) idInput.remove();
            if (formHapus.length > 0) formHapus.remove();
        }
    });

    $(document).on('click', '.hapus-data-kategori', function() {
        var form = $(this).closest("form");
        if (confirm('Yakin ingin menghapus kategori ini?')) {
            form.submit();
        }
    });
</script>
@endpush