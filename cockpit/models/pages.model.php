<?php

/**
 * Site pages.
 *
 * Fixed structure: the customer fills it in, never edits it. Installed to
 * public/admin/storage/content/ by bin/install-cockpit.php.
 *
 * A page is a list of blocks. One block type = one entry in the `type` list,
 * a few fields shown by a `condition`, and a Twig partial of the same name in
 * templates/blocs/. Adding a type is documented in the README.
 *
 * Publication is not a field: Cockpit tracks it natively on `_state`
 * (1 published, 0 unpublished, -1 archived) with its own button in the admin.
 * The public site only ever serves items with `_state` = 1.
 */

// Headings only, plus links and lists: the page title is the only level-one
// heading, and the toolbar must not offer anything that would break that.
$toolbar = 'format | link | listBullet listOrdered';

// Conditions are evaluated in the admin against the block being edited.
$isHero = "data.type === 'hero'";
$isTexteImage = "data.type === 'texte-image'";
$isContact = "data.type === 'contact'";
$isFormulaire = "data.type === 'formulaire'";
$isTemoignages = "data.type === 'temoignages'";
$hasImage = "['hero', 'texte-image', 'contact'].includes(data.type)";
$hasTexte = "['texte-image', 'contact', 'formulaire'].includes(data.type)";

