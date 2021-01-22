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

        // Variable definitions
        let form = this
        let formId = form.getAttribute('id')
        let method = form.getAttribute('method')
        let action = form.getAttribute('action')
        let formData = new FormData(document.querySelector("#" + formId));
        let formSubmitButton = this.querySelector('button[type="submit"]')

        // Append loader SVG
        if (!!form.querySelectorAll('.section-loader-spinner')) {
            form.innerHTML += '<div class="section-loader-spinner">' +
                '<svg width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">' +
                '<circle fill="none" stroke-width="4" stroke-linecap="round" cx="33" cy="33" r="30" class="circle">'+
                '</circle></svg></div>'

        }

        formSubmitButton.disabled = true
        form.classList.add('section-loader-active')

        setTimeout(() => {
            formSubmitButton.disabled = false;
            form.classList.remove('section-loader-active')
            NProgress.done();
        }, 1500)

        let request = new XMLHttpRequest();
        request.open(method, '<?php echo base(); ?>async/' + action, true);
        request.setRequestHeader("Content-type", "application/x-form-urlencoded");
        request.onload = function() {
            if (typeof this.status !== "undefined") {
                xhrCompleted(this);
            }
        };
        request.onerror = function() {
            xhrProblem(this);
        };
        request.send(formData);

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

    function xhrProblem(xhr) {
        console.log('error', xhr)
    }

    function xhrCompleted(xhr) {
        console.log('completed', xhr)
    }
<?php
if (defined('INLINE_JS')) { ?>
</script>
<?php } ?>