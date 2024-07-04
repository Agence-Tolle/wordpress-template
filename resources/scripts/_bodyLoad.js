function handleBodyOpacity() {
    document.body.classList.add('loaded');
}

window.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        handleBodyOpacity();
    }, 700);
});
