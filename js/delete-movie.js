const form = document.querySelector("#deleteConfirm");
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
