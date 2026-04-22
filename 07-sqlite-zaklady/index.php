<?php
$databaseDirectory = __DIR__ . '/data';
$databasePath = $databaseDirectory . '/students.sqlite';

if (!is_dir($databaseDirectory)) {
    mkdir($databaseDirectory, 0777, true);
}

$pdo = new PDO('sqlite:' . $databasePath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        course TEXT NOT NULL,
        points INTEGER NOT NULL,
        created_at TEXT NOT NULL
    )'
);

$existingCount = (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
if ($existingCount === 0) {
    $seedStatement = $pdo->prepare(
        'INSERT INTO students (name, course, points, created_at)
         VALUES (:name, :course, :points, :created_at)'
    );

    $seedData = [
        ['name' => 'Anna', 'course' => 'Základy PHP', 'points' => 92],
        ['name' => 'Karel', 'course' => 'Práce s formuláři', 'points' => 81],
        ['name' => 'Eva', 'course' => 'Úvod do databází', 'points' => 88],
    ];

    foreach ($seedData as $student) {
        $seedStatement->execute([
            ':name' => $student['name'],
            ':course' => $student['course'],
            ':points' => $student['points'],
            ':created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $points = trim($_POST['points'] ?? '');

        if ($name === '') {
            $errors[] = 'Jméno studenta je povinné.';
        }

        if ($course === '') {
            $errors[] = 'Název kurzu je povinný.';
        }

        if ($points === '' || filter_var($points, FILTER_VALIDATE_INT) === false) {
            $errors[] = 'Body musí být celé číslo.';
        }

        if ($errors === []) {
            $insertStatement = $pdo->prepare(
                'INSERT INTO students (name, course, points, created_at)
                 VALUES (:name, :course, :points, :created_at)'
            );

            $insertStatement->execute([
                ':name' => $name,
                ':course' => $course,
                ':points' => (int) $points,
                ':created_at' => date('Y-m-d H:i:s'),
            ]);

            $successMessage = 'Záznam byl uložen do SQLite databáze.';
        }
    }

    if ($action === 'reset') {
        $pdo->exec('DELETE FROM students');
        $successMessage = 'Tabulka byla vyčištěna.';
    }
}

$studentsStatement = $pdo->query(
    'SELECT id, name, course, points, created_at
     FROM students
     ORDER BY id DESC'
);
$students = $studentsStatement->fetchAll(PDO::FETCH_ASSOC);

$nameValue = $_POST['name'] ?? '';
$courseValue = $_POST['course'] ?? '';
$pointsValue = $_POST['points'] ?? '';
?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>07. SQLite a databáze</title>
    <link rel="stylesheet" href="../lesson.css" />
  </head>
  <body>
    <main>
      <h1>07. SQLite a databáze</h1>
      <p>
        Ukázka základní práce s databází přes <code>PDO</code>. Stránka sama
        vytvoří SQLite soubor, tabulku <code>students</code> a umožní přidávat
        další záznamy.
      </p>

      <section class="card">
        <h2>Kam se databáze ukládá</h2>
        <p><code><?= htmlspecialchars($databasePath, ENT_QUOTES, 'UTF-8'); ?></code></p>
      </section>

      <section class="card">
        <h2>Přidat studenta</h2>
        <form action="" method="post">
          <input type="hidden" name="action" value="create" />

          <div class="grid">
            <div class="field">
              <label for="name">Jméno</label>
              <input id="name" name="name" type="text" value="<?= htmlspecialchars($nameValue, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="course">Kurz</label>
              <input id="course" name="course" type="text" value="<?= htmlspecialchars($courseValue, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="points">Body</label>
              <input id="points" name="points" type="number" value="<?= htmlspecialchars($pointsValue, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>
          </div>

          <button type="submit">Uložit do databáze</button>
        </form>
      </section>

      <?php if ($errors !== []): ?>
        <section class="card">
          <h2>Chyby ve formuláři</h2>
          <ul class="error-list">
            <?php foreach ($errors as $error): ?>
              <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
          </ul>
        </section>
      <?php endif; ?>

      <?php if ($successMessage !== ''): ?>
        <section class="card success-box">
          <h2>Výsledek operace</h2>
          <p><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></p>
        </section>
      <?php endif; ?>

      <section class="card">
        <h2>Obsah tabulky students</h2>
        <table>
          <tr>
            <th>ID</th>
            <th>Jméno</th>
            <th>Kurz</th>
            <th>Body</th>
            <th>Vytvořeno</th>
          </tr>
          <?php foreach ($students as $student): ?>
            <tr>
              <td><?= (int) $student['id']; ?></td>
              <td><?= htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?= htmlspecialchars($student['course'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?= (int) $student['points']; ?></td>
              <td><?= htmlspecialchars($student['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </section>

      <section class="card">
        <h2>Vyčistit tabulku</h2>
        <form action="" method="post">
          <input type="hidden" name="action" value="reset" />
          <button type="submit">Smazat všechny záznamy</button>
        </form>
      </section>

      <section class="card">
        <h2>Ukázka SQL a PDO</h2>
        <pre><?= htmlspecialchars(
'$pdo = new PDO("sqlite:" . $databasePath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    course TEXT NOT NULL,
    points INTEGER NOT NULL,
    created_at TEXT NOT NULL
)");

$statement = $pdo->prepare(
    "INSERT INTO students (name, course, points, created_at)
     VALUES (:name, :course, :points, :created_at)"
);', ENT_QUOTES, 'UTF-8'); ?></pre>
      </section>

      <a class="nav-link" href="../index.php">Zpět na přehled</a>
    </main>
  </body>
</html>
