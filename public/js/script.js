$(function () {
    $('.tambahSantri').on('click', function () {
        $('#formSantri').attr('action', BASEURL + '/santri/create');
        $('.modal-title').html('Tambah Santri');
        $('.modal-footer button[type=submit]').html('Tambah Data');
        $('.modal-footer button[type=submit]').attr('name', 'tambah');

        $('#id_santri').val('');
        $('#fotoLama').val('');
        $('#no_induk').val('');
        $('#nama_santri').val('');
        $('input[name=jk]').prop('checked', false);
        $('#kamar').val('');
        $('#sekolah').val('');
        $('#madrasah').val('');
        $('#tempat_lahir').val('');
        $('#tanggal_lahir').val('');
        $('#previewFoto').html('');
        $('#foto').val('');
    });
    $('.tambahKamar').on('click', function () {
        $('#formKamar').attr('action', BASEURL + '/kamar/create');
        $('.modal-title').html('Tambah Data Kamar');
        $('.modal-footer button[type=submit]').html('Tambah Data');
        $('.modal-footer button[type=submit]').attr('name', 'tambah');

        $('#id_kamar').val('');
        $('#nama_kamar').val('');
        $('#kode').val('');
        $('input[name=jk]').prop('checked', false);
    });

    $('.tambahSekolah').on('click', function () {
        $('#formSekolah').attr('action', BASEURL + '/sekolah/create');
        $('.modal-title').html('Tambah Data Sekolah');
        $('.modal-footer button[type=submit]').html('Tambah Data');
        $('.modal-footer button[type=submit]').attr('name', 'tambah');

        $('#id_sekolah').val('');
        $('#nama_sekolah').val('');
        $('#kode').val('');
    });

    $('.tambahMadrasah').on('click', function () {
        $('#formMadrasah').attr('action', BASEURL + '/madrasah/create');
        $('.modal-title').html('Tambah Data Madrasah');
        $('.modal-footer button[type=submit]').html('Tambah Data');
        $('.modal-footer button[type=submit]').attr('name', 'tambah');

        $('#id_madrasah').val('');
        $('#nama_madrasah').val('');
        $('#kode').val('');
    });

    $('.tambahUser').on('click', function () {
        $('#formUser').attr('action', BASEURL + '/user/create');
        $('.modal-title').html('Tambah Data User');
        $('.modal-footer button[type=submit]').html('Tambah Data');
        $('.modal-footer button[type=submit]').attr('name', 'tambah');

        $('#id_user').val('');
        $('#username').val('');
        $('#passwordNow').html('');
        $('#password').val('');
        $('#confirm').val('');
    });
});

function ubahSantri(data) {
    $('#formSantri').attr('action', BASEURL + '/santri/update');
    $('.modal-title').html('Ubah Data Santri');
    $('.modal-footer button[type=submit]').html('Ubah Data');
    $('.modal-footer button[type=submit]').attr('name', 'ubah');

    $('#id_santri').val(data.id_santri);
    $('#no_induk').val(data.no_induk);
    $('#fotoLama').val(data.foto);
    $('#nama_santri').val(data.nama_santri);
    $('#jk').prop('checked', false);
    $('input[name=jk][value="' + data.jk + '"]').prop('checked', true);
    $('#kamar').val(data.kamar).trigger('change');
    $('#sekolah').val(data.sekolah).trigger('change');
    $('#madrasah').val(data.madrasah).trigger('change');
    $('#tempat_lahir').val(data.tempat_lahir);
    $('#tanggal_lahir').val(data.tanggal_lahir);
    $('#previewFoto').html('<p class="small mb-1">Foto Saat Ini:</p><img src="' + BASEURL + '/img/foto/' + data.foto + '" class="img-thumbnail" width="100">');
    $('#foto').val('');
}

function ubahKamar(data) {
    $('#formKamar').attr('action', BASEURL + '/kamar/update');
    $('.modal-title').html('Ubah Data Kamar');
    $('.modal-footer button[type=submit]').html('Ubah Data');
    $('.modal-footer button[type=submit]').attr('name', 'ubah');

    $('#id_kamar').val(data.id_kamar);
    $('#nama_kamar').val(data.nama_kamar);
    $('#kode').val(data.kode);
    $('input[name=jk]').prop('checked', false);
    $('input[name=jk][value="' + data.jk + '"]').prop('checked', true);
}

function ubahMadrasah(data) {
    $('#formMadrasah').attr('action', BASEURL + '/madrasah/update');
    $('.modal-title').html('Ubah Data Madrasah');
    $('.modal-footer button[type=submit]').html('Ubah Data');
    $('.modal-footer button[type=submit]').attr('name', 'ubah');

    $('#id_madrasah').val(data.id_madrasah);
    $('#nama_madrasah').val(data.nama_madrasah);
    $('#kode').val(data.kode);
}

function ubahSekolah(data) {
    $('#formSekolah').attr('action', BASEURL + '/sekolah/update');
    $('.modal-title').html('Ubah Data Sekolah');
    $('.modal-footer button[type=submit]').html('Ubah Data');
    $('.modal-footer button[type=submit]').attr('name', 'ubah');

    $('#id_sekolah').val(data.id_sekolah);
    $('#nama_sekolah').val(data.nama_sekolah);
    $('#kode').val(data.kode);
}

function ubahUser(data) {
    $('#formUser').attr('action', BASEURL + '/user/update');
    $('.modal-title').html('Ubah Data User');
    $('.modal-footer button[type=submit]').html('Ubah Data');
    $('.modal-footer button[type=submit]').attr('name', 'ubah');

    $('#id_user').val(data.id_user);
    $('#username').val(data.username);
    $('#passwordNow').html('');
    $('#password').val('');
    $('#confirm').val('');
}
