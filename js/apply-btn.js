const applyButton = document.querySelector(".apply-btn");

applyButton.addEventListener("mousedown", () =>
  applyButton.classList.add("active")
);

applyButton.addEventListener("mouseup", function () {
  const mouseUp = this;
  setTimeout(function () {
    mouseUp.classList.remove("active");
  });
});

applyButton.addEventListener("mouseout", function () {
  const btnLeave = this;
  setTimeout(function () {
    btnLeave.classList.remove("active");
  });
});
