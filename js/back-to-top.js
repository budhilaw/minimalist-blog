/**
 * Back to top button functionality
 * 
 * Adds smooth scrolling for the back to top button.
 */
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.querySelector('.back-to-top');
        
        if (backToTopButton) {
            // Add click event for smooth scrolling
            backToTopButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Smooth scroll to top
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            // Show/hide button based on scroll position
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTopButton.classList.add('visible');
                } else {
                    backToTopButton.classList.remove('visible');
                }
            });
            
            // Initial check
            if (window.scrollY > 300) {
                backToTopButton.classList.add('visible');
            }
        }
    });
})(); 