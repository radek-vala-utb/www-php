<?php
$lessons = [
    [
        'title' => '01. Základy syntaxe a proměnné',
        'path' => './01-zaklady-syntaxe-a-promenna/index.php',
        'description' => 'Proměnné, čísla, text a základní výpočet věku.',
    ],
    [
        'title' => '02. Podmínky a formuláře',
        'path' => './02-podminky-a-formulare/index.php',
        'description' => 'Vyhodnocení podmínek a reakce na vstup uživatele.',
    ],
    [
        'title' => '03. Pole a cykly',
        'path' => './03-pole-a-cykly/index.php',
        'description' => 'Explode, trim, count a foreach nad jednoduchým polem.',
    ],
    [
        'title' => '04. Funkce a řetězce',
        'path' => './04-funkce-a-retezce/index.php',
        'description' => 'Vlastní funkce, návratové hodnoty a úprava textu.',
    ],
    [
        'title' => '05. Asociativní pole a foreach',
        'path' => './05-asociativni-pole-a-foreach/index.php',
        'description' => 'Data ve tvaru klíč-hodnota a výpis tabulky.',
    ],
    [
        'title' => '06. GET a POST validace',
        'path' => './06-get-a-post-validace/index.php',
        'description' => 'Zpracování formuláře a kontrola povinných polí.',
    ],
    [
        'title' => '07. SQLite a databáze',
        'path' => './07-sqlite-zaklady/index.php',
        'description' => 'Základní práce s PDO, SQLite databází a tabulkou záznamů.',
    ],
    [
        'title' => '08. Kompletní CRUD nad SQLite',
        'path' => './08-sqlite-crud-zaklad/index.php',
        'description' => 'Create, Read, Update a Delete nad jednou tabulkou studentů.',
    ],
    [
        'title' => '09. Objektové PHP a mini ORM',
        'path' => './09-oop-a-mini-orm/index.php',
        'description' => 'Objektové modely, repository-like přístup a vazba studentů na kurzy.',
    ],
];
?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PHP výukové příklady</title>
    <link rel="stylesheet" href="./styles.css" />
  </head>
  <body>
    <main class="page">
      <header class="hero">
        <p class="eyebrow">Úvod do PHP</p>
        <h1>PHP výukové příklady</h1>
        <p>
          Tato verze běží na PHP serveru, takže jednotlivé lekce můžeš rovnou
          spouštět a zkoumat na nich zpracování vstupu, formuláře i výstupy na
          serveru.
        </p>
      </header>

      <section class="panel">
        <h2>Jak to spustit</h2>
        <p>
          V kořenové složce projektu stačí použít <code>php -S localhost:8000</code> a
          otevřít <code>index.php</code>. V GitHub Pages funguje jen statický
          přehled v souboru <code>index.html</code>.
        </p>
        <p>Celkem <?= count($lessons); ?> lekcí v doporučeném pořadí od základů po validaci formuláře.</p>
      </section>

      <section class="grid">
        <?php foreach ($lessons as $lesson): ?>
          <article class="card">
            <h2><?= htmlspecialchars($lesson['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <p><?= htmlspecialchars($lesson['description'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><a href="<?= htmlspecialchars($lesson['path'], ENT_QUOTES, 'UTF-8'); ?>">Otevřít příklad</a></p>
          </article>
        <?php endforeach; ?>
      </section>
    </main>
  </body>
</html>
