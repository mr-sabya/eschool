document.addEventListener('livewire:navigated', () => {


    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function () {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("scrollUpBtn").style.display = "block";
        } else {
            document.getElementById("scrollUpBtn").style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }

    // scroll to top button
    document.getElementById("scrollUpBtn").addEventListener("click", topFunction);

    $('.slider').slick({
        dots: true,
        arrows: true,
        infinite: true,
        speed: 500,
        fade: false,
        cssEase: '',
        autoplay: true,
        autoplaySpeed: 2000,
        appendDots: $('#custom-dots'),
        appendArrows: $('#custom-arrows'), // Append buttons here
        prevArrow: '<button type="button" class="slick-prev"><i class="ri-arrow-left-s-line"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="ri-arrow-right-s-line"></i></button>',

    });

    $('.quote-slider').slick({
        dots: false,
        arrows: true,
        infinite: true,
        speed: 500,
        fade: false,
        cssEase: 'linear',
        autoplay: false,
        autoplaySpeed: 2000,
        slidesToShow: 2,
        slidesToScroll: 1,
        appendArrows: $('#quote_slider_arrow'), // Append buttons here
        prevArrow: '<button type="button" class="slick-prev"><i class="ri-arrow-left-s-line"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="ri-arrow-right-s-line"></i></button>',
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    $('#menu_button').click(function () {
        $('#main_menu').toggleClass('show');
    });

});