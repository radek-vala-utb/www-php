<?php
$lessons = [
    [
        'title' => '01. Zaklady syntaxe a promenne',
        'path' => './01-zaklady-syntaxe-a-promenna/index.php',
        'description' => 'Promenne, cisla, text a zakladni vypocet veku.',
    ],
    [
        'title' => '02. Podminky a formulare',
        'path' => './02-podminky-a-formulare/index.php',
        'description' => 'Vyhodnoceni podminek a reakce na vstup uzivatele.',
    ],
    [
        'title' => '03. Pole a cykly',
        'path' => './03-pole-a-cykly/index.php',
        'description' => 'Explode, trim, count a foreach nad jednoduchym polem.',
    ],
    [
        'title' => '04. Funkce a retezce',
        'path' => './04-funkce-a-retezce/index.php',
        'description' => 'Vlastni funkce, navratove hodnoty a uprava textu.',
    ],
    [
        'title' => '05. Asociativni pole a foreach',
        'path' => './05-asociativni-pole-a-foreach/index.php',
        'description' => 'Data ve tvaru klic-hodnota a vypis tabulky.',
    ],
    [
        'title' => '06. GET a POST validace',
        'path' => './06-get-a-post-validace/index.php',
        'description' => 'Zpracovani formularu a kontrola povinnych poli.',
    ],
];
?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PHP vyukove priklady</title>
    <link rel="stylesheet" href="./styles.css" />
  </head>
  <body>
    <main class="page">
      <header class="hero">
        <p class="eyebrow">Uvod do PHP</p>
        <h1>PHP vyukove priklady</h1>
        <p>
          Tato verze bezi na PHP serveru, takze jednotlive lekce muzes rovnou
          spoustet a zkoumat na nich zpracovani vstupu, formulare i vystupy na
          serveru.
        </p>
      </header>

      <section class="panel">
        <h2>Jak to spustit</h2>
        <p>
          V rootu projektu staci pouzit <code>php -S localhost:8000</code> a
          otevrit <code>index.php</code>. V GitHub Pages funguje jen staticky
          prehled v souboru <code>index.html</code>.
        </p>
        <p>Celkem <?= count($lessons); ?> lekci v doporucenem poradi od zakladu po validaci formulare.</p>
      </section>

      <section class="grid">
        <?php foreach ($lessons as $lesson): ?>
          <article class="card">
            <h2><?= htmlspecialchars($lesson['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <p><?= htmlspecialchars($lesson['description'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><a href="<?= htmlspecialchars($lesson['path'], ENT_QUOTES, 'UTF-8'); ?>">Otevrit priklad</a></p>
          </article>
        <?php endforeach; ?>
      </section>
    </main>
  </body>
</html>
