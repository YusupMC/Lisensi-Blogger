<?php
session_start();
include 'dashboard/config.php';

if (isset($_POST['login'])) {
    if ($_POST['user'] === $admin_user && $_POST['pass'] === $admin_pass) {
        $_SESSION['login'] = true;
    } else {
        $error = "Login gagal";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['login'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Lisensi Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-5 col-lg-6 col-md-8">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-5">
            <div class="text-center">
              <h1 class="h4 text-gray-900 mb-4">Login Admin</h1>
              <?php if (isset($error)) echo "<div class='alert alert-danger'>" . $error . "</div>"; ?>
            </div>
            <form method="post">
              <div class="form-group">
                <input type="text" name="user" class="form-control form-control-user" placeholder="Username" required>
              </div>
              <div class="form-group">
                <input type="password" name="pass" class="form-control form-control-user" placeholder="Password" required>
              </div>
              <button type="submit" name="login" class="btn btn-primary btn-user btn-block">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    exit;
}

$licenses = json_decode(file_get_contents('data/license.json'), true) ?? [];

// Update status otomatis jika expired
$today = date('Y-m-d');
$updated = false;
foreach ($licenses as &$lic) {
    if ($lic['expired_at'] < $today && $lic['status'] !== 'expired') {
        $lic['status'] = 'expired';
        $updated = true;
    }
}
unset($lic);
if ($updated) {
    file_put_contents('data/license.json', json_encode($licenses, JSON_PRETTY_PRINT));
}

function getLicenseById($id, $licenses) {
    foreach ($licenses as $item) {
        if ($item['id'] === $id) return $item;
    }
    return null;
}
$current_license = null;
if (isset($_GET['id'])) {
    $current_license = getLicenseById($_GET['id'], $licenses);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Lisensi</title>
  <link href="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body id="page-top">
  <div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-key"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Lisensi Admin</div>
      </a>
      <hr class="sidebar-divider">
      <li class="nav-item active">
        <a class="nav-link" href="#">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
          <span class="ml-auto">Welcome, Admin | <a href="?logout=1">Logout</a></span>
        </nav>

        <div class="container-fluid">
          <h1 class="h3 mb-4 text-gray-800">Manajemen Lisensi</h1>

          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span><?= $current_license ? 'Edit Lisensi' : 'Tambah Lisensi' ?></span>
            </div>
            <div class="card-body">
              <form method="post" action="dashboard/save.php">
                <input type="hidden" name="edit" value="<?= $current_license ? '1' : '0' ?>">
                <div class="form-group">
                  <input type="text" name="id" class="form-control" placeholder="ID Lisensi (unik)" value="<?= $current_license['id'] ?? '' ?>" <?= $current_license ? 'readonly' : 'required' ?> autocomplete="off">
                </div>
                <div class="form-group">
                  <input type="text" name="domain" class="form-control" placeholder="Domain (blogspot.com)" value="<?= $current_license['domain'] ?? '' ?>" required autocomplete="off">
                </div>
                <div class="form-group">
                  <select name="status" class="form-control">
                    <option value="active" <?= isset($current_license) && $current_license['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="expired" <?= isset($current_license) && $current_license['status'] === 'expired' ? 'selected' : '' ?>>Expired</option>
                  </select>
                </div>
                <div class="form-group">
                  <input type="date" name="expired_at" class="form-control" value="<?= $current_license['expired_at'] ?? '' ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
              </form>
            </div>
          </div>

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Daftar Lisensi</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Domain</th>
                      <th>Status</th>
                      <th>Expired</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($licenses as $lisensi): ?>
                    <tr>
                      <td><?= $lisensi['id'] ?></td>
                      <td><?= $lisensi['domain'] ?></td>
                      <td><span class="badge badge-<?= $lisensi['status'] === 'active' ? 'success' : 'danger' ?>"> <?= ucfirst($lisensi['status']) ?></span></td>
                      <td><?= $lisensi['expired_at'] ?></td>
                      <td>
                        <a href="?id=<?= $lisensi['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                        <a href="dashboard/delete.php?id=<?= $lisensi['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus lisensi ini?')"><i class="fas fa-trash"></i> Hapus</a>
                        <form action="dashboard/perpanjang.php" method="POST" style="display:inline-block;margin-left:4px;">
                          <input type="hidden" name="id" value="<?= $lisensi['id'] ?>">
                          <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Perpanjang lisensi 1 tahun?')">🔁</button>
                        </form>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/js/sb-admin-2.min.js"></script>
</body>
</html>
