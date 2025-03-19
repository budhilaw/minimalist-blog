/**
 * Budhilaw Blog - Script for thumbnail positioning
 * 
 * Ensures correct thumbnail display based on theme options
 */
(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Apply thumbnail position styling
        $('.has-thumbnail-beside .post-wrapper').css({
            'display': 'flex',
            'flex-direction': 'row',
            'gap': '1.5rem',
            'align-items': 'flex-start'
        });
        
        $('.post-wrapper.has-beside-layout').css({
            'display': 'flex',
            'flex-direction': 'row'
        });
        
        $('.post-wrapper.has-top-layout').css({
            'display': 'flex',
            'flex-direction': 'column'
        });
        
        // Process all thumbnails to respect size settings
        $('.post-thumbnail img').each(function() {
            // Get the size class from the image
            var sizeClass = '';
            if ($(this).hasClass('thumbnail-thumbnail')) sizeClass = 'thumbnail';
            else if ($(this).hasClass('thumbnail-medium')) sizeClass = 'medium';
            else if ($(this).hasClass('thumbnail-medium_large')) sizeClass = 'medium_large';
            else if ($(this).hasClass('thumbnail-large')) sizeClass = 'large';
            else if ($(this).hasClass('thumbnail-post-thumbnail')) sizeClass = 'post-thumbnail';
            
            // Apply different max-width based on size
            var maxWidth = '100%';
            switch(sizeClass) {
                case 'thumbnail':
                    maxWidth = '150px';
                    break;
                case 'medium':
                    maxWidth = '300px';
                    break;
                case 'medium_large':
                    maxWidth = '768px';
                    break;
                case 'large':
                    maxWidth = '1024px';
                    break;
                case 'post-thumbnail':
                    maxWidth = '1200px';
                    break;
            }
            
            // Apply CSS to ensure correct size
            if ($(this).closest('.thumbnail-beside').length) {
                // Beside layout - don't constrain the width as it's controlled by the parent
                $(this).css({
                    'width': '100%',
                    'height': 'auto',
                    'display': 'block'
                });
            } else {
                // Top layout - apply max-width based on size
                $(this).css({
                    'width': '100%',
                    'max-width': maxWidth,
                    'height': 'auto',
                    'display': 'block',
                    'margin': '0 auto'
                });
            }
        });
    });
    
})(jQuery); 