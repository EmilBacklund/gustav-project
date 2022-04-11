function validateDelete() {
  event.preventDefault();

  if (checkLength(title.value, 0) === true) {
    titleError.style.display = "none";
  } else {
    titleError.style.display = "block";
  }
  if (checkLength(year.value, 3) === true) {
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

function checkLength(value, len) {
  if (value.trim().length > len) {
    return true;
  } else {
    return false;
  }
}
