const toggleButton = document.getElementById('toggleButton'); // On sélectionne le bouton à bascule
toggleButton.addEventListener('change', function() {
    const ajouterLigne = document.getElementById('ajouterLigne'); // On sélectionne le bouton qui permet d'ajouter une ligne en bdd
    const exempleLigne = document.getElementById('exempleLigne'); // On sélectionne l'exemple de ligne
    const elements = document.getElementsByClassName('afficheAdmin'); // On sélectionne les boutons supprimer et modifier

    const entrerValeurRows = document.querySelectorAll('.entrerValeur'); // On sélectoinne toutes les classes présente

    // Si le bouton est sélectionné, on affiche les boutons ajouter ligne, supprimer et modifier ainsi que l'exemple de ligne
    if (this.checked) { 
        // Afficher le bouton qui permet d'ajouter une ligne avec la transition
        ajouterLigne.classList.remove('d-none');

        // Afficher l'exemple de ligne avec la transition
        exempleLigne.classList.remove('d-none');

        // Afficher tous les boutons pour supprimer et enregistrer une ligne
        for (let i = 0; i < elements.length; i++) {
            elements[i].classList.remove('d-none');
        }

        // Permettre à l'admin de modifier les inputs
        entrerValeurRows.forEach(function(entrerValeurRow) {
            const tdElements = entrerValeurRow.querySelectorAll('td');

            tdElements.forEach(function(td) {
                const input = td.querySelector('input');
                if (input) {
                    input.readOnly = false;
                }
            });
        });
    } else { // s'il n'est pas sélectionné, on ne les affiches pas
        // Ne pas afficher le bouton qui permet d'ajouter une ligne
        ajouterLigne.classList.add('d-none');

        // Ne pas afficher l'exemple de ligne avec la transition
        exempleLigne.classList.add('d-none');

        // Ne pas afficher tous les boutons pour supprimer et enregistrer une ligne
        for (let i = 0; i < elements.length; i++) {
            elements[i].classList.add('d-none');
        }

        // Empecher l'utilisateur de modifier les inputs
        entrerValeurRows.forEach(function(entrerValeurRow) {
            const tdElements = entrerValeurRow.querySelectorAll('td');

            tdElements.forEach(function(td) {
                const input = td.querySelector('input');
                if (input) {
                    input.readOnly = true;
                }
            });
        });
    }
});

// Raffraichir la page au click
function RaffraichirPage() {
    location.reload(true);
}