return [
    'name' => 'pages',
    'label' => 'Pages',
    'info' => 'Les pages du site.',
    'type' => 'collection',
    'group' => null,
    'preview' => [],
    'meta' => [
        'unique' => ['slug'],
    ],
    '_created' => 1754179200,
    '_modified' => 1754352000,

    'fields' => [
        [
            'name' => 'titre',
            'type' => 'text',
            'label' => 'Titre de la page',
            'info' => 'Apparaît en titre principal en haut de la page et dans le menu.',
            'required' => true,
            'localize' => false,
            'multiple' => false,
            'group' => 'Contenu',
            'width' => '1-1',
            'opts' => [],
        ],
        [
            'name' => 'slug',
            'type' => 'text',
            'label' => 'Adresse de la page',
            'info' => "Termine l'adresse de la page : « services » donne /services. Lettres minuscules et tirets.",
            'required' => true,
            'localize' => false,
            'multiple' => false,
            'group' => 'Contenu',
            'width' => '1-1',
            'opts' => ['placeholder' => 'services'],
        ],

        // ── Ajout Maison Lestrade ─────────────────────────────────────────
        [
            'name' => 'chapo',
            'type' => 'text',
            'label' => 'Introduction',
            'info' => 'Phrase affichée sous le titre de la page. Par exemple : Nous rencontrer à l’atelier. Inutile sur l’accueil.',
            'required' => false,
            'localize' => false,
            'multiple' => false,
            'group' => 'Contenu',
            'width' => '1-1',
            'opts' => ['multiline' => true, 'maxlength' => 250],
        ],
        // ── Fin de l'ajout Maison Lestrade ────────────────────────────────

        // ── Blocs ─────────────────────────────────────────────────────────
        [
            'name' => 'blocs',
            'type' => 'set',
            'label' => 'Contenu de la page',
            'info' => 'Les sections affichées sous le titre, dans cet ordre.',
            'required' => false,
            'localize' => false,
            'multiple' => true,
            'group' => 'Contenu',
            'width' => '1-1',
            'opts' => [
                'display' => '${data.titre || data.type || \'Section\'}',
                'fields' => [
                    [
                        'name' => 'type',
                        'type' => 'select',
                        'label' => 'Type de section',
                        'required' => true,
                        'width' => '1-1',
                        'opts' => [
                            'options' => [
                                ['value' => 'hero', 'label' => 'Bandeau d’ouverture'],
                                ['value' => 'texte-image', 'label' => 'Texte et image'],
                                ['value' => 'contact', 'label' => 'Coordonnées'],
                                ['value' => 'formulaire', 'label' => 'Formulaire de contact'],
                                ['value' => 'temoignages', 'label' => 'Témoignages'],
                                ['value' => 'cartes', 'label' => 'Cartes'],
                                ['value' => 'galerie', 'label' => 'Galerie de photos'],
                                ['value' => 'appel', 'label' => 'Appel à l\'action'],
                            ],
                        ],
                    ],
                    [
                        'name' => 'titre',
                        'type' => 'text',
                        'label' => 'Titre de la section',
                        'info' => 'Apparaît en tête de la section.',
                        'width' => '1-1',
                        'opts' => [],
                    ],
                    [
                        'name' => 'accroche',
                        'type' => 'text',
                        'label' => 'Accroche',
                        'info' => 'La phrase affichée sous le titre du bandeau.',
                        'width' => '1-1',
                        'condition' => $isHero,
                        'opts' => ['multiline' => true, 'maxlength' => 200],
                    ],
                    [
                        'name' => 'texte',
                        'type' => 'wysiwyg',
                        'label' => 'Texte',
                        'info' => 'Le corps de la section.',
                        'width' => '1-1',
                        'condition' => $hasTexte,
                        'opts' => ['toolbar' => $toolbar],
                    ],
                    [
                        'name' => 'image',
                        'type' => 'asset',
                        'label' => 'Image',
                        'info' => 'Apparaît dans la section. Format paysage conseillé.',
                        'width' => '1-2',
                        'condition' => $hasImage,
                        'opts' => ['filter' => ['type' => 'image']],
                    ],
                    [
                        'name' => 'alt',
                        'type' => 'text',
                        'label' => 'Description de l’image',
                        'info' => 'Lue à voix haute par les lecteurs d’écran, et affichée si l’image ne charge pas. Décrire ce que l’on voit.',
                        'width' => '1-2',
                        'condition' => $hasImage,
                        'opts' => ['maxlength' => 150],
                    ],
                    [
                        'name' => 'positionImage',
                        'type' => 'select',
                        'label' => 'Position de l’image',
                        'width' => '1-2',
                        'condition' => $isTexteImage,
                        'opts' => [
                            'options' => [
                                ['value' => 'droite', 'label' => 'À droite du texte'],
                                ['value' => 'gauche', 'label' => 'À gauche du texte'],
                            ],
                        ],
                    ],
                    [
                        'name' => 'boutonTexte',
                        'type' => 'text',
                        'label' => 'Texte du bouton',
                        'info' => 'Laisser vide pour ne pas afficher de bouton.',
                        'width' => '1-2',
                        'condition' => $isHero,
                        'opts' => ['maxlength' => 40],
                    ],
                    [
                        'name' => 'boutonLien',
                        'type' => 'text',
                        'label' => 'Adresse du bouton',
                        'info' => 'Adresse d’une page du site, par exemple /services.',
                        'width' => '1-2',
                        'condition' => $isHero,
                        'opts' => ['placeholder' => '/services'],
                    ],
                    // ── Témoignages ─────────────────────────────────────
                    // Exemple commenté d'un type de section : voir le partial
                    // templates/blocs/temoignages.html.twig et
                    // docs/guide-integration.md.
                    [
                        'name' => 'introduction',
                        'type' => 'text',
                        'label' => 'Phrase d’introduction',
                        'info' => 'Apparaît sous le titre, avant les témoignages.',
                        'width' => '1-1',
                        'condition' => $isTemoignages,
                        'opts' => ['multiline' => true, 'maxlength' => 200],
                    ],
                    [
                        'name' => 'temoignages',
                        'type' => 'set',
                        'label' => 'Témoignages',
                        'info' => 'Chaque entrée devient un témoignage affiché dans la section.',
                        'multiple' => true,
                        'width' => '1-1',
                        'condition' => $isTemoignages,
                        'opts' => [
                            'display' => '${data.auteur || \'Témoignage\'}',
                            'fields' => [
                                [
                                    'name' => 'citation',
                                    'type' => 'text',
                                    'label' => 'Ce que dit la personne',
                                    'required' => true,
                                    'width' => '1-1',
                                    'opts' => ['multiline' => true, 'maxlength' => 400],
                                ],
                                [
                                    'name' => 'auteur',
                                    'type' => 'text',
                                    'label' => 'Nom',
                                    'width' => '1-2',
                                    'opts' => [],
                                ],
                                [
                                    'name' => 'fonction',
                                    'type' => 'text',
                                    'label' => 'Fonction ou ville',
                                    'info' => 'Affichée sous le nom, en plus discret.',
                                    'width' => '1-2',
                                    'opts' => [],
                                ],
                                [
                                    'name' => 'portrait',
                                    'type' => 'asset',
                                    'label' => 'Portrait',
                                    'info' => 'Facultatif. Image carrée conseillée.',
                                    'width' => '1-2',
                                    'opts' => ['filter' => ['type' => 'image']],
                                ],
                                [
                                    'name' => 'alt',
                                    'type' => 'text',
                                    'label' => 'Description du portrait',
                                    'info' => 'Obligatoire dès qu’un portrait est choisi.',
                                    'width' => '1-2',
                                    'opts' => ['maxlength' => 150],
                                ],
                            ],
                        ],
                    ],

                    [
                        'name' => 'afficherHoraires',
                        'type' => 'boolean',
                        'label' => 'Afficher les horaires',
                        'info' => 'Les horaires saisis dans « Identité du site » apparaissent sous les coordonnées.',
                        'width' => '1-1',
                        'condition' => $isContact,
                        'opts' => ['default' => true],
                    ],
                    // ── Ajouts Maison Lestrade ──────────────────────────
                    [
                        'name' => 'surtitre',
                        'type' => 'text',
                        'label' => 'Surtitre',
                        'info' => 'Petite ligne affichée au-dessus du titre. Par exemple : Traiteur événementiel · Périgueux · Depuis 1998.',
                        'width' => '1-1',
                        'condition' => $isHero,
                        'opts' => ['maxlength' => 80],
                    ],
                    [
                        'name' => 'bouton2Texte',
                        'type' => 'text',
                        'label' => 'Texte du second bouton',
                        'info' => 'Bouton secondaire, affiché à côté du premier. Laisser vide pour ne pas l’afficher.',
                        'width' => '1-2',
                        'condition' => $isHero,
                        'opts' => ['maxlength' => 40],
                    ],
                    [
                        'name' => 'bouton2Lien',
                        'type' => 'text',
                        'label' => 'Adresse du second bouton',
                        'info' => 'Adresse d’une page du site, par exemple /formules.',
                        'width' => '1-2',
                        'condition' => $isHero,
                        'opts' => ['placeholder' => '/formules'],
                    ],

                    [
                        'name' => 'cartes',
                        'type' => 'set',
                        'label' => 'Cartes',
                        'info' => 'Chaque entrée devient une carte : image, précision, titre et texte.',
                        'multiple' => true,
                        'width' => '1-1',
                        'condition' => "data.type === 'cartes'",
                        'opts' => [
                            'display' => '${data.titre || \'Carte\'}',
                            'fields' => [
                                [
                                    'name' => 'image',
                                    'type' => 'asset',
                                    'label' => 'Image',
                                    'info' => 'Format portrait conseillé.',
                                    'width' => '1-2',
                                    'opts' => ['filter' => ['type' => 'image']],
                                ],
                                [
                                    'name' => 'alt',
                                    'type' => 'text',
                                    'label' => 'Description de l’image',
                                    'info' => 'Lue par les lecteurs d’écran. Décrire ce que l’on voit.',
                                    'width' => '1-2',
                                    'opts' => ['maxlength' => 150],
                                ],
                                [
                                    'name' => 'precision',
                                    'type' => 'text',
                                    'label' => 'Précision',
                                    'info' => 'Petite ligne au-dessus du titre. Par exemple : De 50 à 250 convives.',
                                    'width' => '1-2',
                                    'opts' => ['maxlength' => 60],
                                ],
                                [
                                    'name' => 'titre',
                                    'type' => 'text',
                                    'label' => 'Titre de la carte',
                                    'required' => true,
                                    'width' => '1-2',
                                    'opts' => ['maxlength' => 60],
                                ],
                                [
                                    'name' => 'texte',
                                    'type' => 'text',
                                    'label' => 'Texte',
                                    'info' => 'Une ou deux lignes sous le titre.',
                                    'width' => '1-1',
                                    'opts' => ['multiline' => true, 'maxlength' => 160],
                                ],
                                [
                                    'name' => 'lien',
                                    'type' => 'text',
                                    'label' => 'Lien de la carte',
                                    'info' => 'Adresse d’une page du site, par exemple /prestations. Vide : la carte n’est pas cliquable.',
                                    'width' => '1-1',
                                    'opts' => ['placeholder' => '/prestations'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'detaillee',
                        'type' => 'boolean',
                        'label' => 'Afficher les filtres et les légendes',
                        'info' => 'À cocher sur la page Nos réceptions. Décoché : une simple mosaïque de photos, comme sur l’accueil.',
                        'width' => '1-1',
                        'condition' => "data.type === 'galerie'",
                        'opts' => ['default' => false],
                    ],
                    [
                        'name' => 'photos',
                        'type' => 'set',
                        'label' => 'Photos',
                        'info' => 'Uniquement des photos pour lesquelles les personnes reconnaissables ont donné leur accord écrit.',
                        'multiple' => true,
                        'width' => '1-1',
                        'condition' => "data.type === 'galerie'",
                        'opts' => [
                            'display' => '${data.lieu || data.alt || \'Photo\'}',
                            'fields' => [
                                [
                                    'name' => 'image',
                                    'type' => 'asset',
                                    'label' => 'Photo',
                                    'required' => true,
                                    'width' => '1-2',
                                    'opts' => ['filter' => ['type' => 'image']],
                                ],
                                [
                                    'name' => 'alt',
                                    'type' => 'text',
                                    'label' => 'Description de la photo',
                                    'info' => 'Lue par les lecteurs d’écran. Décrire ce que l’on voit.',
                                    'width' => '1-2',
                                    'opts' => ['maxlength' => 150],
                                ],
                                [
                                    'name' => 'evenement',
                                    'type' => 'select',
                                    'label' => 'Type d’événement',
                                    'required' => true,
                                    'info' => 'Sert au classement de la page Nos réceptions.',
                                    'width' => '1-3',
                                    'opts' => [
                                        'options' => [
                                            ['value' => 'mariage', 'label' => 'Mariage'],
                                            ['value' => 'entreprise', 'label' => 'Séminaire & entreprise'],
                                            ['value' => 'privee', 'label' => 'Réception privée'],
                                        ],
                                    ],
                                ],
                                [
                                    'name' => 'lieu',
                                    'type' => 'text',
                                    'label' => 'Lieu',
                                    'info' => 'Affiché dans la légende. Par exemple : Château de Lanquais.',
                                    'width' => '1-3',
                                    'opts' => ['maxlength' => 80],
                                ],
                                [
                                    'name' => 'date',
                                    'type' => 'date',
                                    'label' => 'Date de la réception',
                                    'info' => 'Seuls le mois et l’année sont affichés.',
                                    'width' => '1-3',
                                    'opts' => [],
                                ],
                            ],
                        ],
                    ],

                    [
                        'name' => 'appelPresentation',
                        'type' => 'select',
                        'label' => 'Présentation',
                        'info' => 'Bandeau : pleine largeur, en bas de page. Encart : bloc crème. Encadré : cadre fin, sans fond.',
                        'width' => '1-1',
                        'condition' => "data.type === 'appel'",
                        'opts' => [
                            'default' => 'bandeau',
                            'options' => [
                                ['value' => 'bandeau', 'label' => 'Bandeau'],
                                ['value' => 'encart', 'label' => 'Encart'],
                                ['value' => 'encadre', 'label' => 'Encadré'],
                            ],
                        ],
                    ],
                    [
                        'name' => 'appelTexte',
                        'type' => 'text',
                        'label' => 'Texte',
                        'info' => 'Affiché sous le titre de la section.',
                        'width' => '1-1',
                        'condition' => "data.type === 'appel'",
                        'opts' => ['multiline' => true, 'maxlength' => 250],
                    ],
                    [
                        'name' => 'appelBoutonTexte',
                        'type' => 'text',
                        'label' => 'Texte du bouton',
                        'info' => 'Laisser vide pour ne pas afficher de bouton.',
                        'width' => '1-2',
                        'condition' => "data.type === 'appel'",
                        'opts' => ['maxlength' => 40],
                    ],
                    [
                        'name' => 'appelBoutonLien',
                        'type' => 'text',
                        'label' => 'Adresse du bouton',
                        'info' => 'Adresse d’une page du site, par exemple /demander-un-devis.',
                        'width' => '1-2',
                        'condition' => "data.type === 'appel'",
                        'opts' => ['placeholder' => '/demander-un-devis'],
                    ],
                    // ── Fin des ajouts Maison Lestrade ──────────────────
                ],
            ],
        ],

        // ── Référencement ─────────────────────────────────────────────────
        [
            'name' => 'seoTitre',
            'type' => 'text',
            'label' => 'Titre dans les résultats de recherche',
            'info' => "Apparaît en bleu dans Google et dans l'onglet du navigateur. Vide, le titre de la page est repris.",
            'required' => false,
            'localize' => false,
            'multiple' => false,
            'group' => 'Référencement',
            'width' => '1-1',
            'opts' => ['maxlength' => 60, 'showCount' => true],
        ],
        [
            'name' => 'seoDescription',
            'type' => 'text',
            'label' => 'Résumé dans les résultats de recherche',
            'info' => 'Apparaît sous le titre dans Google. Environ 155 caractères.',
            'required' => false,
            'localize' => false,
            'multiple' => false,
            'group' => 'Référencement',
            'width' => '1-1',
            'opts' => ['multiline' => true, 'maxlength' => 160, 'showCount' => true],
        ],
    ],
];
