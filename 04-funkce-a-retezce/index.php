<?php
function make_title(string $text): string
{
    return ucwords(strtolower(trim($text)));
}

function make_slug(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text ?? '', '-');
}

$input = $_GET['text'] ?? 'uvod do PHP';
$title = make_title($input);
$slug = make_slug($input);
$length = strlen(trim($input));
?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>04. Funkce a řetězce</title>
    <link rel="stylesheet" href="../lesson.css" />
  </head>
  <body>
    <main>
      <h1>04. Funkce a řetězce</h1>
      <p>Ukázka vlastních funkcí, parametrů, návratové hodnoty a úpravy textu.</p>

      <section class="card">
        <form action="" method="get">
          <div class="field">
            <label for="text">Vstupní text</label>
            <input id="text" name="text" type="text" value="<?= htmlspecialchars($input, ENT_QUOTES, 'UTF-8'); ?>" />
          </div>

          <button type="submit">Upravit text</button>
        </form>
      </section>

      <section class="card">
        <h2>Výstupy funkcí</h2>
        <table>
          <tr>
            <th>Položka</th>
            <th>Hodnota</th>
          </tr>
          <tr>
            <td>make_title()</td>
            <td><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></td>
          </tr>
          <tr>
            <td>make_slug()</td>
            <td><?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?></td>
          </tr>
          <tr>
            <td>Délka po trim()</td>
            <td><?= $length; ?></td>
          </tr>
        </table>
      </section>

      <section class="card">
        <h2>Kód</h2>
        <pre><?= htmlspecialchars(
'function make_title(string $text): string
{
    return ucwords(strtolower(trim($text)));
}

function make_slug(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace("/[^a-z0-9]+/", "-", $text);
    return trim($text ?? "", "-");
}', ENT_QUOTES, 'UTF-8'); ?></pre>
      </section>

      <a class="nav-link" href="../index.php">Zpět na přehled</a>
    </main>
  </body>
</html>
