/**
 * Admin scripts for the Budhilaw Blog theme.
 */
jQuery(document).ready(function($) {
    // Tabs functionality
    $('.nav-tab-wrapper .nav-tab').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');
        
        // Hide all sections
        $('.tab-content').hide();
        
        // Show the target section
        $(target).show();
        
        // Update active tab
        $('.nav-tab-wrapper .nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        
        // Save the active tab in localStorage
        localStorage.setItem('budhilaw_blog_active_tab', target);
    });
    
    // Check if there's a saved tab
    var activeTab = localStorage.getItem('budhilaw_blog_active_tab');
    if (activeTab) {
        $('.nav-tab-wrapper .nav-tab[href="' + activeTab + '"]').trigger('click');
    } else {
        // Default to first tab
        $('.nav-tab-wrapper .nav-tab:first').trigger('click');
    }
    
    // Enhance the sidebar disable feature
    $('#disable_sidebar_slugs').on('focus', function() {
        $(this).attr('rows', 8); // Expand textarea when focused
    }).on('blur', function() {
        if (!$(this).val()) {
            $(this).attr('rows', 4); // Shrink back if empty
        }
    }).on('input', function() {
        // Update preview based on content
        if ($(this).val().trim()) {
            // If there's content, show no-sidebar preview
            $('.preview-content').css('flex', '0 0 80%');
            $('.preview-sidebar').css('opacity', '0.3').fadeOut(300);
            $('.preview-description').html(
                'With sidebar disabled, content expands to full width. <strong>These pages will have no sidebar:</strong> ' + 
                $(this).val().split('\n').filter(Boolean).map(function(slug) {
                    return '<code>' + slug.trim() + '</code>';
                }).join(', ')
            );
        } else {
            // If empty, show default preview
            $('.preview-content').css('flex', '3');
            $('.preview-sidebar').css('opacity', '1').fadeIn(300);
            $('.preview-description').text('When you disable the sidebar for a page, the content will expand to use the full width of the container.');
        }
    });
    
    // Add a button to get current page slug
    if ($('#disable_sidebar_slugs').length) {
        $('<button type="button" class="button button-secondary" id="get_current_slug">Get Current Page Slug</button>')
            .insertAfter('#disable_sidebar_slugs')
            .on('click', function(e) {
                e.preventDefault();
                // This is just a placeholder - in a real implementation, you would
                // use an AJAX call to get the current page's slug from the server
                alert('To get a page slug:\n1. Visit the page on your site\n2. Look at the URL - the part after your domain and before any query parameters is usually the slug\n3. For example, in https://example.com/about-us/, the slug is "about-us"');
            });
    }
}); 