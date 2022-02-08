
$(document).ready(function () {
    var countPublic = 3;
    var page = 1;
    var lengthPublications = $('.publication-mobile').length;
    var publics = $('.publication-mobile');
    showPublication();
    $('.nav__link').on('click', function(event) {
        event.preventDefault();
        var id = event.target.dataset.href;
        var top = $(id).offset().top - parseInt($('header').innerHeight());
        $('body,html').animate({scrollTop: top - 100}, 600);
    });
    $('.issues__slick-slider').slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        adaptiveHeight: true
    });
    $('.publications-slider-desktop').slick({
        infinite: false,
        dots: true,
        speed: 300,
        slidesToShow: 2,
        adaptiveHeight: true
    });
    function showPublication() {
        for(var j = 0; j < publics.length; j++) {
            publics[j].style.display = 'none'
        }
        for(var i = 0; i < page * countPublic; i++) {
            if (publics[i]) {
                publics[i].style.display = 'block';
            }
        }
    }

    if (page * countPublic >= lengthPublications) {
        $('.btn-next').css('display','none');
    }

    $('.btn-next').on('click', function(event) {
        event.preventDefault();
        page += 1;
        showPublication();
        if (page * countPublic >= lengthPublications) {
            $('.btn-next').css('display','none');
        }
    });
    $('.header__item-mobile').on('click', function(event) {
        event.preventDefault();
        $('.nav-mobile').css('display','flex');
        $('.background-mobile').css('display','block');
    });
    $('.nav__link-mobile').on('click', function(event) {
        event.preventDefault();
        $('.nav-mobile').css('display','none');
        $('.background-mobile').css('display','none');
    });
    $('.menu-cross').on('click', function(event) {
        event.preventDefault();
        $('.nav-mobile').css('display','none');
        $('.background-mobile').css('display','none');
    });
    
    function validation(...arg) {
        var req_rules = {
            phone: /^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/,
            email: /^[a-zA-Z0-9._\-]+@\w+-{0,1}\w+-{0,1}\w+.\w{2,5}$/
        };
        var valid = 0;
        arg.forEach(element => {
            var val = element.val(),
                name = element.attr('name');
            if(req_rules[name].test(val)) {
                element.removeClass('input_invalid');
            }
            else {
                element.addClass('input_invalid');
                valid++;
            }
        });
        if(valid == 0) {
            valid = 0;
            return true;
        } else {
            valid = 0;
            return false;
        }
    }
    $('.form').submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();
        if(validation($('.input-phone'), $('.input-email')) == true) {
            $.ajax({
                type: 'POST',
                url: '/send-email',
                data: data,
                success: function () { 
                    $('.block-modal-user').text($('#user-name').val());
                    console.log(form.children('#user-name').val());
                    $('.modal').css('display','flex');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log('error');
                }
            });
        }
    });
    $('.js-close-modal').on('click', function(e) {
        e.preventDefault();
        $('.modal').css('display','none');
    })
})