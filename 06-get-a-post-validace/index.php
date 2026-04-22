<?php
$errors = [];
$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($submitted) {
    if ($name === '') {
        $errors[] = 'Jméno je povinné.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'E-mail nemá správný formát.';
    }

    if ($message === '' || strlen($message) < 10) {
        $errors[] = 'Zpráva musí mít alespoň 10 znaků.';
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
      <p>Ukázka zpracování formuláře metodou <code>POST</code> a jednoduché validace.</p>

      <section class="card">
        <form action="" method="post">
          <div class="field">
            <label for="name">Jméno</label>
            <input id="name" name="name" type="text" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" />
          </div>

          <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" />
          </div>

          <div class="field">
            <label for="message">Zpráva</label>
            <textarea id="message" name="message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></textarea>
          </div>

          <button type="submit">Odeslat</button>
        </form>
      </section>

      <?php if ($submitted && $errors !== []): ?>
        <section class="card">
          <h2>Chyby ve formuláři</h2>
          <ul class="error-list">
            <?php foreach ($errors as $error): ?>
              <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
          </ul>
        </section>
      <?php endif; ?>

      <?php if ($submitted && $errors === []): ?>
        <section class="card success-box">
          <h2>Validace prošla</h2>
          <p><strong>Jméno:</strong> <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></p>
          <p><strong>Zpráva:</strong> <?= nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')); ?></p>
        </section>
      <?php endif; ?>

      <section class="card">
        <h2>Poznámka k bezpečnosti</h2>
        <p class="muted">
          Při výpisu uživatelských dat používáme <code>htmlspecialchars()</code>,
          aby se vstup neinterpretoval jako HTML.
        </p>
      </section>

      <a class="nav-link" href="../index.php">Zpět na přehled</a>
    </main>
  </body>
</html>
