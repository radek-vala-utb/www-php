<?php
require __DIR__ . '/models.php';

$databaseDirectory = __DIR__ . '/data';
$databasePath = $databaseDirectory . '/orm.sqlite';

if (!is_dir($databaseDirectory)) {
    mkdir($databaseDirectory, 0777, true);
}

$pdo = Database::connection($databasePath);
Course::setConnection($pdo);
Student::setConnection($pdo);

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS courses (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        level TEXT NOT NULL
    )'
);

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        points INTEGER NOT NULL,
        course_id INTEGER NOT NULL,
        FOREIGN KEY(course_id) REFERENCES courses(id)
    )'
);

if ((int) $pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn() === 0) {
    Course::create(['title' => 'Základy PHP', 'level' => 'začátečník']);
    Course::create(['title' => 'Objektové PHP', 'level' => 'mírně pokročilý']);
    Course::create(['title' => 'Databáze a SQL', 'level' => 'začátečník']);
}

if ((int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn() === 0) {
    $courses = Course::all('id ASC');
    Student::create([
        'name' => 'Anna Nováková',
        'email' => 'anna.orm@example.test',
        'points' => 94,
        'course_id' => $courses[0]->id,
    ]);
    Student::create([
        'name' => 'Tomáš Král',
        'email' => 'tomas.orm@example.test',
        'points' => 87,
        'course_id' => $courses[1]->id,
    ]);
}

$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entity = $_POST['entity'] ?? '';
    $action = $_POST['action'] ?? '';

    if ($entity === 'course' && $action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $level = trim($_POST['level'] ?? '');

        if ($title === '') {
            $errors[] = 'Název kurzu je povinný.';
        }

        if ($level === '') {
            $errors[] = 'Úroveň kurzu je povinná.';
        }

        if ($errors === []) {
            $course = $id > 0 ? Course::find($id) : new Course();
            if ($course !== null) {
                $course->fill([
                    'title' => $title,
                    'level' => $level,
                ]);
                $course->save();
                $message = $id > 0 ? 'Kurz byl upraven.' : 'Kurz byl vytvořen.';
            }
        }
    }

    if ($entity === 'course' && $action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $course = Course::find($id);

        $countStatement = $pdo->prepare('SELECT COUNT(*) FROM students WHERE course_id = :course_id');
        $countStatement->execute([':course_id' => $id]);
        $linkedStudents = (int) $countStatement->fetchColumn();

        if ($linkedStudents > 0) {
            $errors[] = 'Kurz nelze smazat, protože je přiřazen studentům.';
        } elseif ($course !== null) {
            $course->delete();
            $message = 'Kurz byl smazán.';
        }
    }

    if ($entity === 'student' && $action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $points = trim($_POST['points'] ?? '');
        $courseId = (int) ($_POST['course_id'] ?? 0);

        if ($name === '') {
            $errors[] = 'Jméno studenta je povinné.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'E-mail studenta musí mít platný formát.';
        }

        if ($points === '' || filter_var($points, FILTER_VALIDATE_INT) === false) {
            $errors[] = 'Body studenta musí být celé číslo.';
        }

        if ($courseId <= 0 || Course::find($courseId) === null) {
            $errors[] = 'Je potřeba vybrat existující kurz.';
        }

        if ($errors === []) {
            $student = $id > 0 ? Student::find($id) : new Student();
            if ($student !== null) {
                $student->fill([
                    'name' => $name,
                    'email' => $email,
                    'points' => (int) $points,
                    'course_id' => $courseId,
                ]);
                $student->save();
                $message = $id > 0 ? 'Student byl upraven.' : 'Student byl vytvořen.';
            }
        }
    }

    if ($entity === 'student' && $action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $student = Student::find($id);
        if ($student !== null) {
            $student->delete();
            $message = 'Student byl smazán.';
        }
    }
}

$editCourse = isset($_GET['edit_course']) ? Course::find((int) $_GET['edit_course']) : null;
$editStudent = isset($_GET['edit_student']) ? Student::find((int) $_GET['edit_student']) : null;

$courseFormTitle = $editCourse->title ?? ($_POST['entity'] ?? '') === 'course' ? ($_POST['title'] ?? '') : '';
$courseFormLevel = $editCourse->level ?? ($_POST['entity'] ?? '') === 'course' ? ($_POST['level'] ?? '') : '';
$studentFormName = $editStudent->name ?? ($_POST['entity'] ?? '') === 'student' ? ($_POST['name'] ?? '') : '';
$studentFormEmail = $editStudent->email ?? ($_POST['entity'] ?? '') === 'student' ? ($_POST['email'] ?? '') : '';
$studentFormPoints = isset($editStudent) ? (string) $editStudent->points : ((($_POST['entity'] ?? '') === 'student') ? ($_POST['points'] ?? '') : '');
$studentFormCourseId = isset($editStudent) ? $editStudent->course_id : ((($_POST['entity'] ?? '') === 'student') ? (int) ($_POST['course_id'] ?? 0) : 0);

