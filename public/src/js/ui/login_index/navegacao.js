
export function navegacao() {
    const linkInicio = document.querySelector('#link-inicio');
    const linkSobre = document.querySelector('#link-sobre');

    const secaoInicio = document.querySelector('#secao-inicio');
    const secaoSobre = document.querySelector('#secao-sobre');

    if (!linkInicio || !linkSobre || !secaoInicio || !secaoSobre) return;

    linkInicio.addEventListener('click', (e) => {
        e.preventDefault();

        secaoInicio.classList.remove('oculto');
        secaoSobre.classList.add('oculto');

        linkInicio.parentElement.classList.add('ativo');
        linkSobre.parentElement.classList.remove('ativo');
    });

    linkSobre.addEventListener('click', (e) => {
        e.preventDefault();

        secaoSobre.classList.remove('oculto');
        secaoInicio.classList.add('oculto');

        linkSobre.parentElement.classList.add('ativo');
        linkInicio.parentElement.classList.remove('ativo');
    })
    
}


