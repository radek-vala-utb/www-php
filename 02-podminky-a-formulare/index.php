<?php
$ageInput = $_GET['age'] ?? '17';
$age = (int) $ageInput;
$isStudent = isset($_GET['student']);

if ($age < 0) {
    $group = 'Neplatny vek';
    $discount = 0;
} elseif ($age < 18) {
    $group = 'Junior';
    $discount = 20;
} elseif ($age >= 65) {
    $group = 'Senior';
    $discount = 25;
} elseif ($isStudent) {
    $group = 'Student';
    $discount = 15;
} else {
    $group = 'Dospely';
    $discount = 0;
}
?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>02. Podminky a formulare</title>
    <link rel="stylesheet" href="../lesson.css" />
  </head>
  <body>
    <main>
      <h1>02. Podminky a formulare</h1>
      <p>Ukazka rozhodovani pomoci <code>if</code>, <code>elseif</code> a <code>else</code>.</p>

      <section class="card">
        <form action="" method="get">
          <div class="field">
            <label for="age">Vek</label>
            <input id="age" name="age" type="number" value="<?= htmlspecialchars($ageInput, ENT_QUOTES, 'UTF-8'); ?>" />
          </div>

          <div class="field">
            <label for="student">Je student</label>
            <input
              id="student"
              name="student"
              type="checkbox"
              value="1"
              <?= $isStudent ? 'checked' : ''; ?>
            />
          </div>

          <button type="submit">Vyhodnotit</button>
        </form>
      </section>

      <section class="card">
        <h2>Vyhodnoceni</h2>
        <p>Kategorie: <strong><?= htmlspecialchars($group, ENT_QUOTES, 'UTF-8'); ?></strong></p>
        <p>Sleva: <strong><?= $discount; ?> %</strong></p>
        <pre><?= htmlspecialchars(
'if ($age < 0) {
    $group = "Neplatny vek";
} elseif ($age < 18) {
    $group = "Junior";
} elseif ($age >= 65) {
    $group = "Senior";
} elseif ($isStudent) {
    $group = "Student";
} else {
    $group = "Dospely";
}', ENT_QUOTES, 'UTF-8'); ?></pre>
      </section>

      <a class="nav-link" href="../index.php">Zpet na prehled</a>
    </main>
  </body>
</html>
