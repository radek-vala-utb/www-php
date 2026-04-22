# PHP - výukové příklady

Tento adresář obsahuje základní edukativní ukázky pro úvod do PHP.

Struktura:

- `index.html` - statický rozcestník a přehled, funguje i na GitHub Pages
- `index.php` - rozcestník pro PHP server
- `styles.css` - společné styly pro rozcestník
- `lesson.css` - společné styly pro jednotlivé lekce
- `01-zaklady-syntaxe-a-promenna/index.php`
- `02-podminky-a-formulare/index.php`
- `03-pole-a-cykly/index.php`
- `04-funkce-a-retezce/index.php`
- `05-asociativni-pole-a-foreach/index.php`
- `06-get-a-post-validace/index.php`

Doporučené pořadí:

1. `01-zaklady-syntaxe-a-promenna`
2. `02-podminky-a-formulare`
3. `03-pole-a-cykly`
4. `04-funkce-a-retezce`
5. `05-asociativni-pole-a-foreach`
6. `06-get-a-post-validace`

## GitHub Pages vs. PHP

GitHub Pages neumí spouštět PHP. Proto:

- `index.html` lze nasadit na GitHub Pages jako statický přehled
- samotné `*.php` lekce je potřeba spouštět na serveru s PHP

## Lokalni spusteni nebo Codespaces

Ve složce projektu spusť:

```bash
php -S localhost:8000
```

Pak otevři:

- `http://localhost:8000/index.php`

Stejný princip funguje i v GitHub Codespaces, kde se stránka zobrazí přes forwardovaný port.

## GitHub Codespaces - rychlý postup

1. Otevři repozitář na GitHubu.
2. Klikni na `Code` -> `Codespaces` -> `Create codespace on ...`.
3. Po otevření Codespace počkej, až se načte dev container z `.devcontainer/devcontainer.json`.
4. V terminálu v adresáři projektu spusť:

```bash
./start-server.sh
```

5. Codespaces automaticky rozpozná port `8000` a nabídne otevření náhledu.
6. Otevři:

- `/index.php` pro plně funkční PHP rozcestník
- `/index.html` pro statickou GitHub Pages verzi

Alternativně můžeš ve VS Code spustit task:

- `Terminal` -> `Run Task...` -> `Spustit PHP server`

## Co je v projektu pro Codespaces připravené

- `.devcontainer/devcontainer.json` - připraví PHP prostředí a forward portu 8000
- `.vscode/tasks.json` - jednoduchý task pro spuštění serveru
- `start-server.sh` - nejrychlejší příkaz pro spuštění
