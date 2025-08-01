// fonction d'initialisation des formulaires de type CollectionType
// gere les champs ajoutés via le bouton "ajouter"
function initCollectionForm() {
    const buttonsAddForm = document.querySelectorAll('[data-btn-selector]');
    buttonsAddForm.forEach((btnElt) => {
        // ajout un ecouteur d'évenement à chaque bouton qui à un attribut [data-btn-selector]
        btnElt.addEventListener('click', () => {

            // récupère la valeur de l'attribut data-btn-selector du bouton
            const dataValueSelector = btnElt.getAttribute('data-btn-selector');
            // sélectionne la liste correspondant à cette valeur via l'attribut data-list-selector
            let list = document.querySelector('[data-list-selector="'+dataValueSelector+'"]');
            // compte combien d'éléments enfants il y a dans cette liste
            let counter = list.children.length;
            // récupère le prototype HTML
            let newWidget = list.getAttribute('data-prototype');
            // remplace tous les `__name__` dans le prototype par le compteur actuel
            newWidget = newWidget.replace(/__name__/g, counter.toString());

            // créer l'icon pour supprimer l'élément
            const icon = createIcon(counter);

            // incrément le compteur de 1
            counter++;
            // met à jour le compteur dans l'attribut HTML
            list.setAttribute('widget-counter', counter.toString());

            // créer la div qui va contenir le nouveau champs
            let newDiv = mainDivForm();
            // lui injecter le HTML et l'icon de suppression
            newDiv.innerHTML = newWidget;
            newDiv.insertAdjacentElement('afterbegin', icon)

            // ajoute le nouveau champs à la liste
            list.appendChild(newDiv);
            // ajout un écouteur d'événement sur l'icon de suppression
            icon.addEventListener('click', () => {
                // vide l'HTML du champ et le supprime du DOM
                newDiv.innerHTML = '';
                newDiv.remove();
            });
        });
    });
}

// gere les champs déjà existant en base
function initButtonCollectionForm() {
    // récupère tous les élément ayant l'attribut [data-list-selector]
    const collectionForm = document.querySelectorAll('[data-list-selector]');
    if (collectionForm) {
        for (const form of collectionForm) {
            let i = 0;
            // stock temporairement les enfants existants
            const savedChildren = [];
            for (const child of form.children) {
                // sauvegarde le HTML de chaque enfant actuel du conteneur
                savedChildren.push(child.outerHTML);
            }
            // vide complètement le conteneur
            form.innerHTML = '';

            // pour chaque élément sauvegardé
            for (const childSaved of savedChildren) {
                // créer une div
                const divForm = mainDivForm();
                // ajouter l'icon de suppression
                const icon = createIcon(i);
                // ajouter l'HTML de l'enfant dans la div
                divForm.innerHTML = childSaved;
                // ajouter la div sous l'icon supprimer
                divForm.insertAdjacentElement('afterbegin', icon);
                // incrémenter le compteur pour passer à l'enfant suivant
                i++;
                // ajouter toutes les div au formulaire
                form.appendChild(divForm);
                // ajouter un écouteur d'événement sur le bouton supprimer
                icon.addEventListener('click', () => {
                    divForm.innerHTML = '';
                    divForm.remove();
                });
            }
        }
    }
}

function mainDivForm() {
    let newDiv = document.createElement('div');
    newDiv.classList.add('ingredient-group');
    return newDiv;
}

function createIcon(counter) {
    const icon= document.createElement('img');
    icon.setAttribute('data-delete-form', counter.toString());
    icon.src = "/assets/icons/xmark.svg";
    icon.alt = "Supprimer";
    icon.classList.add('icon');
    icon.classList.add('center-icon');
    icon.style.cursor = 'pointer';
    return icon;
}

window.addEventListener('load', () => {
    initCollectionForm();
    initButtonCollectionForm();
});
