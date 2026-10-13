document.querySelectorAll('.bloc-galerie--detaillee').forEach((galerie) => {
    const liens = galerie.querySelectorAll('.galerie__filtres a');
    const groupes = galerie.querySelectorAll('.galerie__groupe');

    if (liens.length === 0) {
        return;
    }

    const afficher = (id) => {
        groupes.forEach((groupe) => {
            groupe.hidden = id !== 'galerie-toutes' && groupe.id !== id;
        });
        liens.forEach((lien) => {
            if (lien.getAttribute('href') === '#' + id) {
                lien.setAttribute('aria-current', 'true');
            } else {
                lien.removeAttribute('aria-current');
            }
        });
    };

    liens.forEach((lien) => {
        lien.addEventListener('click', (evenement) => {
            evenement.preventDefault();
            afficher(lien.getAttribute('href').slice(1));
        });
    });
    afficher(liens[0].getAttribute('href').slice(1));
});