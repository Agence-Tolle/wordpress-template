const navigation = document.querySelector('.navigation');
const burgers = document.querySelectorAll('.burger');
let lastScrollDistance = 0;

// NAVIGATION SCROLL / HIDE
window.addEventListener('scroll', () => {
    const currentScrollDistance = window.scrollY;

    if (currentScrollDistance < 300) {
        navigation.classList.remove('hideNav');
    } else if (currentScrollDistance > lastScrollDistance) {
        navigation.classList.add('hideNav');
    } else {
        navigation.classList.remove('hideNav');
    }

    lastScrollDistance = currentScrollDistance;
});

// BURGER TRIGGER
burgers.forEach((burger) => {
    burger.addEventListener('click', () => {
        document.body.classList.toggle('menu-shown');
    });
});
