// Menu mobile
const bouton = document.querySelector('.menu-bouton');

if (bouton) {
    bouton.hidden = false;
    bouton.closest('.en-tete').classList.add('en-tete--js');

    bouton.addEventListener('click', () => {
        const ouvert = bouton.getAttribute('aria-expanded') === 'true';
        bouton.setAttribute('aria-expanded', String(!ouvert));
    });
}