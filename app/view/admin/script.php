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
                if (event.keyCode === 13 || event.which === 13) {
                    $('#password').focus();
                    event.preventDefault();
                }
            });
            // For password preview
            $('.password-secret a').on('click', function(){
                let $t = $(this);
                if ($('.password-secret input').attr('type') === 'password') {
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
                let fileName = $(this).val();
                $(this)
                    .next('.custom-file-label')
                    .html(fileName.substring(fileName.lastIndexOf("\\") + 1, fileName.length));
            });

            if (popstate) {
                console.log("It's popstate!")
            }
        });
    }

    $(document).on('submit', 'form.section-loader', function(){

        NProgress.start();
        let form = $(this)
        let formId = form.attr('id')
        let formSubmitButton = $(this).find('button[type="submit"]')
        let formSubmitButtonContent = formSubmitButton.html()


        // Append loader SVG
        if (form.find('.section-loader-spinner').length === 0) {
            form.append('<div class="section-loader-spinner">' +
                '<svg width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">' +
                '<circle fill="none" stroke-width="4" stroke-linecap="round" cx="33" cy="33" r="30" class="circle">'+
                '</circle></svg></div>')
        }

        formSubmitButton.html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true">'+
            '</span><span class="sr-only"><?php echo lang('loading'); ?></span>').prop('disabled', true);
        form.addClass('section-loader-active')

        setTimeout(() => {
            formSubmitButton.html(formSubmitButtonContent).prop('disabled', false);
            form.removeClass('section-loader-active');
            NProgress.done();
        }, 755000)

        console.log(form);
        console.log(formId);
        console.log(formSubmitButton);

    });

    $(document).on('pjax:send', function() {

        if (window.refreshVal !== undefined) {
            clearTimeout(window.refreshVal);
            window.refreshVal = undefined;
        }

        NProgress.start();
    })

    $(document).on('pjax:complete', function() {
        initialize();
        NProgress.done();
    })

    $(document).on('pjax:popstate', function() {
        initialize(true);
    })

    $(document).on('pjax:error', function(xhr, textStatus, error, options) {
        console.log(xhr, textStatus, error, options)
    })
<?php
if (defined('INLINE_JS')) { ?>
</script>
<?php } ?>