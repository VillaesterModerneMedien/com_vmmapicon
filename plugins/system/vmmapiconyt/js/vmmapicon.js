/**
 * VMMapicon Customizer JavaScript
 */

(function ($, UIkit) {
    'use strict';

    // Add customizer functionality for VMMapicon API sources
    $(document).ready(function() {

        // Listen for customizer changes
        UIkit.util.on('#customizer', 'change', function(e) {
            var target = e.target;

            // Handle VMMapicon source changes
            if (target.name && target.name.indexOf('vmmapicon') !== -1) {
                console.log('VMMapicon customizer changed:', target.name, target.value);

                // Trigger update of dynamic content
                if (window.YOOtheme && window.YOOtheme.builder) {
                    window.YOOtheme.builder.update();
                }
            }
        });

        console.log('VMMapicon customizer JavaScript loaded');
    });

})(jQuery, UIkit);
