<?php
include 'config.php';
header('Content-Type:application/json');
if(!isset($_SESSION['admin'])){
  echo json_encode(['status'=>'err','msg'=>'Not authenticated']); exit;
}
$act = $_POST['act'] ?? '';
$id  = isset($_POST['id'])?(int)$_POST['id']:0;

// validasi & sanitasi
$no  = str_pad((int)$_POST['no_pendaftar'],3,'0',STR_PAD_LEFT);
$nama= $conn->real_escape_string($_POST['nama']);
$jur = $_POST['jurusan'];
$hp  = $conn->real_escape_string($_POST['no_hp']);
$al  = $conn->real_escape_string($_POST['alamat']);
$as  = $conn->real_escape_string($_POST['asal_sekolah']);
$fotoName = '';

// upload & validasi gambar
if(!empty($_FILES['foto']['name'])){
  $okExt=['jpg','jpeg','png']; 
  $ext=strtolower(pathinfo($_FILES['foto']['name'],PATHINFO_EXTENSION));
  if($_FILES['foto']['size']>2e6) exit(json_encode(['status'=>'err','msg'=>'Ukuran >2MB']));
  if(!in_array($ext,$okExt)) exit(json_encode(['status'=>'err','msg'=>'Tipe file salah']));
  $fotoName=uniqid().".{$ext}";
  $dest="uploads/{$fotoName}";
  if(!move_uploaded_file($_FILES['foto']['tmp_name'],$dest))
    exit(json_encode(['status'=>'err','msg'=>'Gagal upload']));
  // buat thumbnail 100×100
  list($w,$h)=getimagesize($dest);
  $ratio=100/$w; $nw=100; $nh=$h*$ratio;
  $thumb=imagecreatetruecolor($nw,$nh);
  $src = ($ext=='png')? imagecreatefrompng($dest): imagecreatefromjpeg($dest);
  imagecopyresampled($thumb,$src,0,0,0,0,$nw,$nh,$w,$h);
  if(!file_exists('uploads/thumbs')) mkdir('uploads/thumbs',0755,true);
  $out='uploads/thumbs/'.$fotoName;
  ($ext=='png')? imagepng($thumb,$out): imagejpeg($thumb,$out,80);
  imagedestroy($thumb); imagedestroy($src);
}

// query
if($act=='save'){
  $stm=$conn->prepare(
    "INSERT INTO siswa(no_pendaftar,nama,jurusan,no_hp,alamat,asal_sekolah,foto)
     VALUES(?,?,?,?,?,?,?)"
  );
  $stm->bind_param('sssssss',$no,$nama,$jur,$hp,$al,$as,$fotoName);
  $stm->execute();
  echo json_encode(['status'=>'ok','msg'=>'Data tersimpan']);
}
elseif($act=='update'){
  if($fotoName){
    $stm=$conn->prepare(
      "UPDATE siswa SET no_pendaftar=?,nama=?,jurusan=?,no_hp=?,
        alamat=?,asal_sekolah=?,foto=? WHERE id=?"
    );
    $stm->bind_param('sssssssi',$no,$nama,$jur,$hp,$al,$as,$fotoName,$id);
  } else {
    $stm=$conn->prepare(
      "UPDATE siswa SET no_pendaftar=?,nama=?,jurusan=?,no_hp=?,alamat=?,asal_sekolah=? 
       WHERE id=?"
    );
    $stm->bind_param('ssssssi',$no,$nama,$jur,$hp,$al,$as,$id);
  }
  $stm->execute();
  echo json_encode(['status'=>'ok','msg'=>'Data diperbarui']);
}
else echo json_encode(['status'=>'err','msg'=>'Aksi tidak dikenal']);
