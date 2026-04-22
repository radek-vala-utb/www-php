<?php
$name = trim($_GET['name'] ?? 'Anna');
$birthYearInput = $_GET['year'] ?? '2004';
$birthYear = (int) $birthYearInput;
$currentYear = (int) date('Y');
$age = $birthYear > 0 ? $currentYear - $birthYear : null;

$outputLines = [
    '$name = "' . $name . '";',
    '$birthYear = ' . $birthYear . ';',
    '$currentYear = ' . $currentYear . ';',
    '$age = ' . ($age === null ? 'null' : $age) . ';',
    '',
    'Ahoj, ' . $name . '.',
    $age === null
        ? 'Vek se nepodarilo spocitat.'
        : 'V roce ' . $currentYear . ' je ti priblizne ' . $age . ' let.',
];
?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>01. Zaklady syntaxe a promenne</title>
    <link rel="stylesheet" href="../lesson.css" />
  </head>
  <body>
    <main>
      <h1>01. Zaklady syntaxe a promenne</h1>
      <p>
        Jednoducha ukazka promennych, cisel, textu a vypoctu veku pomoci PHP.
      </p>

      <section class="card">
        <form action="" method="get">
          <div class="grid">
            <div class="field">
              <label for="name">Jmeno</label>
              <input id="name" name="name" type="text" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="year">Rok narozeni</label>
              <input id="year" name="year" type="number" value="<?= htmlspecialchars($birthYearInput, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>
          </div>

          <button type="submit">Spocitat</button>
        </form>
      </section>

      <section class="card">
        <h2>Vystup</h2>
        <pre><?= htmlspecialchars(implode("\n", $outputLines), ENT_QUOTES, 'UTF-8'); ?></pre>
      </section>

      <a class="nav-link" href="../index.php">Zpet na prehled</a>
    </main>
  </body>
</html>
