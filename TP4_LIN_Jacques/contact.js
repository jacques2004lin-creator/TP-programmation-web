document.addEventListener("DOMContentLoaded", function () {
  const contactForm = document.getElementById("contactForm");
  const successMessage = document.getElementById("successMessage");

  contactForm.addEventListener(
    "submit",
    function (event) {
      event.preventDefault();
      event.stopPropagation();

      if (!contactForm.checkValidity()) {
        contactForm.classList.add("was-validated");
      } else {
        contactForm.classList.remove("was-validated");
        contactForm.reset();
        successMessage.classList.remove("d-none");

        successMessage.scrollIntoView({
          behavior: "smooth",
          block: "center",
        });

        setTimeout(function () {
          successMessage.classList.add("d-none");
        }, 8000);
      }
    },
    false,
  );
});
