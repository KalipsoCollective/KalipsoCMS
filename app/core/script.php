<?php
$directory = $this->currentDirectory;

if (defined('INLINE_JS')) { ?>
<script>
<?php } else {
    http('content_type', 'js');
} ?>
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
            $('[data-toggle="dropdown"]').dropdown();
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

        // Variable definitions
        let xhr = true;
        let form = $(this)
        let formId = form.attr('id')
        let method = form.attr('method')
        let action = form.attr('action')
        let formData = new FormData($('#' + formId)[0])
        let formSubmitButton = $('#' + formId + ' button[type="submit"]')
        let formSubmitButtonContent = formSubmitButton.html()

        // Append loader SVG
        if (form.find('.section-loader-spinner').length === 0) {
            form.append('<div class="section-loader-spinner">' +
                '<svg width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">' +
                '<circle fill="none" stroke-width="4" stroke-linecap="round" cx="33" cy="33" r="30" class="circle">'+
                '</circle></svg></div>')
        }

        if (xhr) {

            NProgress.start();

            formSubmitButton.prop('disabled', true).html('<?php echo lang('sending'); ?>')
            form.addClass('section-loader-active')

            $.ajax({
                type: method,
                url: '<?php echo base(); ?>async/' + action,
                dataType: "json",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                complete: function(response){
                    setTimeout(() => {
                        if (typeof response.responseJSON === 'object') {
                            let data = response.responseJSON;
                            xhrCompleted(data, formId);

                        } else {
                            xhrProblem(formId);
                        }
                        formSubmitButton.prop("disabled", false).html(formSubmitButtonContent);
                        $("#"+formId).removeClass('section-loader-active');
                        NProgress.done();
                    }, 1000);
                }
            });
        }
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

    function xhrProblem(form = false) {

        let b;

        if (form) {
            b = {
                tryAgain: {
                    text: '<?php echo lang('try_again'); ?>',
                    btnClass: 'btn-red',
                    action: function(){
                        setTimeout(() => {
                            $('#'+form).submit();
                        }, 1500);
                    }
                },
                close: {
                    text: '<?php echo lang('close'); ?>',
                    action: function(){

                    }
                },
            }
        } else {
            b = {
                close: {
                    text: '<?php echo lang('close'); ?>',
                    action: function(){

                    }
                },
            }
        }
        $.confirm({
            icon: 'mdi mdi-alert',
            title: '<?php echo lang('error'); ?>',
            content: '<?php echo lang('a_problem_occurred'); ?>',
            type: 'red',
            typeAnimated: true,
            buttons: b
        });
    }

    function xhrCompleted(data, form = null) {
        if (data.modal_close !== undefined && data.modal_close !== null) {
            $(data.modal_close).modal('hide');
        }

        if (form !== false && data.form_reset === true) {
            document.getElementById(form).reset();
        }

        if (data.message !== "" && data.status) {
            $("#"+form+" .form-info").fadeIn( "slow", function() {
                $(this).html(data.message);
            });
        } else if (data.message !== "" && ! data.status) {
            $.confirm({
                icon: 'mdi mdi-alert',
                title: '<?php echo lang('warning'); ?>',
                content: data.message,
                type: 'yellow',
                typeAnimated: true,
                buttons: {
                    close: {
                        text: '<?php echo lang('close'); ?>',
                        action: function(){
                        }
                    }
                }
            });
        }

        if (data.toast !== undefined) {
            let id = Math.floor(new Date().getTime() / 1000).toString();
            id = 't_' + id;
            let toast = data.toast.replace('[ID]', id);

            $('#toastArea').append(toast);
            $('.' + id).toast('show');
        }

        if (data.val !== undefined && typeof data.val !== 'undefined') {

            for (const [key, value] of Object.entries(data.val)) {
                $(key).val(value).trigger('change');
            }
        }

        if (data.html !== undefined && typeof data.html !== 'undefined') {

            for (const [key, value] of Object.entries(data.html)) {

                $(key).html(value);
            }
        }

        if (data.attr !== undefined && typeof data.attr !== 'undefined') {

            for (const [selector, attrs] of Object.entries(data.attr)) {

                for (const [attr, value] of Object.entries(attrs)) {

                    if (attr === 'disabled') $(selector).prop(attr, value);
                    else $(selector).attr(attr, value);
                }
            }
        }

        if (data.add_class !== undefined && typeof data.add_class !== 'undefined') {

            for (const [key, value] of Object.entries(data.add_class)) {
                $(key).addClass(value);
            }
        }

        if (data.emergency !== undefined && window.emergency_alert === undefined) {

            let b = {};

            if (data.emergency.href_button) {

                b['hey'] = {
                    text: data.emergency.href_text,
                    btnClass: 'btn-orange',
                    action: function(){
                        location.href = data.emergency.href_target;
                    }
                }

            }

            if (data.emergency.close_button) {

                b['close'] = {
                    text: data.emergency.close_text,
                    action: function(){
                        setTimeout(() => {
                            window.emergency_alert = undefined
                        }, 300000)
                    }
                }

            }

            window.emergency_alert = true
            $.confirm({
                icon: data.emergency.icon,
                title: data.emergency.title,
                content: data.emergency.message,
                type: data.emergency.color,
                typeAnimated: true,
                buttons: b
            });

        }

        if (data.refresh !== undefined) { // Page Refresh or Redirect
            let second = (1000 * parseInt(data.refresh[0]) ); // Second to millisecond
            let url = data.refresh[1];
            let real =  data.refresh[2];

            window.refreshVal = setTimeout( () => {

                if (url != null) {

                    if (real) {
                        location.href = url;
                    } else {
                        $.pjax({ url: data.refresh[1], container: '#wrap' });
                    }

                } else {

                    if (real) {
                        location.reload();
                    } else {
                        $.pjax.reload({ container: '#wrap' });
                    }

                }
            }, second);
        }
    }
<?php
if (defined('INLINE_JS')) { ?>
</script>
<?php } ?>