<?php
/**
 * Force Twemoji Override
 * 
 * Loads Twemoji library to replace native emojis with consistent SVG versions.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Twemoji script to page head
 */
function use_twemoji() {
    ?>
    <script type="text/javascript">
    !function(window, document) {
        // Normalize CSS content values like quoted strings and escaped unicode.
        function decodeCssContent(content) {
            if (!content || content === 'none' || content === 'normal') {
                return '';
            }

            if ((content.charAt(0) === '"' && content.charAt(content.length - 1) === '"') ||
                (content.charAt(0) === '\'' && content.charAt(content.length - 1) === '\'')) {
                content = content.slice(1, -1);
            }

            return content
                .replace(/\\([0-9a-fA-F]{1,6})\s?/g, function(match, codePoint) {
                    return String.fromCodePoint(parseInt(codePoint, 16));
                })
                .replace(/\\A/g, '\n')
                .replace(/\\([\\"'])/g, '$1');
        }

        function hasEmoji(text) {
            return /[\u00A9\u00AE\u203C-\u3299\u{1F000}-\u{1FAFF}]/u.test(text);
        }

        // Hide the original pseudo-element content once a real DOM fallback has been injected.
        function ensurePseudoEmojiBridgeStyle() {
            if (document.getElementById('loopis-twemoji-pseudo-style')) {
                return;
            }

            var style = document.createElement('style');
            style.id = 'loopis-twemoji-pseudo-style';
            style.textContent = '' +
                '[data-loopis-twemoji-before]::before { content: none !important; }' +
                '[data-loopis-twemoji-after]::after { content: none !important; }' +
                '.loopis-twemoji-pseudo { display: inline; }';
            document.head.appendChild(style);
        }

        // Copy emoji from ::before/::after into the DOM so Twemoji can parse it.
        function injectPseudoEmoji(element, pseudo) {
            var dataKey = pseudo === '::before' ? 'loopisTwemojiBefore' : 'loopisTwemojiAfter';
            var attrName = pseudo === '::before' ? 'data-loopis-twemoji-before' : 'data-loopis-twemoji-after';
            var markerClass = pseudo === '::before' ? 'loopis-twemoji-pseudo-before' : 'loopis-twemoji-pseudo-after';
            var existing = element.querySelector(':scope > .' + markerClass);
            var content = decodeCssContent(window.getComputedStyle(element, pseudo).content);

            if (!hasEmoji(content)) {
                if (existing) {
                    existing.remove();
                }
                element.removeAttribute(attrName);
                delete element.dataset[dataKey];
                return;
            }

            if (element.dataset[dataKey] === content && existing) {
                return;
            }

            if (!existing) {
                existing = document.createElement('span');
                existing.className = 'loopis-twemoji-pseudo ' + markerClass;
                existing.setAttribute('aria-hidden', 'true');
            }

            existing.textContent = content;

            if (pseudo === '::before') {
                element.insertBefore(existing, element.firstChild);
            } else {
                element.appendChild(existing);
            }

            element.dataset[dataKey] = content;
            element.setAttribute(attrName, '1');
        }

        // Scan a subtree for pseudo-element emojis before running the normal Twemoji pass.
        function bridgePseudoElementEmoji(root) {
            var scope = root && root.nodeType === 1 ? root : (document.body || document.documentElement);
            var elements = [scope].concat(Array.prototype.slice.call(scope.querySelectorAll('*')));

            ensurePseudoEmojiBridgeStyle();

            elements.forEach(function(element) {
                injectPseudoEmoji(element, '::before');
                injectPseudoEmoji(element, '::after');
            });
        }

        // Twemoji only sees DOM nodes, so bridge pseudo-element content first.
        function parseWithTwemoji(root) {
            var target = root && root.nodeType === 1 ? root : (document.body || document.documentElement);

            bridgePseudoElementEmoji(target);
            twemoji.parse(target, {
                folder: 'svg',
                ext: '.svg'
            });
        }

        // Immediate emoji replacement based on wp-emoji-loader.min.js
        function loadTwemojiSync() {
            var script = document.createElement('script');
            script.src = 'https://unpkg.com/twemoji@latest/dist/twemoji.min.js';
            script.onload = function() {
                // Parse immediately when loaded
                parseWithTwemoji(document.body || document.documentElement);
                
                // Set up observer for dynamic content
                if (window.MutationObserver) {
                    var observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            mutation.addedNodes.forEach(function(node) {
                                if (node.nodeType === 1) {
                                    parseWithTwemoji(node);
                                }
                            });
                        });
                    });
                    
                    observer.observe(document.body || document.documentElement, {
                        childList: true,
                        subtree: true
                    });
                }
            };
            document.head.appendChild(script);
        }
        
        // Load immediately - don't wait for DOMContentLoaded
        if (document.head) {
            loadTwemojiSync();
        } else {
            // Fallback if head doesn't exist yet
            document.addEventListener('DOMContentLoaded', loadTwemojiSync);
        }
        
    }(window, document);
    </script>
    <?php
}

// Add Twemoji to frontend
add_action('wp_head', 'use_twemoji');   