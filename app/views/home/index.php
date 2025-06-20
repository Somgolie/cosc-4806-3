<?php require_once 'app/views/templates/header.php' ?>
<div class="container">
  <div class="page-header bg-primary text-white p-4 rounded shadow-sm mb-4">
    <h1 class="display-4">Hey <?= htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>!</h1>
    <p class="lead"><?= date("F jS, Y"); ?></p>
  </div>

  <div class="mb-3">
    <a href="/logout" class="btn btn-danger btn-lg">Logout</a>
  </div>
</div>

    <?php require_once 'app/views/templates/footer.php' ?>
