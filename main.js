const cards = document.querySelectorAll(".card");
const lessonCount = document.querySelector("#lesson-count");

if (lessonCount) {
  lessonCount.textContent = `Pripraveno je ${cards.length} tematickych lekci. Na GitHub Pages uvidis tento prehled, v Codespaces nebo lokalne spustis i samotne PHP priklady.`;
}
