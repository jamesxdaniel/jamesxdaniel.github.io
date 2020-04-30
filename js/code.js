$(document).ready(function(){

    var window_width = $(window).width();

    $('.hamburger').click(function(){
        $(this).toggleClass("fixed");
        $('.hamburger_inner').toggleClass("active");
        $('.nav_con').toggleClass("show");
    });
    
    $('.nav_con').animate("show");

    // new WOW().init();

    $('a[href*="#"]').on('click', function(e) {
        e.preventDefault()
        $('html, body').animate({
            scrollTop: $($(this).attr('href')).offset().top,
        }, 300, 'linear'
    )});

    $('.scrollToContent').click(function() {
        $('html, body').animate({
            scrollTop: $("#content1").offset().top,
        }, 300, 'linear'
    )});

    // $(window).scroll(function(){
    //     var fromtop = $(document).scrollTop();       // pixels from top of screen
    //     $('.arrow_dwn').css({opacity: 300 - fromtop}); // use a better formula for better fading
    //     $('.hamburger_con').css({opacity: 0.5 - fromtop});
    // });

    // $(window).scroll(function() {

    //     if ($(this).scrollTop() > 0) {
    //         if(window_width <= 800){
    //             $('.hamburger_con').css('opacity', 0.5);
    //         }
    //         else {
    //             $('.hamburger_con').css('opacity', 1);
    //         }
    //     }
    //     else {
    //         $('.hamburger_con').css('opacity', 1);
    //     }

    // });
    

    $(".btns").click(function(){
        $.ajax({url: "index.html", success: function(result){
            $("#home").html(result);
        }});
    });

    $(window).scroll(function() { 
        if ($(this).scrollTop() > 600) {
            $('.scrollToTop').css('opacity', 1);

            if ($(window).scrollTop() + $(window).height() > $(document).height() - 80) {
                $('.scrollToTop').css('color', 'red');
            } else {
                $('.scrollToTop').css('color', 'black');
            }

        } else {
            $('.scrollToTop').css('opacity', 0);
        }
    });

    $('.scrollToTop').click(function() {
        $('html, body').animate({
            scrollTop: $("#home-2").offset().top,
        }, 300, 'linear'
    )});

    var distance = $('nav.theme-2').offset().top,
        $window = $(window);

    $window.scroll(function() {
        if ( $window.scrollTop() >= distance ) {
            $('nav.theme-2').addClass("nvfix");
        }
        else {
            $('nav.theme-2').removeClass("nvfix");
        }
    });

        // Cache selectors
        var lastId,
        topMenu = $("#top-menu"),
        topMenuHeight = topMenu.outerHeight()+0,

        // All list items
        menuItems = topMenu.find("a"),

        // Anchors corresponding to menu items
        scrollItems = menuItems.map(function(){
            var item = $($(this).attr("href"));
            if (item.length) { return item; }
        });

        // Bind click handler to menu items
        // so we can get a fancy scroll animation
        menuItems.click(function(e){
            var href = $(this).attr("href"),
            offsetTop = href === "#" ? 0 : $(href).offset().top;

                $('html, body').stop().animate({ 
                    scrollTop: offsetTop
                }, 300);

            e.preventDefault();
        });

        // Bind to scroll
        $(window).scroll(function(){

        // Get container scroll position
        var fromTop = $(this).scrollTop()+topMenuHeight;

        // Get id of current scroll item
        var cur = scrollItems.map(function(){
            if ($(this).offset().top < fromTop)
            return this;
        });

        // Get the id of the current element
        cur = cur[cur.length-1];

        var id = cur && cur.length ? cur[0].id : "";

            if (lastId !== id) {
                lastId = id;
                // Set/remove active class
                menuItems
                    .parent().removeClass("current")
                    .end().filter("[href='#"+id+"']").parent().addClass("current");
            }

        });
    
    console.clear();

    const typing = document.querySelectorAll('.typewriter');
    
    function type(element) {
    
    function randomOpacity() {
        return (Math.floor(Math.random() * 50) + 50)/100;
    }
    
    function randomEms() {
        if (Math.random() > .8) {
        return (Math.floor(Math.random() * 100) - 50)/800;
        }
        else {
        return 0;
        }
    }
    
    function wrap(char) {
        return '<span style="opacity:' + randomOpacity() + '; text-shadow:' + randomEms() + 'em ' + randomEms() + 'em currentColor;">' + char + '</span>';
    }
    
    const wrappedText = Array.from(element.textContent).map(wrap);
    
    element.innerHTML = wrappedText.join('');
        
    }
    
    typing.forEach(type);

});