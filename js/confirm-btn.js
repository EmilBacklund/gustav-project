const confirmButton = document.querySelector(".confirm-btn");

console.log(confirmButton);

confirmButton.addEventListener("mousedown", () =>
  confirmButton.classList.add("active")
);

confirmButton.addEventListener("mouseup", function () {
  const mouseUp = this;
  setTimeout(function () {
    mouseUp.classList.remove("active");
  }, 450);
});

confirmButton.addEventListener("mouseout", function () {
  const btnLeave = this;
  setTimeout(function () {
    btnLeave.classList.remove("active");
  }, 450);
});
