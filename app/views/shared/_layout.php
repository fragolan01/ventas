<?php
if (!defined('APP_INIT')) { header('HTTP/1.0 403 Forbidden'); exit; }
?><!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo isset($title) ? h($title) : 'Mi sitio'; ?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!-- enlaces CSS/JS -->
</head>
<body>
  <?php include APP_ROOT . '/views/_header.php'; ?>

  <div class="wrap">
    <aside class="sidebar">
      <?php include APP_ROOT . '/views/_sidebar.php'; ?>
    </aside>

    <main class="main-content">
      <?php echo $content; // contenido de la vista ?>
    </main>
  </div>

  <?php include APP_ROOT . '/views/_footer.php'; ?>
</body>
</html>
