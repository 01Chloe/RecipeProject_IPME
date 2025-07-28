function initCollectionForm() {
    const buttonsAddForm = document.querySelectorAll('[data-btn-selector]');
    buttonsAddForm.forEach((btnElt) => {
        btnElt.addEventListener('click', () => {

            const dataValueSelector = btnElt.getAttribute('data-btn-selector');
            let list = document.querySelector('[data-list-selector="'+dataValueSelector+'"]');
            let counter = list.children.length;
            let newWidget = list.getAttribute('data-prototype');
            newWidget = newWidget.replace(/__name__/g, counter.toString());
            // newWidget = newWidget.replace('mb-3', 'mb-3 w-100');

            const icon = createIcon(counter);

            counter++;
            list.setAttribute('widget-counter', counter.toString());

            let newDiv = mainDivForm();
            newDiv.innerHTML = newWidget;
            newDiv.insertAdjacentElement('afterbegin', icon)

            list.appendChild(newDiv);
            icon.addEventListener('click', () => {
                newDiv.innerHTML = '';
                newDiv.remove();
            });
        });
    });
}

function initButtonCollectionForm() {
    const collectionForm = document.querySelectorAll('[data-list-selector]');
    if (collectionForm) {
        for (const form of collectionForm) {
            let i = 0;
            const savedChildren = [];
            for (const child of form.children) {
                child.classList.add('w-100');
                savedChildren.push(child.outerHTML);
            }
            form.innerHTML = '';

            for (const childSaved of savedChildren) {
                const divForm = mainDivForm();
                const icon = createIcon(i);
                divForm.innerHTML = childSaved;
                divForm.insertAdjacentElement('afterbegin', icon);
                i++;
                form.appendChild(divForm);
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
    newDiv.classList.add('d-flex');
    return newDiv;
}

function createIcon(counter) {
    const icon= document.createElement('img');
    icon.setAttribute('data-delete-form', counter.toString());
    icon.src = "./assets/icons/xmark.svg";
    icon.alt = "Supprimer";
    icon.classList.add('icon');
    icon.style.cursor = 'pointer';
    // icon.classList.add('fa');
    // icon.classList.add('fa-trash');
    // icon.classList.add('icon-click');
    // icon.classList.add('me-3');
    // icon.classList.add('mt-1');
    // icon.classList.add('fa-2x');
    return icon;
}

window.addEventListener('load', () => {
    initCollectionForm();
    initButtonCollectionForm();
});
