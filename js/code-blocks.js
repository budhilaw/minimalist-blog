/**
 * Code Block Enhancements for Budhilaw Blog Theme
 */
(function($) {
    'use strict';

    /**
     * Initialize code block enhancements when DOM is ready
     */
    $(document).ready(function() {
        // Wait for Prism to be fully loaded with all dependencies
        var prismCheckInterval = setInterval(function() {
            // Check if Prism is loaded with all required components
            if (typeof Prism !== 'undefined' && 
                Prism.languages && 
                Prism.languages.markup && 
                Prism.languages.php) {
                clearInterval(prismCheckInterval);
                initializeCodeBlocks();
            }
        }, 100);
        
        // Fallback - if after 3 seconds Prism isn't loaded, try anyway
        setTimeout(function() {
            clearInterval(prismCheckInterval);
            if (typeof Prism !== 'undefined') {
                initializeCodeBlocks();
            } else {
                console.error('PrismJS failed to load properly');
            }
        }, 3000);
        
        // Add observer for dynamically loaded content (like AJAX comments)
        const contentObserver = new MutationObserver(processNewContent);
        contentObserver.observe(document.body, { childList: true, subtree: true });
    });

    /**
     * Initialize all code blocks on the page
     */
    function initializeCodeBlocks() {
        // Process all code blocks
        const codeBlocks = document.querySelectorAll('pre code');
        
        if (codeBlocks.length) {
            // Configure autoloader to use CDN for missing languages
            if (typeof Prism !== 'undefined' && Prism.plugins && Prism.plugins.autoloader) {
                // Set the correct CDN path
                Prism.plugins.autoloader.languages_path = 'https://cdn.jsdelivr.net/npm/prismjs@1.30.0/components/';
                
                // Add custom handling for failed loads
                const originalLoadLanguages = Prism.plugins.autoloader.loadLanguages;
                Prism.plugins.autoloader.loadLanguages = function(languages, success, error) {
                    return originalLoadLanguages.call(this, languages, success, function(lang) {
                        console.warn('Failed to load language: ' + lang + '. Falling back to plain text.');
                        if (typeof error === 'function') {
                            error.call(this, lang);
                        }
                    });
                };
            }
            
            // First ensure all language classes are properly assigned
            codeBlocks.forEach(function(codeBlock) {
                enhanceCodeBlock(codeBlock);
            });
            
            // Highlight all code blocks
            try {
                Prism.highlightAll();
                
                // Additionally highlight each block individually
                // (sometimes highlightAll misses some)
                setTimeout(function() {
                    codeBlocks.forEach(function(codeBlock) {
                        if (!codeBlock.querySelector('.token')) {
                            try {
                                const lang = getCodeBlockLanguage(codeBlock);
                                if (lang && !Prism.languages[lang]) {
                                    // Try to load the language if it doesn't exist
                                    codeBlock.classList.add('language-text');
                                    codeBlock.parentElement.setAttribute('data-language', 'Plain Text');
                                }
                                Prism.highlightElement(codeBlock);
                            } catch (e) {
                                console.warn('Error highlighting block:', e);
                            }
                        }
                    });
                }, 500);
            } catch (e) {
                console.error('Error during syntax highlighting:', e);
            }
        }
    }

    /**
     * Get language from code block
     */
    function getCodeBlockLanguage(codeBlock) {
        const classes = codeBlock.className.split(' ');
        for (let i = 0; i < classes.length; i++) {
            if (classes[i].startsWith('language-')) {
                return classes[i].replace('language-', '');
            }
        }
        return null;
    }

    /**
     * Process and enhance a single code block
     */
    function enhanceCodeBlock(codeBlock) {
        if (!codeBlock || !codeBlock.parentElement || codeBlock.closest('.processed-code-block')) {
            return; // Already processed
        }
        
        const preElement = codeBlock.parentElement;
        if (!preElement || preElement.tagName !== 'PRE') {
            return;
        }
        
        // Mark as processed to avoid duplicate processing
        codeBlock.classList.add('processed-code-block');
        
        // Add line numbers
        if (!preElement.classList.contains('line-numbers')) {
            preElement.classList.add('line-numbers');
        }
        
        // Add language detection
        detectLanguage(preElement, codeBlock);
        
        // Add copy button if not already present
        addCopyButton(preElement);
    }

    /**
     * Add a copy button to the code block
     */
    function addCopyButton(preElement) {
        // Check if button already exists
        if (preElement.querySelector('.copy-to-clipboard-button')) {
            return;
        }
        
        const copyButton = document.createElement('button');
        copyButton.className = 'copy-to-clipboard-button';
        copyButton.textContent = 'Copy';
        
        copyButton.addEventListener('click', function() {
            const codeElement = preElement.querySelector('code');
            const codeToCopy = codeElement.textContent;
            
            navigator.clipboard.writeText(codeToCopy)
                .then(function() {
                    // Success feedback
                    copyButton.textContent = 'Copied!';
                    copyButton.classList.add('success');
                    
                    setTimeout(function() {
                        copyButton.textContent = 'Copy';
                        copyButton.classList.remove('success');
                    }, 2000);
                })
                .catch(function(err) {
                    // Error feedback
                    copyButton.textContent = 'Failed!';
                    
                    setTimeout(function() {
                        copyButton.textContent = 'Copy';
                    }, 2000);
                    
                    console.error('Could not copy code: ', err);
                });
        });
        
        preElement.appendChild(copyButton);
    }

    /**
     * Detect language class and set data attribute
     */
    function detectLanguage(preElement, codeElement) {
        let language = '';
        
        // Check for language classes
        const classes = codeElement.className.split(' ');
        for (let i = 0; i < classes.length; i++) {
            if (classes[i].startsWith('language-')) {
                language = classes[i].replace('language-', '');
                break;
            }
        }
        
        // Support for WP code blocks
        if (!language && preElement.className) {
            const wpLanguage = preElement.className.match(/lang-(.*?)($|\s)/);
            if (wpLanguage && wpLanguage[1]) {
                language = wpLanguage[1];
                codeElement.classList.add('language-' + language);
            }
        }
        
        // Try to auto-detect language based on content if still not found
        if (!language) {
            const codeContent = codeElement.textContent.trim();
            
            // Detect PHP
            if (codeContent.startsWith('<?php') || codeContent.startsWith('<?=')) {
                language = 'php';
                codeElement.classList.add('language-php');
            }
            // Detect Rust
            else if (codeContent.includes('fn main') || 
                    (codeContent.includes('pub fn') && codeContent.includes('impl')) ||
                    (codeContent.includes('let mut') && codeContent.includes('::')) ||
                    codeContent.includes('extern crate')) {
                language = 'rust';
                codeElement.classList.add('language-rust');
            }
            // Detect Go
            else if (codeContent.includes('package main') || 
                    (codeContent.includes('func ') && codeContent.includes('import')) ||
                    codeContent.includes('go func(') ||
                    (codeContent.includes('type ') && codeContent.includes(' struct {'))) {
                language = 'go';
                codeElement.classList.add('language-go');
            }
            // Detect Python
            else if (codeContent.includes('def ') ||
                    codeContent.includes('import ') && 
                    (codeContent.includes(':') || codeContent.includes('if __name__ == "__main__"')) ||
                    codeContent.includes('class ') && codeContent.includes(':')) {
                language = 'python';
                codeElement.classList.add('language-python');
            }
            // Detect Nix
            else if (codeContent.includes('{ pkgs ? import') || 
                    codeContent.includes('stdenv.mkDerivation') ||
                    (codeContent.includes('{ ') && codeContent.includes('}: '))) {
                language = 'nix';
                codeElement.classList.add('language-nix');
            }
            // Detect HTML
            else if (codeContent.startsWith('<!DOCTYPE html') || codeContent.startsWith('<html') || 
                   (codeContent.startsWith('<') && codeContent.includes('</') && !codeContent.includes('<?'))) {
                language = 'html';
                codeElement.classList.add('language-html');
            }
            // Detect JavaScript
            else if (codeContent.includes('function') && (codeContent.includes('var ') || 
                   codeContent.includes('let ') || codeContent.includes('const ') || 
                   codeContent.includes('return '))) {
                language = 'javascript';
                codeElement.classList.add('language-javascript');
            }
            // Detect CSS
            else if (codeContent.includes('{') && codeContent.includes('}') && 
                   (codeContent.includes(':') && codeContent.includes(';'))) {
                language = 'css';
                codeElement.classList.add('language-css');
            }
            // Detect SQL
            else if ((codeContent.toUpperCase().includes('SELECT') && codeContent.toUpperCase().includes('FROM')) ||
                   codeContent.toUpperCase().includes('INSERT INTO') || 
                   codeContent.toUpperCase().includes('CREATE TABLE')) {
                language = 'sql';
                codeElement.classList.add('language-sql');
            }
            // Detect Bash/Shell
            else if (codeContent.startsWith('#!') || codeContent.startsWith('$ ') || 
                   codeContent.includes(' && ') || codeContent.includes(' || ')) {
                language = 'bash';
                codeElement.classList.add('language-bash');
            }
        }
        
        // Use "text" as fallback if no language is detected
        if (!language) {
            language = 'text';
            codeElement.classList.add('language-text');
        }
        
        // Set pretty name for display
        let displayName = language.toUpperCase();
        
        // Special handling for common languages
        switch (language.toLowerCase()) {
            case 'js':
            case 'javascript':
                displayName = 'JavaScript';
                break;
            case 'ts':
            case 'typescript':
                displayName = 'TypeScript';
                break;
            case 'html':
                displayName = 'HTML';
                break;
            case 'css':
                displayName = 'CSS';
                break;
            case 'scss':
                displayName = 'SCSS';
                break;
            case 'php':
                displayName = 'PHP';
                break;
            case 'py':
            case 'python':
                displayName = 'Python';
                break;
            case 'rs':
            case 'rust':
                displayName = 'Rust';
                break;
            case 'go':
                displayName = 'Go';
                break;
            case 'nix':
                displayName = 'Nix';
                break;
            case 'java':
                displayName = 'Java';
                break;
            case 'json':
                displayName = 'JSON';
                break;
            case 'md':
            case 'markdown':
                displayName = 'Markdown';
                break;
            case 'sql':
                displayName = 'SQL';
                break;
            case 'bash':
            case 'shell':
                displayName = 'Shell';
                break;
            case 'text':
                displayName = 'Plain Text';
                break;
        }
        
        preElement.setAttribute('data-language', displayName);
    }

    /**
     * Process new content added to the DOM
     */
    function processNewContent(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && mutation.addedNodes.length) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        // Check for new code blocks in the added node
                        const codeBlocks = node.querySelectorAll('pre code');
                        if (codeBlocks.length) {
                            codeBlocks.forEach(function(codeBlock) {
                                enhanceCodeBlock(codeBlock);
                            });
                            
                            // Re-highlight the new code blocks
                            if (typeof Prism !== 'undefined') {
                                Prism.highlightElement(codeBlock);
                            }
                        }
                    }
                });
            }
        });
    }

})(jQuery); 