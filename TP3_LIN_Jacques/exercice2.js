const inputPrenom = document.querySelector("#prenom");
const bouton = document.querySelector("#btnMessage");
const message = document.querySelector("#message");

bouton.addEventListener("click", () => {
  const valeur = inputPrenom.value.trim();

  if (valeur !== "") {
    message.textContent = `bienvenue ${valeur}`;
  } else {
    message.textContent = "il faut saisir ton prénom.";
  }
});
