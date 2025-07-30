document.addEventListener('load', hideAlert());

function hideAlert() {
    const alertMessage = document.querySelectorAll('.alert');
    // remove alert from DOM after 3seconds
    alertMessage.forEach((alert) => {
        setTimeout(() => {
            alert.remove();
        }, 3000)
    })
}
