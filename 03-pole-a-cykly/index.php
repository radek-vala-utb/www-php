<?php
$input = $_GET['items'] ?? 'HTML, CSS, JavaScript, PHP';
$rawItems = explode(',', $input);
$items = [];

foreach ($rawItems as $rawItem) {
    $trimmed = trim($rawItem);
    if ($trimmed !== '') {
        $items[] = $trimmed;
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>03. Pole a cykly</title>
    <link rel="stylesheet" href="../lesson.css" />
  </head>
  <body>
    <main>
      <h1>03. Pole a cykly</h1>
      <p>Rozdeleni vstupu na pole a jeho nasledny vypis pomoci cyklu <code>foreach</code>.</p>

      <section class="card">
        <form action="" method="get">
          <div class="field">
            <label for="items">Polozky oddelene carkou</label>
            <input id="items" name="items" type="text" value="<?= htmlspecialchars($input, ENT_QUOTES, 'UTF-8'); ?>" />
          </div>

          <button type="submit">Zpracovat</button>
        </form>
      </section>

      <section class="card">
        <h2>Vysledek</h2>
        <p>Nalezeno polozek: <strong><?= count($items); ?></strong></p>
        <pre><?php foreach ($items as $index => $item): ?>
<?= htmlspecialchars(($index + 1) . '. ' . $item, ENT_QUOTES, 'UTF-8') . "\n"; ?><?php endforeach; ?></pre>
      </section>

      <section class="card">
        <h2>Co se deje v kodu</h2>
        <pre><?= htmlspecialchars(
'$rawItems = explode(",", $input);
$items = [];

foreach ($rawItems as $rawItem) {
    $trimmed = trim($rawItem);
    if ($trimmed !== "") {
        $items[] = $trimmed;
    }
}', ENT_QUOTES, 'UTF-8'); ?></pre>
      </section>

      <a class="nav-link" href="../index.php">Zpet na prehled</a>
    </main>
  </body>
</html>
