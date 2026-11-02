const inputN1 = document.getElementById("nombre1");
const inputN2 = document.getElementById("nombre2");
const selectOperation = document.getElementById("operation");
const boutonCalculer = document.getElementById("calculer");
const texteResultat = document.getElementById("resultat");
const boutonTheme = document.getElementById("btnTheme");

function appliquerTheme(theme) {
  if (theme === "dark") {
    document.body.classList.add("dark-mode");
    boutonTheme.textContent = " Mode clair";
    boutonTheme.className = "btn btn-sm btn-outline-light";
    boutonCalculer.className = "btn btn-light w-100 mt-2 text-dark";
  } else {
    document.body.classList.remove("dark-mode");
    boutonTheme.textContent = "Mode sombre";
    boutonTheme.className = "btn btn-sm btn-outline-secondary";
    boutonCalculer.className = "btn btn-dark w-100 mt-2";
  }
}

const themeSauvegarde = localStorage.getItem("themePreference") || "light";
appliquerTheme(themeSauvegarde);

boutonTheme.addEventListener("click", () => {
  const estActuellementSombre = document.body.classList.contains("dark-mode");
  const nouveauTheme = estActuellementSombre ? "light" : "dark";

  localStorage.setItem("themePreference", nouveauTheme);
  appliquerTheme(nouveauTheme);
});

function animerResultat() {
  texteResultat.classList.remove("anime-resultat");

  void texteResultat.offsetWidth;
  texteResultat.classList.add("anime-resultat");
}

function effectuerCalcul() {
  const valeur1 = inputN1.value.trim();
  const valeur2 = inputN2.value.trim();
  const op = selectOperation.value;

  texteResultat.className = "mt-3 text-center fs-5 fw-bold mb-0";

  if (valeur1 === "" || valeur2 === "") {
    texteResultat.textContent = "Erreur : saisis les deux nombres.";
    texteResultat.classList.add("text-warning");
    animerResultat();
    return;
  }

  const n1 = parseFloat(valeur1);
  const n2 = parseFloat(valeur2);

  if (op === "/" && n2 === 0) {
    texteResultat.textContent = "Erreur : division par zéro impossible.";
    texteResultat.classList.add("text-danger");
    animerResultat();
    return;
  }

  let total = 0;
  switch (op) {
    case "+":
      total = n1 + n2;
      break;
    case "-":
      total = n1 - n2;
      break;
    case "*":
      total = n1 * n2;
      break;
    case "/":
      total = n1 / n2;
      break;
  }

  const resultatArrondi = Math.round(total * 1000000) / 1000000;
  texteResultat.textContent = `Résultat : ${resultatArrondi}`;

  if (resultatArrondi > 0) {
    texteResultat.classList.add("text-success");
  } else if (resultatArrondi < 0) {
    texteResultat.classList.add("text-danger");
  } else {
    texteResultat.classList.add("text-secondary");
  }

  animerResultat();
}

boutonCalculer.addEventListener("click", effectuerCalcul);

[inputN1, inputN2].forEach((champ) => {
  champ.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      effectuerCalcul();
    }
  });
});
