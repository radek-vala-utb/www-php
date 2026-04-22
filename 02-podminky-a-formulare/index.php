<?php
$ageInput = $_GET['age'] ?? '17';
$age = (int) $ageInput;
$isStudent = isset($_GET['student']);

if ($age < 0) {
    $group = 'Neplatný věk';
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
    $group = 'Dospělý';
    $discount = 0;
}
?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>02. Podmínky a formuláře</title>
    <link rel="stylesheet" href="../lesson.css" />
  </head>
  <body>
    <main>
      <h1>02. Podmínky a formuláře</h1>
      <p>Ukázka rozhodování pomocí <code>if</code>, <code>elseif</code> a <code>else</code>.</p>

      <section class="card">
        <form action="" method="get">
          <div class="field">
            <label for="age">Věk</label>
            <input id="age" name="age" type="number" value="<?= htmlspecialchars($ageInput, ENT_QUOTES, 'UTF-8'); ?>" />
          </div>

          <div class="field">
            <label for="student">Je studentem</label>
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
        <h2>Vyhodnocení</h2>
        <p>Kategorie: <strong><?= htmlspecialchars($group, ENT_QUOTES, 'UTF-8'); ?></strong></p>
        <p>Sleva: <strong><?= $discount; ?> %</strong></p>
        <pre><?= htmlspecialchars(
'if ($age < 0) {
    $group = "Neplatný věk";
} elseif ($age < 18) {
    $group = "Junior";
} elseif ($age >= 65) {
    $group = "Senior";
} elseif ($isStudent) {
    $group = "Student";
} else {
    $group = "Dospělý";
}', ENT_QUOTES, 'UTF-8'); ?></pre>
      </section>

      <a class="nav-link" href="../index.php">Zpět na přehled</a>
    </main>
  </body>
</html>
