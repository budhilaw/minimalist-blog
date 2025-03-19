/**
 * Check if sidebar is empty and add appropriate class to body
 */
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.widget-area');
    
    // If sidebar exists and has no widgets (empty or only contains empty elements)
    if (sidebar) {
        // Check if the sidebar has any visible widgets
        const widgets = sidebar.querySelectorAll('.widget');
        let hasVisibleWidgets = false;
        
        widgets.forEach(function(widget) {
            // Check if widget has any content (not just whitespace)
            if (widget.textContent.trim().length > 0) {
                hasVisibleWidgets = true;
            }
        });
        
        // If no visible widgets found, add class to body
        if (!hasVisibleWidgets || widgets.length === 0) {
            document.body.classList.add('no-sidebar');
            sidebar.style.display = 'none'; // Hide the empty sidebar
        }
    } else {
        // No sidebar element found, add the class anyway
        document.body.classList.add('no-sidebar');
    }
}); 