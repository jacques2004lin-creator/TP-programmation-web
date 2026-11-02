document.addEventListener("DOMContentLoaded", function () {
  const reservationForm = document.getElementById("reservationForm");

  if (reservationForm) {
    reservationForm.addEventListener("submit", function (event) {
      event.preventDefault();

      const nom = document.getElementById("nom").value;
      const personnes = document.getElementById("personnes").value;

      alert(
        `Merci ${nom}. Votre réservation pour ${personnes} personne(s) a bien été confirmée. L'équipe de L'Atelier vous attend.`,
      );
      reservationForm.reset();
    });
  }
});
