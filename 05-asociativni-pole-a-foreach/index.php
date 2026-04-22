<?php
$category = $_GET['category'] ?? 'all';

$courses = [
    [
        'title' => 'HTML a struktura stranky',
        'category' => 'frontend',
        'level' => 'zacatecnik',
    ],
    [
        'title' => 'Zaklady PHP',
        'category' => 'backend',
        'level' => 'zacatecnik',
    ],
    [
        'title' => 'Prace s formularem',
        'category' => 'backend',
        'level' => 'mirne pokrocily',
    ],
    [
        'title' => 'JavaScript v prohlizeci',
        'category' => 'frontend',
        'level' => 'zacatecnik',
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
    <title>05. Asociativni pole a foreach</title>
    <link rel="stylesheet" href="../lesson.css" />
  </head>
  <body>
    <main>
      <h1>05. Asociativni pole a foreach</h1>
      <p>Data ulozena jako pole zaznamu, vypis do tabulky a jednoduche filtrovani.</p>

      <section class="card">
        <form action="" method="get">
          <div class="field">
            <label for="category">Kategorie</label>
            <select id="category" name="category">
              <option value="all" <?= $category === 'all' ? 'selected' : ''; ?>>Vse</option>
              <option value="frontend" <?= $category === 'frontend' ? 'selected' : ''; ?>>Frontend</option>
              <option value="backend" <?= $category === 'backend' ? 'selected' : ''; ?>>Backend</option>
            </select>
          </div>

          <button type="submit">Filtrovat</button>
        </form>
      </section>

      <section class="card">
        <h2>Seznam kurzu</h2>
        <table>
          <tr>
            <th>Nazev</th>
            <th>Kategorie</th>
            <th>Uroven</th>
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
        <h2>Ukazka datove struktury</h2>
        <pre><?= htmlspecialchars(
'$courses = [
    [
        "title" => "Zaklady PHP",
        "category" => "backend",
        "level" => "zacatecnik",
    ],
];', ENT_QUOTES, 'UTF-8'); ?></pre>
      </section>

      <a class="nav-link" href="../index.php">Zpet na prehled</a>
    </main>
  </body>
</html>
