const titre = document.querySelector("h1");
const bouton = document.querySelector("button");

bouton.addEventListener("click", () => {
  titre.textContent = "Bienvenue sur ma page interactive !";
});
