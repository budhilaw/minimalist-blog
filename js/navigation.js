/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens.
 */
(() => {
    // Wait for DOM to be fully loaded to ensure all elements are available
    document.addEventListener('DOMContentLoaded', () => {
        const siteNavigation = document.getElementById('site-navigation');
        const button = document.querySelector('.menu-toggle');
        const menuItems = document.querySelectorAll('.menu-item-has-children');
        const menuContainer = siteNavigation ? siteNavigation.querySelector('.menu-container') : null;

        // Return early if the navigation or button doesn't exist.
        if (!siteNavigation || !button) {
            return;
        }
        
        // CRITICAL FIX: Ensure menu is initially hidden on mobile
        if (menuContainer && window.innerWidth <= 767) {
            // Force menu to be hidden on mobile
            menuContainer.style.display = 'none';
            siteNavigation.classList.remove('toggled');
            button.setAttribute('aria-expanded', 'false');
        }
        
        // CRITICAL FIX: Ensure all submenus are hidden on page load
        menuItems.forEach((item) => {
            const submenu = item.querySelector('.sub-menu');
            if (submenu) {
                submenu.style.display = 'none';
                submenu.style.opacity = '0';
                submenu.style.visibility = 'hidden';
                submenu.style.maxHeight = '0';
                // Remove active class if present
                item.classList.remove('active');
            }
        });
        
        // Special debug function to help diagnose menu display issues
        function debugMenuState() {
            if (!menuContainer) return;
            
            const computedStyle = window.getComputedStyle(menuContainer);
            // Debugging code removed
        }
        
        // Run initial debug
        debugMenuState();

        // Force menu visibility - Fix for sticky mobile menu issues
        function checkMobileMenuVisibility() {
            if (siteNavigation.classList.contains('toggled') && window.innerWidth <= 767) {
                if (menuContainer) {
                    // Force display with inline style to override any CSS
                    menuContainer.style.display = 'block';
                    menuContainer.style.visibility = 'visible';
                    menuContainer.style.opacity = '1';
                    
                    // Debug after forcing visibility
                    debugMenuState();
                }
            }
        }

        // Add resize listener to ensure menu stays visible when toggled
        window.addEventListener('resize', checkMobileMenuVisibility);

        // Toggle the .toggled class and the aria-expanded value each time the button is clicked.
        button.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default button behavior
            
            // Toggle the navigation class
            siteNavigation.classList.toggle('toggled');
            
            // Update aria-expanded
            const isExpanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', !isExpanded);
            
            // CRITICAL FIX: Directly manipulate the menu container display
            if (menuContainer) {
                if (siteNavigation.classList.contains('toggled')) {
                    // This forces the menu to show
                    menuContainer.style.display = 'block';
                    menuContainer.style.visibility = 'visible';
                    menuContainer.style.opacity = '1';
                    menuContainer.style.maxHeight = 'calc(100vh - 70px)';
                } else {
                    // Hide the menu
                    menuContainer.style.display = 'none';
                }
            }
            
            // Force menu visibility after toggle
            checkMobileMenuVisibility();
            
            // Debug the current state
            debugMenuState();
            
            if (isExpanded) {
                // Close all open submenus when closing the main menu
                menuItems.forEach((item) => {
                    item.classList.remove('active');
                    const submenu = item.querySelector('.sub-menu');
                    if (submenu) {
                        submenu.style.maxHeight = null;
                    }
                });
            }
        });

        // Handle submenu toggling for touch/mobile devices
        menuItems.forEach((item) => {
            const link = item.querySelector('a');
            const submenu = item.querySelector('.sub-menu');
            
            // Add a click event to parent menu items
            if (link && submenu) {
                // Create a toggle button and append it to the link for better mobile UX
                const toggleBtn = document.createElement('span');
                toggleBtn.className = 'submenu-toggle';
                toggleBtn.setAttribute('aria-label', 'Toggle submenu');
                
                // Add event listener just to the toggle button
                toggleBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Toggle active class
                    item.classList.toggle('active');
                    
                    // Animate submenu height and visibility
                    if (item.classList.contains('active')) {
                        // First make it display block so the animation can work
                        submenu.style.display = 'block';
                        
                        // Use setTimeout to ensure the display change has taken effect
                        setTimeout(() => {
                            submenu.style.maxHeight = submenu.scrollHeight + 'px';
                            submenu.style.opacity = '1';
                            submenu.style.visibility = 'visible';
                        }, 10);
                    } else {
                        submenu.style.maxHeight = null;
                        submenu.style.opacity = '0';
                        
                        // Delay hiding to allow for animation
                        setTimeout(() => {
                            if (!item.classList.contains('active')) {
                                submenu.style.visibility = 'hidden';
                                submenu.style.display = 'none';
                            }
                        }, 300); // Match transition time
                    }
                });
                
                // Only append for mobile view using media query
                const mobileOnly = window.matchMedia('(max-width: 767px)');
                
                // Initial check
                if (mobileOnly.matches) {
                    // Only add the toggle if it doesn't already exist
                    if (!link.querySelector('.submenu-toggle')) {
                        link.appendChild(toggleBtn);
                    }
                }
                
                // Handle screen resize
                mobileOnly.addEventListener('change', (e) => {
                    if (e.matches) {
                        // Add toggle button when going to mobile
                        if (!link.querySelector('.submenu-toggle')) {
                            link.appendChild(toggleBtn);
                        }
                    } else {
                        // Remove toggle button when going to desktop
                        const existingBtn = link.querySelector('.submenu-toggle');
                        if (existingBtn) {
                            existingBtn.remove();
                        }
                        
                        // Reset styles for desktop
                        item.classList.remove('active');
                        submenu.style.maxHeight = null;
                    }
                });
            }
        });

        // Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
        document.addEventListener('click', (event) => {
            const isClickInside = siteNavigation.contains(event.target) || button.contains(event.target);

            if (!isClickInside) {
                siteNavigation.classList.remove('toggled');
                button.setAttribute('aria-expanded', 'false');
                
                // CRITICAL FIX: Ensure menu container is hidden on outside click for mobile
                if (menuContainer && window.innerWidth <= 767) {
                    menuContainer.style.display = 'none';
                    menuContainer.style.visibility = 'hidden';
                }
                
                // Close all open submenus
                menuItems.forEach((item) => {
                    item.classList.remove('active');
                    const submenu = item.querySelector('.sub-menu');
                    if (submenu) {
                        submenu.style.maxHeight = null;
                        submenu.style.opacity = '0';
                        submenu.style.visibility = 'hidden';
                        submenu.style.display = 'none';
                    }
                });
            }
        });

        // Handle Escape key to close the menu
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && siteNavigation.classList.contains('toggled')) {
                siteNavigation.classList.remove('toggled');
                button.setAttribute('aria-expanded', 'false');
                button.focus();
                
                // Ensure menu container is hidden on Escape for mobile
                if (menuContainer && window.innerWidth <= 767) {
                    menuContainer.style.display = 'none';
                    menuContainer.style.visibility = 'hidden';
                }
                
                // Close all open submenus
                menuItems.forEach((item) => {
                    item.classList.remove('active');
                    const submenu = item.querySelector('.sub-menu');
                    if (submenu) {
                        submenu.style.maxHeight = null;
                        submenu.style.opacity = '0';
                        submenu.style.visibility = 'hidden';
                        submenu.style.display = 'none';
                    }
                });
            }
        });

        // Helper function to reset any visible submenus
        function resetAllSubmenus() {
            document.querySelectorAll('.sub-menu').forEach(submenu => {
                submenu.style.display = 'none';
                submenu.style.opacity = '0';
                submenu.style.visibility = 'hidden';
                submenu.style.maxHeight = '0';
            });
            
            document.querySelectorAll('.menu-item-has-children').forEach(item => {
                item.classList.remove('active');
            });
        }
        
        // Run reset on page load
        resetAllSubmenus();
    });
})();

