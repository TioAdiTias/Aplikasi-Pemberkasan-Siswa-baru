<?php
include 'config.php';
if(!isset($_SESSION['admin'])) exit;
$data=[];
$res=$conn->query("SELECT * FROM siswa ORDER BY created_at DESC");
while($r=$res->fetch_assoc()){
  $data[]=[
    'id'=>$r['id'],
    'no_pendaftar'=>$r['no_pendaftar'],
    'nama'=>$r['nama'],
    'jurusan'=>$r['jurusan'],
    'no_hp'=>$r['no_hp'],
    'asal_sekolah'=>$r['asal_sekolah'],
    'foto'=>"<img src='uploads/thumbs/{$r['foto']}' width='50'>",
    'aksi'=>"
      <a href='form.php?id={$r['id']}'>Edit</a> |
      <a href='#' class='del' data-id='{$r['id']}'>Hapus</a>"
  ];
}
echo json_encode(['data'=>$data]);
