
jQuery(function () {
    'use strict';

    var body = jQuery('body');

    body.on('searchinit', 'form', function (e) {
        var request;
        var timeout;
        var form = jQuery(e.target);
        var all_selector = 'input, select';

        // Set up options.
        var options = form.data('search-options');
        var defaults = {
            result_blocks: {
                main_section: {
                    result_selector : '#resources-grid',
                    target : '#resources-grid'
                }
            },
            rebind: false
        };

        options = jQuery.extend(true, defaults, options);

        var elements = {
            inputs: jQuery(),
            submit: jQuery()
        };

        var collect_all_elements = function () {
            elements.inputs = jQuery(all_selector);
            elements.submit = form.find('button[type="submit"]');
        };

        var set_previous_values = function () {
            elements.inputs.each(function () {
                var input = jQuery(this);
                input.data('previous-value', get_current_value(input));
            });
        };

        var get_current_value = function(input) {
            if (input.is('input[type="checkbox"]:not(:checked)')) {
                return '';
            } else if(!(input.is('input[type="checkbox"]:checked'))) {
                return input.val();
            } else {
                return (input.val() || '');
            }
        };

        var start_search = function () {
            // Cancel previous timeout.
            clearTimeout(timeout);

            // Store previous values for all inputs.
            set_previous_values();

            // Cancel previous unfinished request.
            if (request) {
                request.abort();
            }

            timeout = setTimeout(function () {
                elements.submit.trigger('loadingstart');

                var parameters = {};

                form.serializeArray().forEach(function( item){
                    parameters[item.name] = item.value;
                });

                // Construct url.
                var form_url = form.attr('action');
                var url = buildUrl(form_url, {
                    queryParams: parameters
                });

                if ('replaceState' in window.history) {
                    window.history.replaceState(window.history.state, window.title, url);
                }

                // Send request.
                request = jQuery.ajax({
                    url: url,
                    success: function (response) {
                        form.trigger('searchresponse', response);
                        form.trigger('searchend');
                    }
                });
            }, 200);
        };

        var stop_search = function () {
            elements.submit.trigger('loadingend');
        };

        var start_search_if_value_changed = function () {
            var input = jQuery(this);

            if (get_current_value(input) === input.data('previous-value'))
            {
                return;
            }

            form.trigger('searchstart');
        };


        form.on('searchresponse', function (e, response) {
            response = jQuery('<div />').append(response);

            // For each result block find its content in response and copy it
            // to its target container.

            for (var key in options.result_blocks)
            {
                if (options.result_blocks.hasOwnProperty(key))
                {
                    var block = options.result_blocks[key];
                    var content = response.find(block.result_selector).first().html();

                    jQuery(block.target).html(content);
                    jQuery(block.target).trigger('contentloaded');
                }
            }

            if (options.rebind) {
                collect_all_elements();
            }
        });

        form.on('change keyup', all_selector, start_search_if_value_changed);
        form.on('searchstart', start_search);
        form.on('searchend', stop_search);

        collect_all_elements();
        set_previous_values();
    });

    jQuery('.view-index form.search').trigger('searchinit');
});
