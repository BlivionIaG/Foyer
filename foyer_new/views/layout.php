<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
</head>
<body>
  <header>
    <a href='./'>Home</a>
    <a href='?controller=home'>Home2</a>
    <a href='?controller=pages&action=product'>Product</a>
    <a href='?controller=pages&action=existepas'>Error</a>
  </header>

  <?php require_once('routes.php'); ?>

  <footer>
    Copyright
  </footer>
</body>
</html>