<?php
if (defined('INLINE_JS')) { ?>
<script>
<?php } ?>
    NProgress.start();
    (function(a) {
        "use strict";
        let c = a(window), d = document.documentElement;
        d.setAttribute("data-useragent", navigator.userAgent);
        let f = function() {
            c.on("load", function() {
                a("html, body").animate({
                    scrollTop: 0
                }, "normal");
                initialize();
                NProgress.done();
                // Async page change init
                $(document).pjax('a:not([target="_blank"])', '#wrap');
                $(document).on('submit', 'form[data-async]', function(event) {
                    $.pjax.submit(event, '#wrap')
                })
            })
        };
        (function() {
            f()
        })()
    })(jQuery);

    function initialize(popstate = false) {

        $(function () {
            // for login page
            $('#username').keypress(function(event) {
                if (event.keyCode == 13 || event.which == 13) {
                    $('#password').focus();
                    event.preventDefault();
                }
            });
            // For password preview
            $('.password-secret a').on('click', function(){
                let $t = $(this);
                if ($('.password-secret input').attr('type') == 'password') {
                    $('.password-secret input').attr('type', 'text');
                    $t.find('i').addClass('text-danger mdi-eye-off').removeClass('mdi-eye');
                } else {
                    $('.password-secret input').attr('type', 'password');
                    $t.find('i').removeClass('text-danger mdi-eye-off').addClass('mdi-eye');
                }
            })

            // Bootstrap functions
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="popover"]').popover({container: 'body'});

            $('.custom-file-input').on('change',function(){
                var fileName = $(this).val();
                $(this).next('.custom-file-label').html(fileName.substring(fileName.lastIndexOf("\\") + 1, fileName.length));
            });
        });
    }

    $(document).on('pjax:send', function() {

        if (window.refreshVal !== undefined) {
            clearTimeout(window.refreshVal);
            window.refreshVal = undefined;
        }

        NProgress.start();
    })

    $(document).on('pjax:complete', function(e) {
        initialize();
        NProgress.done();
    })

    $(document).on('pjax:popstate', function(e) {
        initialize(true);
    })

    $(document).on('pjax:error', function(xhr, textStatus, error, options) {
        console.log(xhr, textStatus, error, options)
    })
<?php
if (defined('INLINE_JS')) { ?>
</script>
<?php } ?>