$courses = Course::all('title ASC');
$students = Student::all('id DESC');
?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>09. Objektové PHP a mini ORM</title>
    <link rel="stylesheet" href="../lesson.css" />
  </head>
  <body>
    <main>
      <h1>09. Objektové PHP a mini ORM</h1>
      <p>
        Tady už nepracujeme přímo jen s SQL příkazy v hlavním skriptu. Místo
        toho používáme třídy <code>Course</code> a <code>Student</code>, které
        dědí ze společného modelu a umí samy načítat, ukládat i mazat data.
      </p>

      <section class="card">
        <h2>Co je tu vidět</h2>
        <p>
          Ukázka simuluje jednoduchý ORM přístup: <code>find()</code>,
          <code>all()</code>, <code>create()</code>, <code>save()</code> a
          <code>delete()</code>. Navíc je tu i vazba
          <code>$student->course()</code>.
        </p>
        <p><code><?= htmlspecialchars($databasePath, ENT_QUOTES, 'UTF-8'); ?></code></p>
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
        <h2><?= $editCourse ? 'Upravit kurz' : 'Přidat kurz'; ?></h2>
        <form action="" method="post">
          <input type="hidden" name="entity" value="course" />
          <input type="hidden" name="action" value="save" />
          <input type="hidden" name="id" value="<?= (int) ($editCourse->id ?? 0); ?>" />

          <div class="grid">
            <div class="field">
              <label for="title">Název kurzu</label>
              <input id="title" name="title" type="text" value="<?= htmlspecialchars($courseFormTitle, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="level">Úroveň</label>
              <input id="level" name="level" type="text" value="<?= htmlspecialchars($courseFormLevel, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>
          </div>

          <button type="submit"><?= $editCourse ? 'Uložit kurz' : 'Vytvořit kurz'; ?></button>
        </form>
      </section>

      <section class="card">
        <h2><?= $editStudent ? 'Upravit studenta' : 'Přidat studenta'; ?></h2>
        <form action="" method="post">
          <input type="hidden" name="entity" value="student" />
          <input type="hidden" name="action" value="save" />
          <input type="hidden" name="id" value="<?= (int) ($editStudent->id ?? 0); ?>" />

          <div class="grid">
            <div class="field">
              <label for="student-name">Jméno</label>
              <input id="student-name" name="name" type="text" value="<?= htmlspecialchars($studentFormName, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="student-email">E-mail</label>
              <input id="student-email" name="email" type="email" value="<?= htmlspecialchars($studentFormEmail, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="student-points">Body</label>
              <input id="student-points" name="points" type="number" value="<?= htmlspecialchars($studentFormPoints, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="course-id">Kurz</label>
              <select id="course-id" name="course_id">
                <option value="0">Vyber kurz</option>
                <?php foreach ($courses as $course): ?>
                  <option value="<?= (int) $course->id; ?>" <?= $studentFormCourseId === $course->id ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($course->title . ' (' . $course->level . ')', ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <button type="submit"><?= $editStudent ? 'Uložit studenta' : 'Vytvořit studenta'; ?></button>
        </form>
      </section>

      <section class="card">
        <h2>Kurzy</h2>
        <table>
          <tr>
            <th>ID</th>
            <th>Název</th>
            <th>Úroveň</th>
            <th>Akce</th>
          </tr>
          <?php foreach ($courses as $course): ?>
            <tr>
              <td><?= (int) $course->id; ?></td>
              <td><?= htmlspecialchars($course->title, ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?= htmlspecialchars($course->level, ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="actions">
                <a href="./index.php?edit_course=<?= (int) $course->id; ?>">Upravit</a>
                <form action="" method="post">
                  <input type="hidden" name="entity" value="course" />
                  <input type="hidden" name="action" value="delete" />
                  <input type="hidden" name="id" value="<?= (int) $course->id; ?>" />
                  <button type="submit">Smazat</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      </section>

      <section class="card">
        <h2>Studenti a jejich kurz</h2>
        <table>
          <tr>
            <th>ID</th>
            <th>Jméno</th>
            <th>E-mail</th>
            <th>Body</th>
            <th>Kurz</th>
            <th>Akce</th>
          </tr>
          <?php foreach ($students as $student): ?>
            <?php $course = $student->course(); ?>
            <tr>
              <td><?= (int) $student->id; ?></td>
              <td><?= htmlspecialchars($student->name, ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?= htmlspecialchars($student->email, ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?= (int) $student->points; ?></td>
              <td><?= htmlspecialchars($course?->title ?? 'Bez kurzu', ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="actions">
                <a href="./index.php?edit_student=<?= (int) $student->id; ?>">Upravit</a>
                <form action="" method="post">
                  <input type="hidden" name="entity" value="student" />
                  <input type="hidden" name="action" value="delete" />
                  <input type="hidden" name="id" value="<?= (int) $student->id; ?>" />
                  <button type="submit">Smazat</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      </section>

      <section class="card">
        <h2>Jak vypadá mini ORM</h2>
        <pre><?= htmlspecialchars(
'$student = Student::find(1);
$student->name = "Anna Nováková";
$student->points = 100;
$student->save();

$course = $student->course();

$newCourse = Course::create([
    "title" => "SQLite v PHP",
    "level" => "začátečník",
]);', ENT_QUOTES, 'UTF-8'); ?></pre>
      </section>

      <section class="card">
        <h2>Kde jsou třídy</h2>
        <p>
          Modely i společná databázová vrstva jsou v souboru
          <code>models.php</code>, aby bylo dobře vidět oddělení prezentace a
          práce s daty.
        </p>
      </section>

      <a class="nav-link" href="../index.php">Zpět na přehled</a>
    </main>
  </body>
</html>
