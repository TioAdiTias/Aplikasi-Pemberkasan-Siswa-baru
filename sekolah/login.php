<?php
include 'config.php';   // pastikan koneksi & session_start() ada di sini

if(isset($_SESSION['admin'])) {
  header('Location: form.php');
  exit;
}

$error = '';
if($_SERVER['REQUEST_METHOD']==='POST') {
  $u = $conn->real_escape_string($_POST['username'] ?? '');
  $p = $_POST['password'] ?? '';

  // ambil data admin
  $stm = $conn->prepare("SELECT id,password FROM admin WHERE username=? LIMIT 1");
  $stm->bind_param('s',$u);
  $stm->execute();
  $res = $stm->get_result();

  if($res->num_rows===1) {
    $row = $res->fetch_assoc();
    // cek plain-text
    if($p === $row['password']) {
      $_SESSION['admin'] = $row['id'];
      header('Location: form.php');
      exit;
    } else {
      $error = 'Password salah.';
    }
  } else {
    $error = 'User tidak ditemukan.';
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Admin</title>
<style>
  body{font-family:Arial;background:#f4f4f4;margin:0;padding:0}
  .box{width:300px;margin:100px auto;padding:20px;background:#fff;box-shadow:0 0 5px rgba(0,0,0,.1)}
  input,button{width:100%;padding:8px;margin:8px 0;box-sizing:border-box}
  button{background:#007BFF;color:#fff;border:none;cursor:pointer}
  .error{color:red;font-size:.9em;text-align:center}
</style>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
  <div class="box">
    <h2>Login Admin</h2>
    <?php if($error): ?>
      <p class="error"><?=htmlspecialchars($error)?></p>
    <?php endif; ?>
    <form method="post" action="">
      <input name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
