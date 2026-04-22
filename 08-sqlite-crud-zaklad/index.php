<?php
$databaseDirectory = __DIR__ . '/data';
$databasePath = $databaseDirectory . '/crud.sqlite';

if (!is_dir($databaseDirectory)) {
    mkdir($databaseDirectory, 0777, true);
}

$pdo = new PDO('sqlite:' . $databasePath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        points INTEGER NOT NULL,
        created_at TEXT NOT NULL
    )'
);

$rowCount = (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
if ($rowCount === 0) {
    $seed = $pdo->prepare(
        'INSERT INTO students (name, email, points, created_at)
         VALUES (:name, :email, :points, :created_at)'
    );

    $seedRows = [
        ['name' => 'Anna Nováková', 'email' => 'anna@example.test', 'points' => 91],
        ['name' => 'Karel Dvořák', 'email' => 'karel@example.test', 'points' => 78],
        ['name' => 'Eva Černá', 'email' => 'eva@example.test', 'points' => 85],
    ];

    foreach ($seedRows as $row) {
        $seed->execute([
            ':name' => $row['name'],
            ':email' => $row['email'],
            ':points' => $row['points'],
            ':created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

$errors = [];
$message = '';
$editingStudent = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';

    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $points = trim($_POST['points'] ?? '');

        if ($name === '') {
            $errors[] = 'Jméno je povinné.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'E-mail musí mít platný formát.';
        }

        if ($points === '' || filter_var($points, FILTER_VALIDATE_INT) === false) {
            $errors[] = 'Body musí být celé číslo.';
        }

        if ($errors === []) {
            if ($id > 0) {
                $statement = $pdo->prepare(
                    'UPDATE students
                     SET name = :name, email = :email, points = :points
                     WHERE id = :id'
                );

                $statement->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':points' => (int) $points,
                    ':id' => $id,
                ]);

                $message = 'Záznam byl upraven.';
            } else {
                $statement = $pdo->prepare(
                    'INSERT INTO students (name, email, points, created_at)
                     VALUES (:name, :email, :points, :created_at)'
                );

                $statement->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':points' => (int) $points,
                    ':created_at' => date('Y-m-d H:i:s'),
                ]);

                $message = 'Nový student byl přidán.';
            }
        }
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $statement = $pdo->prepare('DELETE FROM students WHERE id = :id');
        $statement->execute([':id' => $id]);
        $message = 'Záznam byl smazán.';
    }

    if ($action === 'reset') {
        $pdo->exec('DELETE FROM students');
        $message = 'Tabulka byla vyčištěna.';
    }
}

$editId = (int) ($_GET['edit'] ?? 0);
if ($editId > 0) {
    $statement = $pdo->prepare('SELECT id, name, email, points FROM students WHERE id = :id');
    $statement->execute([':id' => $editId]);
    $editingStudent = $statement->fetch(PDO::FETCH_ASSOC) ?: null;
}

$formId = (int) ($editingStudent['id'] ?? ($_POST['id'] ?? 0));
$formName = $editingStudent['name'] ?? ($_POST['name'] ?? '');
$formEmail = $editingStudent['email'] ?? ($_POST['email'] ?? '');
$formPoints = $editingStudent['points'] ?? ($_POST['points'] ?? '');

$students = $pdo->query(
    'SELECT id, name, email, points, created_at
     FROM students
     ORDER BY id DESC'
)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>08. Kompletní CRUD nad SQLite</title>
    <link rel="stylesheet" href="../lesson.css" />
  </head>
  <body>
    <main>
      <h1>08. Kompletní CRUD nad SQLite</h1>
      <p>
        Tato ukázka spojuje všechny základní databázové operace:
        <strong>Create</strong>, <strong>Read</strong>, <strong>Update</strong>
        a <strong>Delete</strong>. Pracujeme nad jedinou tabulkou
        <code>students</code>.
      </p>

      <section class="card">
        <h2>Databázový soubor</h2>
        <p><code><?= htmlspecialchars($databasePath, ENT_QUOTES, 'UTF-8'); ?></code></p>
      </section>

      <section class="card">
        <h2><?= $formId > 0 ? 'Upravit studenta' : 'Přidat studenta'; ?></h2>
        <form action="" method="post">
          <input type="hidden" name="action" value="save" />
          <input type="hidden" name="id" value="<?= $formId; ?>" />

          <div class="grid">
            <div class="field">
              <label for="name">Jméno</label>
              <input id="name" name="name" type="text" value="<?= htmlspecialchars((string) $formName, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="email">E-mail</label>
              <input id="email" name="email" type="email" value="<?= htmlspecialchars((string) $formEmail, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="points">Body</label>
              <input id="points" name="points" type="number" value="<?= htmlspecialchars((string) $formPoints, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>
          </div>

          <button type="submit"><?= $formId > 0 ? 'Uložit změny' : 'Přidat studenta'; ?></button>
        </form>

        <?php if ($formId > 0): ?>
          <p><a class="nav-link" href="./index.php">Přepnout zpět na režim vytvoření</a></p>
        <?php endif; ?>
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

      <?php if ($message !== ''): ?>
        <section class="card success-box">
          <h2>Stav operace</h2>
          <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        </section>
      <?php endif; ?>

      <section class="card">
        <h2>Seznam studentů</h2>
        <table>
          <tr>
            <th>ID</th>
            <th>Jméno</th>
            <th>E-mail</th>
            <th>Body</th>
            <th>Vytvořeno</th>
            <th>Akce</th>
          </tr>
          <?php foreach ($students as $student): ?>
            <tr>
              <td><?= (int) $student['id']; ?></td>
              <td><?= htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?= htmlspecialchars($student['email'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?= (int) $student['points']; ?></td>
              <td><?= htmlspecialchars($student['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="actions">
                <a href="./index.php?edit=<?= (int) $student['id']; ?>">Upravit</a>
                <form action="" method="post">
                  <input type="hidden" name="action" value="delete" />
                  <input type="hidden" name="id" value="<?= (int) $student['id']; ?>" />
                  <button type="submit">Smazat</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      </section>

      <section class="card">
        <h2>SQL v praxi</h2>
        <pre><?= htmlspecialchars(
'INSERT INTO students (name, email, points, created_at)
VALUES (:name, :email, :points, :created_at);

SELECT id, name, email, points, created_at
FROM students
ORDER BY id DESC;

UPDATE students
SET name = :name, email = :email, points = :points
WHERE id = :id;

DELETE FROM students
WHERE id = :id;', ENT_QUOTES, 'UTF-8'); ?></pre>
      </section>

      <section class="card">
        <h2>Vyčistit tabulku</h2>
        <form action="" method="post">
          <input type="hidden" name="action" value="reset" />
          <button type="submit">Smazat všechny záznamy</button>
        </form>
      </section>

      <a class="nav-link" href="../index.php">Zpět na přehled</a>
    </main>
  </body>
</html>
