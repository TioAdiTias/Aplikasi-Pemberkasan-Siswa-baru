<?php
include 'config.php';
if(!isset($_SESSION['admin'])) header('Location: login.php');
?>
<!DOCTYPE html><html><head>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" 
 href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<title>Data Pendaftaran</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>Data Pendaftaran Siswa</h1>
  <a href="form.php" style="margin-right:15px">[+] Tambah Baru</a>
  <a href="logout.php" style="color:red">Logout</a>
  <table id="tbl" class="display" style="width:100%;margin-top:20px">
    <thead>
      <tr>
        <th>No.</th><th>No.Pndf.</th><th>Nama</th><th>Jurusan</th>
        <th>HP</th><th>Asal Sekolah</th><th>Foto</th><th>Aksi</th>
      </tr>
    </thead>
  </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script 
 src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
$(function(){
  window.table = $('#tbl').DataTable({
    ajax:'fetch_data.php',
    columns:[
      {data:null,render:(_,__,row,i)=>i+1},
      {data:'no_pendaftar'},{data:'nama'},{data:'jurusan'},
      {data:'no_hp'},{data:'asal_sekolah'},{data:'foto'},{data:'aksi'}
    ]
  });
  $('#tbl').on('click','.del',function(e){
    e.preventDefault();
    if(!confirm('Yakin dihapus?')) return;
    let id=$(this).data('id');
    $.post('delete.php',{id},function(res){
      if(res.status==='ok') table.ajax.reload();
    },'json');
  });
});
</script>
</body></html>
