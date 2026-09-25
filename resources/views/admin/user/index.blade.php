@extends('admin.layout.main')

@section('title', 'Master User')

@section('content')
<div class="container-fluid">
    {{-- Header Action --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-sm" style="cursor: pointer;" onclick="tampilUserModal()">
                <span class="info-box-icon bg-gradient-primary">
                    <i class="fas fa-user-plus"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">Tambah User</span>
                    <span class="info-box-number text-muted small">Klik untuk menambah</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel User --}}
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>Daftar User
                    </h3>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-hover table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th width="4%">#</th>
                                <th>Nama User</th>
                                <th>Alamat</th>
                                <th>Email</th>
                                <th width="8%">Status</th>
                                <th width="10%">Role</th>
                                <th width="8%">Avatar</th>
                                <th width="18%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $item)
                            @php
                                $avatar = \App\Helpers\AvatarHelper::generate($item->nama);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <i class="fas fa-user-circle text-muted mr-1"></i>
                                    <span class="nama-user">{{ $item->nama }}</span>
                                </td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-muted mr-1"></i>
                                    {{ $item->alamat }}
                                </td>
                                <td>
                                    <i class="fas fa-envelope text-muted mr-1"></i>
                                    {{ $item->email }}
                                </td>
                                <td class="text-center">
                                    @if($item->is_active == 1)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle"></i> Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->role_id == 1)
                                        <span class="badge badge-primary">
                                            <i class="fas fa-user-shield"></i> Admin
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-user"></i> Member
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- Avatar Huruf --}}
                                    <div class="avatar-initial"
                                         style="width: 45px; height: 45px; background-color: {{ $avatar['color'] }}; font-size: 16px;">
                                        {{ $avatar['initial'] }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info"
                                                onclick="tampilUserModal('{{ $item->id }}', this)"
                                                data-toggle="tooltip" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning"
                                                onclick="tampilUserModal('{{ $item->id }}', this)"
                                                data-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.master.user.resetPassword', $item->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="btn btn-sm btn-secondary reset-password"
                                                    data-toggle="tooltip" title="Reset Password">
                                                <i class="fas fa-key"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.master.user.destroy', $item->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger hapus-data"
                                                    data-toggle="tooltip" title="Hapus">
                                                <i class="fas fa-trash"></i>
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
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="userModal" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h4 class="modal-title" id="userModalLabel">
                    <i class="fas fa-user-plus"></i> Tambah User Baru
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.master.user.store') }}" method="POST"
                  id="userForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label><i class="fas fa-user"></i> Nama User</label>
                                <input type="hidden" name="id" id="id">
                                <input type="text" class="form-control" id="nama" name="nama"
                                       placeholder="Isikan nama user" required>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-map-marker-alt"></i> Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat"
                                          rows="2" placeholder="Isikan alamat" required></textarea>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="Isikan email" required readonly>
                            </div>
                            <div class="form-group d-none" id="status">
                                <label><i class="fas fa-toggle-on"></i> Status</label>
                                <div class="icheck-primary d-inline pr-4">
                                    <input type="radio" id="aktif" name="status" value="1">
                                    <label for="aktif">Aktif</label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="tidak_aktif" name="status" value="0">
                                    <label for="tidak_aktif">Tidak Aktif</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-user-tag"></i> Role</label>
                                <div class="icheck-primary d-inline pr-4">
                                    <input type="radio" id="role_admin" name="role_id" value="1">
                                    <label for="role_admin">Administrator</label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="role_anggota" name="role_id" value="2">
                                    <label for="role_anggota">Anggota</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="form-group">
                                <label><i class="fas fa-user-circle"></i> Avatar</label>
                                <div class="avatar-preview mx-auto mb-2">
                                    <div class="avatar-initial" id="avatarPreview"
                                         style="width: 120px; height: 120px; background-color: #007bff; font-size: 48px;">
                                        ?
                                    </div>
                                </div>
                                <small class="text-muted d-block">
                                    Avatar otomatis dari inisial nama
                                </small>
                            </div>
                        </div>
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
    $(document).ready(function() {
        $("#example1").DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                zeroRecords: "Data tidak ditemukan",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });

    $('[data-toggle="tooltip"]').tooltip();

    // Generate avatar warna dari nama
    function getAvatarColor(name) {
        const colors = [
            '#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8',
            '#6f42c1', '#fd7e14', '#e83e8c', '#20c997', '#6610f2'
        ];
        let hash = 0;
        for (let i = 0; i < name.length; i++) {
            hash = name.charCodeAt(i) + ((hash << 5) - hash);
        }
        return colors[Math.abs(hash) % colors.length];
    }

    function getInitial(name) {
        if (!name) return '?';
        const words = name.trim().split(' ');
        let initial = words[0].charAt(0).toUpperCase();
        if (words.length > 1) initial += words[1].charAt(0).toUpperCase();
        return initial;
    }

    // Live update avatar saat nama diinput
    $('#nama').on('input', function() {
        const name = $(this).val() || 'User';
        const initial = getInitial(name);
        const color = getAvatarColor(name);
        $('#avatarPreview').text(initial).css('background-color', color);
    });

    function tampilUserModal(id = null, element = null) {
        let $userModal = $('#userModal');
        let $form = $('#userForm');
        let $formMethod = $('#formMethod');
        let $status = $('#status');

        $userModal.modal('show');

        function setFormData(data, isReadOnly) {
            $('#id').val(data.id || '');
            $('#nama').val(data.nama || '').prop('readonly', isReadOnly);
            $('#alamat').val(data.alamat || '').prop('readonly', isReadOnly);
            $('#email').val(data.email || '').prop('readonly', true);
            $('input[name=status]').prop('disabled', isReadOnly);
            $('input[name=role_id]').prop('disabled', isReadOnly);
            data.is_active == '1' ? $('#aktif').prop('checked', true) : $('#tidak_aktif').prop('checked', true);
            data.role_id == '1' ? $('#role_admin').prop('checked', true) : $('#role_anggota').prop('checked', true);

            // Update avatar
            if (data.nama) {
                $('#avatarPreview')
                    .text(getInitial(data.nama))
                    .css('background-color', getAvatarColor(data.nama));
            } else {
                $('#avatarPreview').text('?').css('background-color', '#007bff');
            }
        }

        if (id == null) {
            $('#userModalLabel').html('<i class="fas fa-user-plus"></i> Tambah User Baru');
            setFormData({}, false);
            $('.simpan-ubah').html('<i class="fas fa-save"></i> Simpan').show();
            $form.attr('action', '{{ route("admin.master.user.store") }}');
            $formMethod.val('POST');
            $status.addClass('d-none');
            $('#email').prop('readonly', false);
            $('input[name=status]').prop('disabled', false);
            $('input[name=role_id]').prop('disabled', false);
            $('#nama').val('').trigger('input');
        } else {
            let title = $(element).attr('title');
            let isReadOnly = title == 'Detail';
            $('#userModalLabel').html(isReadOnly
                ? '<i class="fas fa-eye"></i> Detail User'
                : '<i class="fas fa-edit"></i> Edit User');
            $status.removeClass('d-none');

            $.ajax({
                url: '{{ url("") }}/admin/master/user/' + id,
                dataType: 'json',
                type: 'GET',
                success: function(data) {
                    setFormData(data, isReadOnly);
                    if (isReadOnly) {
                        $('.simpan-ubah').hide();
                    } else {
                        $('.simpan-ubah').html('<i class="fas fa-save"></i> Ubah').show();
                        $form.attr('action', '{{ url("") }}/admin/master/user/' + id);
                        $formMethod.val('PUT');
                    }
                }
            });
        }
    }

    $(document).on('click', '.hapus-data', function() {
        var form = $(this).closest("form");
        var nama_user = $(this).closest("tr").find('.nama-user').html();
        if (confirm('Yakin ingin menghapus user ' + nama_user + '?')) {
            form.submit();
        }
    });

    $(document).on('click', '.reset-password', function() {
        var form = $(this).closest("form");
        var nama_user = $(this).closest("tr").find('.nama-user').html();
        if (confirm('Yakin ingin reset password user ' + nama_user + '?')) {
            form.submit();
        }
    });
</script>
@endpush