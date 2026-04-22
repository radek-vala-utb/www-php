<?php
$errors = [];
$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($submitted) {
    if ($name === '') {
        $errors[] = 'Jmeno je povinne.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email nema spravny format.';
    }

    if ($message === '' || strlen($message) < 10) {
        $errors[] = 'Zprava musi mit alespon 10 znaku.';
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>06. GET a POST validace</title>
    <link rel="stylesheet" href="../lesson.css" />
  </head>
  <body>
    <main>
      <h1>06. GET a POST validace</h1>
      <p>Ukazka zpracovani formulare metodou <code>POST</code> a jednoduche validace.</p>

      <section class="card">
        <form action="" method="post">
          <div class="field">
            <label for="name">Jmeno</label>
            <input id="name" name="name" type="text" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" />
          </div>

          <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" />
          </div>

          <div class="field">
            <label for="message">Zprava</label>
            <textarea id="message" name="message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></textarea>
          </div>

          <button type="submit">Odeslat</button>
        </form>
      </section>

      <?php if ($submitted && $errors !== []): ?>
        <section class="card">
          <h2>Chyby ve formulari</h2>
          <ul class="error-list">
            <?php foreach ($errors as $error): ?>
              <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
          </ul>
        </section>
      <?php endif; ?>

      <?php if ($submitted && $errors === []): ?>
        <section class="card success-box">
          <h2>Validace prosla</h2>
          <p><strong>Jmeno:</strong> <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></p>
          <p><strong>Zprava:</strong> <?= nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')); ?></p>
        </section>
      <?php endif; ?>

      <section class="card">
        <h2>Poznamka k bezpecnosti</h2>
        <p class="muted">
          Pri vypisu uzivatelskych dat pouzivame <code>htmlspecialchars()</code>,
          aby se vstup neinterpretoval jako HTML.
        </p>
      </section>

      <a class="nav-link" href="../index.php">Zpet na prehled</a>
    </main>
  </body>
</html>
