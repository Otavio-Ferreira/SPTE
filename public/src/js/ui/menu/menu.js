document.addEventListener("DOMContentLoaded", () => {
    const urlAtual = window.location.pathname;

    const liHome = document.getElementById("li-home");
    const linkHome = document.getElementById("link-home");

    const liUsuarios = document.getElementById("li-usuarios");
    const linkUsuarios = document.getElementById("link-usuarios");

    const classesFundoAtivo = ['bg-primary-subtle', 'border-end', 'border-primary', 'border-4'];

    if (urlAtual.includes("/home") && liHome && linkHome) {
        liHome.classList.add(...classesFundoAtivo);
        linkHome.classList.remove("text-dark");
        linkHome.classList.add("text-primary", "fw-bold");
    } 

    else if (urlAtual.includes("/usuarios") && liUsuarios && linkUsuarios) {
        liUsuarios.classList.add(...classesFundoAtivo);
        linkUsuarios.classList.remove("text-dark");
        linkUsuarios.classList.add("text-primary", "fw-bold");
    }
});