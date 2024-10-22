const elysiumcapital = {
    init: function () {
        this.variables();
        this.addEventListeners();
    },
    variables: function () {
        this.headerSection = $('.headerSection');
        this.scrollLink = $('.scroll').not('.link');
    },
    addEventListeners: function () {
        $(window).on('scroll', function () {
            this.headerFixedOnScroll();
        }.bind(this));
        this.scrollLink.on('click', function (e) {
            e.preventDefault();
            this.smoothScrolling(e);
        }.bind(this));
    },
    headerFixedOnScroll: function () {
        if ($(window).scrollTop() >= 1) {
            // this.headerSection.addClass('fixed-header');
            // $('.headerSection .container').removeClass('border-bottom');
        } else {
            // this.headerSection.addClass('fixed-header');
            // $('.headerSection .container').addClass('border-bottom');
        }
    },
    smoothScrolling: function (e) {
        $('body, html').animate({
            scrollTop: $(e.currentTarget.hash).offset().top - 75
        }, 1000);
    }
};

$(document).ready(function() {
    elysiumcapital.init();
});