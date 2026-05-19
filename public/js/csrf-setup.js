(function () {
    var token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        var csrf = token.getAttribute('content');
        document.addEventListener('DOMContentLoaded', function () {
            if (window.jQuery) {
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                    },
                });
            }

            if (window.axios) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;
            }
        });
    }
})();
