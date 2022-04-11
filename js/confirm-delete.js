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

  if (checkLength(title.value, 0) === true) {
    titleError.style.display = "none";
  } else {
    titleError.style.display = "block";
  }
  if (checkLengthYear(year.value, 4) === true) {
    yearError.style.display = "none";
  } else {
    yearError.style.display = "block";
  }
  if (checkLength(director.value, 0) === true) {
    directorError.style.display = "none";
  } else {
    directorError.style.display = "block";
  }
  if (
    titleError.style.display === "none" &&
    yearError.style.display === "none" &&
    directorError.style.display === "none"
  ) {
    overlay.classList.add("activated");
    modalSelection.classList.add("activated");
  }
}

form.addEventListener("submit", validateDelete);

function checkLengthYear(value, len) {
  if (value.trim().length === len && typeof year.value === "number") {
    return true;
  } else {
    return false;
  }
}

function checkLength(value, len) {
  if (value.trim().length > len) {
    return true;
  } else {
    return false;
  }
}

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
