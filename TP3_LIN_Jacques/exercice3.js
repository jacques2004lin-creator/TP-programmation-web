let taches = JSON.parse(localStorage.getItem("taches")) || [];

const champTache = document.getElementById("nouvelleTache");
const boutonAjouter = document.getElementById("ajouter");
const listeTaches = document.getElementById("listeTaches");

function sauvegarderEtAfficher() {
  localStorage.setItem("taches", JSON.stringify(taches));
  afficherTaches();
}

function ajouterTache() {
  const texte = champTache.value.trim();

  if (texte === "") {
    champTache.focus();
    return;
  }

  const nouvelleTache = {
    id: Date.now(),
    texte: texte,
    terminee: false,
  };

  taches.push(nouvelleTache);
  champTache.value = "";
  champTache.focus();

  sauvegarderEtAfficher();
}

function supprimerTache(id) {
  taches = taches.filter((tache) => tache.id !== id);
  sauvegarderEtAfficher();
}

function basculerEtat(id) {
  const tache = taches.find((t) => t.id === id);
  if (tache) {
    tache.terminee = !tache.terminee;
    sauvegarderEtAfficher();
  }
}

function afficherTaches() {
  listeTaches.innerHTML = "";

  taches.forEach((tache) => {
    const li = document.createElement("li");
    li.className = "d-flex align-items-center justify-content-between mb-2";

    const spanTexte = document.createElement("span");
    spanTexte.textContent = tache.texte;

    if (tache.terminee) {
      spanTexte.classList.add("text-muted", "text-decoration-line-through");
    }

    const groupeBoutons = document.createElement("div");

    const btnTerminer = document.createElement("button");
    btnTerminer.textContent = tache.terminee ? "Reprendre" : "Terminer";
    btnTerminer.className = "btn btn-sm btn-success me-2";
    btnTerminer.addEventListener("click", () => basculerEtat(tache.id));

    const btnSupprimer = document.createElement("button");
    btnSupprimer.textContent = "Supprimer";
    btnSupprimer.className = "btn btn-sm btn-danger";
    btnSupprimer.addEventListener("click", () => supprimerTache(tache.id));

    groupeBoutons.appendChild(btnTerminer);
    groupeBoutons.appendChild(btnSupprimer);

    li.appendChild(spanTexte);
    li.appendChild(groupeBoutons);

    listeTaches.appendChild(li);
  });
}

boutonAjouter.addEventListener("click", ajouterTache);

champTache.addEventListener("keydown", (evenement) => {
  if (evenement.key === "Enter") {
    ajouterTache();
  }
});
afficherTaches();
