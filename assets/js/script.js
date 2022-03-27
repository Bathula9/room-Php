let input_prix = document.querySelector("input[name=prix]");
input_prix.addEventListener("change", () => {
  document.getElementById("prix_max").textContent = input_prix.value;
});
