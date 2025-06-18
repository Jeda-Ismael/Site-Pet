function mostrar(id) {
  const sections = document.querySelectorAll(".section");
  sections.forEach(section => section.classList.add("hidden"));
  const target = document.getElementById(id);
  if (target) {
    target.classList.remove("hidden");
    target.scrollIntoView({ behavior: 'smooth' });
  }
}

// Mostrar home ao iniciar
mostrar("home");

// Menu funcional
document.querySelectorAll(".menu a").forEach(link => {
  link.addEventListener("click", e => {
    e.preventDefault();
    const id = link.getAttribute("href").substring(1);
    mostrar(id);
  });
});
