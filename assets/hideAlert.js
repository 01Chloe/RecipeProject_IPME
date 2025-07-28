document.addEventListener('load', hideAlert());

function hideAlert() {
    const alertMessage = document.querySelectorAll('.alert');
    alertMessage.forEach((alert) => {
        setTimeout(() => {
            alert.remove();
        }, 3000)
    })
}
