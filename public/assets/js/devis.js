/* Formulaire de devis */
(function () {
    const formulaire = document.querySelector('.formulaire');
    if (!formulaire) return;

    const champsDevis = formulaire.querySelectorAll('[data-devis]');
    if (champsDevis.length === 0) return;

    const message = formulaire.querySelector('#contact-message');
    const entete = 'Type d\'événement : ';

    if (message.value.startsWith(entete)) return;

    // 1. Affiche les champs détaillés
    champsDevis.forEach(function (element) {
        element.hidden = false;
    });

    // 2. Champs libre devient facultatif
    formulaire.querySelector('label[for="contact-message"]').textContent = 'Votre message';
    formulaire.querySelector('#aide-message').textContent =
        "Facultatif : précision, contraintes, questions.";
    message.required = false;

    // 3. Une étape à la fois
    const etapes = Array.from(formulaire.querySelectorAll('[data-etape]'));
    let etapeActive = 0;

    function premierInvalide(conteneur) {
        return Array.from(conteneur.querySelectorAll('[required]')).find(function (champ) {
            return !champ.checkValidity();
        });
    }

    function afficherEtape(index, deplacerFocus) {
        etapeActive = index;
        etapes.forEach(function (etape, i) {
            etape.hidden = i !== index;
        });
        if (deplacerFocus) {
            etapes[index].focus();
        }
    }

    function creerBouton(texte, classe) {
        const bouton = document.createElement('button');
        bouton.type = 'button';
        bouton.className = classe;
        bouton.textContent = texte;
        return bouton;
    }

    etapes.forEach(function (etape, index) {
        // Permet de placer le focus sur l'étape
        etape.tabIndex = -1;

        let navigation = etape.querySelector('.devis__navigation');
        if (!navigation) {
            navigation = document.createElement('p');
            navigation.className = 'devis__navigation';
            etape.append(navigation);
        }

        if (index > 0) {
            const precedent = creerBouton('Précédent', 'bouton bouton--secondaire');
            precedent.addEventListener('click', function () {
                afficherEtape(index - 1, true);
            });
            navigation.prepend(precedent);
        }

        if (index < etapes.length - 1) {
            const suivant = creerBouton('Suivant', 'bouton');
            suivant.addEventListener('click', function () {
                const invalide = premierInvalide(etape);
                if (invalide) {
                    invalide.reportValidity();
                    return;
                }
                afficherEtape(index + 1, true);
            });
            navigation.append(suivant);
        }
    });

    formulaire.addEventListener('keydown', function (evenement) {
        if (evenement.key === 'Enter' && evenement.target.tagName === 'INPUT'
            && etapeActive < etapes.length - 1) {
            evenement.preventDefault();
            etapes[etapeActive].querySelector('.devis__navigation .bouton:last-child').click();
        }
    });

    afficherEtape(0, false);

    // Libellé affiché dans le message reçu
    const champs = [
        ['Type d\'événement', 'devis-type'],
        ['Date', 'devis-date'],
        ['Convives', 'devis-convives'],
        ['Lieu', 'devis-lieu'],
        ['Formule', 'devis-formule'],
        ['Budget', 'devis-budget'],
        ['Téléphone', 'devis-telephone']
    ];

    // A l'envoi : vérifie puis rassemble
    formulaire.addEventListener('submit', function (evenement) {
        const invalide = premierInvalide(formulaire);

        if (invalide) {
            evenement.preventDefault();
            afficherEtape(etapes.findIndex(function (etape) {
                return etape.contains(invalide);
            }), false);
            invalide.reportValidity();
            return;
        }

        const donnees = new FormData(formulaire);
        const lignes = [];

        champs.forEach(function ([libelle, nom]) {
            let valeur = String(donnees.get(nom) || '').trim();
            if (valeur === '') return;

            if (nom === 'devis-date') valeur = valeur.split('-').reverse().join('/');
            if (nom === 'devis-budget') valeur = valeur + ' €';

            lignes.push(libelle + ' : ' + valeur);
        });

        const libre = message.value.trim();
        if (libre !== '') {
            lignes.push('', 'Message :', libre);
        }
        message.value = lignes.join('\n');
    });
})();