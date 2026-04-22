<?php
$category = $_GET['category'] ?? 'all';

$courses = [
    [
        'title' => 'HTML a struktura stránky',
        'category' => 'frontend',
        'level' => 'začátečník',
    ],
    [
        'title' => 'Základy PHP',
        'category' => 'backend',
        'level' => 'začátečník',
    ],
    [
        'title' => 'Práce s formulářem',
        'category' => 'backend',
        'level' => 'mírně pokročilý',
    ],
    [
        'title' => 'JavaScript v prohlizeci',
        'category' => 'frontend',
        'level' => 'začátečník',
    ],
];

$filteredCourses = [];
foreach ($courses as $course) {
    if ($category === 'all' || $course['category'] === $category) {
        $filteredCourses[] = $course;
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>05. Asociativní pole a foreach</title>
    <link rel="stylesheet" href="../lesson.css" />
  </head>
  <body>
    <main>
      <h1>05. Asociativní pole a foreach</h1>
      <p>Data uložená jako pole záznamů, výpis do tabulky a jednoduché filtrování.</p>

      <section class="card">
        <form action="" method="get">
          <div class="field">
            <label for="category">Kategorie</label>
            <select id="category" name="category">
              <option value="all" <?= $category === 'all' ? 'selected' : ''; ?>>Vše</option>
              <option value="frontend" <?= $category === 'frontend' ? 'selected' : ''; ?>>Frontend</option>
              <option value="backend" <?= $category === 'backend' ? 'selected' : ''; ?>>Backend</option>
            </select>
          </div>

          <button type="submit">Filtrovat</button>
        </form>
      </section>

      <section class="card">
        <h2>Seznam kurzů</h2>
        <table>
          <tr>
            <th>Název</th>
            <th>Kategorie</th>
            <th>Úroveň</th>
          </tr>
          <?php foreach ($filteredCourses as $course): ?>
            <tr>
              <td><?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?= htmlspecialchars($course['category'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?= htmlspecialchars($course['level'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </section>

      <section class="card">
        <h2>Ukázka datové struktury</h2>
        <pre><?= htmlspecialchars(
'$courses = [
    [
        "title" => "Základy PHP",
        "category" => "backend",
        "level" => "začátečník",
    ],
];', ENT_QUOTES, 'UTF-8'); ?></pre>
      </section>

      <a class="nav-link" href="../index.php">Zpět na přehled</a>
    </main>
  </body>
</html>
