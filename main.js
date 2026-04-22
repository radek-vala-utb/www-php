const cards = document.querySelectorAll(".card");
const lessonCount = document.querySelector("#lesson-count");

if (lessonCount) {
  lessonCount.textContent = `Připraveno je ${cards.length} tematických lekcí. Na GitHub Pages uvidíš tento přehled, v Codespaces nebo lokálně spustíš i samotné PHP příklady.`;
}
