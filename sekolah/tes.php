<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Form Test</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<form id="frm" action="process.php" method="post" enctype="multipart/form-data">
  <input name="no_pendaftar" value="001">
  <input name="nama" value="Test">
  <input name="jurusan" value="TKR">
  <input name="no_hp" value="0812345">
  <textarea name="alamat">Alamat</textarea>
  <input name="asal_sekolah" value="SMK">
  <input type="hidden" name="action" value="save">
  <input type="file" name="foto" accept="image/*">
  <button type="submit">Simpan</button>
</form>


  <script>
$('#frm').on('submit',function(e){
  e.preventDefault();
  let fd = new FormData(this);
  fd.append('action','save');

  // Debug: list semua key & value
  for (let pair of fd.entries()) {
    console.log(pair[0]+': '+pair[1]);
  }

  $.ajax({ … });
});

  </script>
</body>
</html>
