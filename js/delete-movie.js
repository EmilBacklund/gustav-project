const form = document.querySelector("#deleteConfirm");
const title = document.querySelector("#title");
const titleError = document.querySelector("#titleError");
const year = document.querySelector("#year");
const yearError = document.querySelector("#yearError");
const director = document.querySelector("#director");
const directorError = document.querySelector("#directorError");
const overlay = document.getElementById("overlay");
const modalSelection = document.querySelector(".modal");
const closeModalButtons = document.querySelectorAll("[data-close-button]");

function validateDelete() {
  event.preventDefault();
  overlay.classList.add("activated");
  modalSelection.classList.add("activated");
}

form.addEventListener("submit", validateDelete);

overlay.addEventListener("click", () => {
  const modals = document.querySelectorAll(".modal.activated");
  modals.forEach((modal) => {
    closeModal(modal);
  });
});

closeModalButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const modal = button.closest(".modal");
    closeModal(modal);
  });
});

function closeModal(modal) {
  if (modal == null) return;
  modal.classList.remove("activated");
  overlay.classList.remove("activated");
}
