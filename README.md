# PHP - vyukove priklady

Tento adresar obsahuje zakladni edukativni ukazky pro uvod do PHP.

Struktura:

- `index.html` - staticky rozcestnik a prehled, funguje i na GitHub Pages
- `index.php` - rozcestnik pro PHP server
- `styles.css` - spolecne styly pro rozcestnik
- `lesson.css` - spolecne styly pro jednotlive lekce
- `01-zaklady-syntaxe-a-promenna/index.php`
- `02-podminky-a-formulare/index.php`
- `03-pole-a-cykly/index.php`
- `04-funkce-a-retezce/index.php`
- `05-asociativni-pole-a-foreach/index.php`
- `06-get-a-post-validace/index.php`

Doporucene poradi:

1. `01-zaklady-syntaxe-a-promenna`
2. `02-podminky-a-formulare`
3. `03-pole-a-cykly`
4. `04-funkce-a-retezce`
5. `05-asociativni-pole-a-foreach`
6. `06-get-a-post-validace`

## GitHub Pages vs. PHP

GitHub Pages neumi spoustet PHP. Proto:

- `index.html` lze nasadit na GitHub Pages jako staticky prehled
- samotne `*.php` lekce je potreba spoustet na serveru s PHP

## Lokalni spusteni nebo Codespaces

Ve slozce projektu spust:

```bash
php -S localhost:8000
```

Pak otevri:

- `http://localhost:8000/index.php`

Stejny princip funguje i v GitHub Codespaces, kde se stranka zobrazi pres forwardovany port.

## GitHub Codespaces - rychly postup

1. Otevri repozitar na GitHubu.
2. Klikni na `Code` -> `Codespaces` -> `Create codespace on ...`.
3. Po otevreni Codespace pockej, az se nacte dev container z `.devcontainer/devcontainer.json`.
4. V terminalu v adresari projektu spust:

```bash
./start-server.sh
```

5. Codespaces automaticky rozpozna port `8000` a nabidne otevreni nahledu.
6. Otevri:

- `/index.php` pro plne funkcni PHP rozcestnik
- `/index.html` pro statickou GitHub Pages verzi

Alternativne muzes ve VS Code spustit task:

- `Terminal` -> `Run Task...` -> `Spustit PHP server`

## Co je v projektu pro Codespaces pripravene

- `.devcontainer/devcontainer.json` - pripravi PHP prostredi a forward portu 8000
- `.vscode/tasks.json` - jednoduchy task pro start serveru
- `start-server.sh` - nejrychlejsi prikaz pro spusteni
