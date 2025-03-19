/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens.
 */
(() => {
    const siteNavigation = document.getElementById('site-navigation');
    const button = document.querySelector('.menu-toggle');

    // Return early if the navigation or button doesn't exist.
    if (!siteNavigation || !button) {
        return;
    }

    // Toggle the .toggled class and the aria-expanded value each time the button is clicked.
    button.addEventListener('click', () => {
        siteNavigation.classList.toggle('toggled');
        
        if (button.getAttribute('aria-expanded') === 'true') {
            button.setAttribute('aria-expanded', 'false');
        } else {
            button.setAttribute('aria-expanded', 'true');
        }
    });

    // Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
    document.addEventListener('click', (event) => {
        const isClickInside = siteNavigation.contains(event.target) || button.contains(event.target);

        if (!isClickInside) {
            siteNavigation.classList.remove('toggled');
            button.setAttribute('aria-expanded', 'false');
        }
    });
})();

