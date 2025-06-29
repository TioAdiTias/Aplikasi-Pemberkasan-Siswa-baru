<?php
include 'config.php';
if(!isset($_SESSION['admin'])) header('Location: login.php');

// Ambil data jika edit
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = ['no_pendaftar'=>'','nama'=>'','jurusan'=>'','no_hp'=>'','alamat'=>'','asal_sekolah'=>'','foto'=>''];
if($id){
  $stm = $conn->prepare("SELECT * FROM siswa WHERE id=?");
  $stm->bind_param('i',$id);
  $stm->execute();
  $data = $stm->get_result()->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $id?'Edit':'Tambah' ?> Data Siswa</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1><?= $id?'Edit':'Pendaftaran'?> Siswa</h1>
  <form id="frm" enctype="multipart/form-data">
    <input type="hidden" name="act" id="form-act" value="<?= $id ? 'update' : 'save' ?>">
    <input type="hidden" name="id" value="<?= $id ?>">
    <label>No. Pendaftar (001–999)</label>
    <input type="number" name="no_pendaftar" min="1" max="999"
      value="<?= intval($data['no_pendaftar']) ?>" required>

    <label>Nama</label>
    <input type="text" name="nama"
      value="<?= htmlspecialchars($data['nama']) ?>" required>

    <label>Jurusan</label>
    <select name="jurusan" required>
      <option value="">Pilih…</option>
      <?php foreach(['TKR','TSM','TKJ'] as $j): ?>
        <option value="<?= $j?>" <?= $data['jurusan']==$j?'selected':'' ?>>
          <?= $j?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>No. HP</label>
    <input type="text" name="no_hp"
      value="<?= htmlspecialchars($data['no_hp']) ?>" required>

    <label>Alamat</label>
    <textarea name="alamat" required><?= htmlspecialchars($data['alamat']) ?></textarea>

    <label>Asal Sekolah</label>
    <input type="text" name="asal_sekolah"
      value="<?= htmlspecialchars($data['asal_sekolah']) ?>" required>

    <label>Foto <?= $id?'(kosongkan jika tidak diubah)':''?></label>
    <input type="file" name="foto" <?= $id?'':'required'?> accept="image/*">
    <?php if($data['foto']): ?>
      <p>Foto saat ini:<br>
        <img src="uploads/thumbs/<?= $data['foto']?>" width="80">
      </p>
    <?php endif; ?>

    <button type="submit"><?= $id?'Update':'Simpan'?></button>
    <a href="table.php" style="margin-left:10px">Lihat Data »</a>
  </form>
  <div id="resp" style="margin-top:15px;color:green;"></div>
</div>

<script>
$(function(){
  $('#frm').on('submit', function(e){
    e.preventDefault();
    // Ambil satu-satu
    let id   = $('input[name=id]').val();
    let no   = $('input[name=no_pendaftar]').val().padStart(3,'0');
    let nama = $('input[name=nama]').val().trim();
    let jur  = $('select[name=jurusan]').val();
    let hp   = $('input[name=no_hp]').val().trim();
    let alm  = $('textarea[name=alamat]').val().trim();
    let asl  = $('input[name=asal_sekolah]').val().trim();
    let foto = $('input[name=foto]')[0].files[0];

    if(!no||!nama||!jur||!hp||!alm||!asl){
      alert('Semua field harus diisi!');
      return;
    }

    let fd = new FormData();
    fd.append('id', id);
    fd.append('no_pendaftar', no);
    fd.append('nama', nama);
    fd.append('jurusan', jur);
    fd.append('no_hp', hp);
    fd.append('alamat', alm);
    fd.append('asal_sekolah', asl);
    if(foto) fd.append('foto', foto);
    fd.set('act', $('#form-act').val());


    $.ajax({
      url: 'process.php',
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      dataType: 'json',
      beforeSend(){
        $('#resp').css('color','green').text('Mengirim…');
      },
      success(res){
        if(res.status==='ok'){
          $('#resp').css('color','green').text(res.msg);
          if(!id) $('#frm')[0].reset();
          if(window.table) window.table.ajax.reload();
        } else {
          $('#resp').css('color','red').text(res.msg);
        }
      },
      error(xhr,st,err){
        $('#resp').css('color','red')
                  .text('AJAX error: '+(xhr.responseText||err));
      }
    });
  });
});
</script>
</body>
</html>
