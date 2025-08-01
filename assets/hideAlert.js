document.addEventListener('load', hideAlert());

function hideAlert() {
    const alertMessage = document.querySelectorAll('.alert');
    // supprimer les messages "flash alert" du DOM après 3secondes
    alertMessage.forEach((alert) => {
        setTimeout(() => {
            alert.remove();
        }, 3000)
    })
}
