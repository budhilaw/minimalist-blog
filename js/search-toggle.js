/**
 * File search-toggle.js.
 *
 * Handles toggling the search form visibility.
 */
(() => {
    const searchToggle = document.getElementById('search-toggle');
    const searchFormContainer = document.querySelector('.search-form-container');

    // Return early if the search toggle or form container doesn't exist.
    if (!searchToggle || !searchFormContainer) {
        return;
    }

    // Toggle the .active class each time the search button is clicked.
    searchToggle.addEventListener('click', (event) => {
        event.preventDefault();
        searchFormContainer.classList.toggle('active');
        
        // If the search form is now visible, focus the search input
        if (searchFormContainer.classList.contains('active')) {
            const searchInput = searchFormContainer.querySelector('.search-field');
            if (searchInput) {
                setTimeout(() => {
                    searchInput.focus();
                }, 100);
            }
        }
    });

    // Hide search form when clicking outside of it
    document.addEventListener('click', (event) => {
        const isClickInside = searchFormContainer.contains(event.target) || searchToggle.contains(event.target);

        if (!isClickInside && searchFormContainer.classList.contains('active')) {
            searchFormContainer.classList.remove('active');
        }
    });

    // Close search form on Escape key press
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && searchFormContainer.classList.contains('active')) {
            searchFormContainer.classList.remove('active');
            searchToggle.focus();
        }
    });
})(); 