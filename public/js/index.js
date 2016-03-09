(function ($) {

    $(function () {

        var SELECTORS = {
            CSRF_TOKEN: 'meta[name="csrf-token"]',
            BUTTON: '#twitter_reach button[type="submit"]',
            URL: 'input[name=url]',
            LOADING_PANEL: '.loading-panel',
            SUCCESS: 'h4.success',
            ERROR: '#errors'
        };
        var loadingPanel = $(SELECTORS.LOADING_PANEL);
        var successNotification = $(SELECTORS.SUCCESS);
        var errorNotification = $(SELECTORS.ERROR);

        function init() {
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $(SELECTORS.CSRF_TOKEN).attr('content')}});
            $(SELECTORS.BUTTON).on('click', geTwitterReach);
        }

        function geTwitterReach(e) {
            hideNotifications();
            var url = $(SELECTORS.URL).val();

            $.ajax({
                url: "/reach",
                method: "POST",
                data: {
                    url: url
                },
                context: document.body
            }).done(handleSucces).fail(handleError);

            e.preventDefault();
        }

        function handleSucces(data) {
            loadingPanel.addClass('hidden');
            if (data && data.reach) {
                successNotification.find('span').text(data.reach);
                successNotification.removeClass('hidden');
            }
        }

        function handleError(data) {
            loadingPanel.addClass('hidden');

            if (data && data.responseJSON && data.responseJSON.error) {
                errorNotification.text(data.responseJSON.error);
            }
            errorNotification.removeClass('hidden');
        }

        function hideNotifications() {
            successNotification.addClass('hidden');
            loadingPanel.removeClass('hidden');
            errorNotification.addClass('hidden');
        }

        init();

    });

})(jQuery);