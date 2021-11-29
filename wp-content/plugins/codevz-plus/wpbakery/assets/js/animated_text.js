! function( s ) {
    "use strict";

    Codevz_Plus.animated_text = function() {
        var e = s(".cz_headline");
        if (e.length) {
            var i = e.data("time") || 3e3,
                a = i,
                t = i,
                n = t - 2e3,
                l = 50,
                d = 150,
                r = 500,
                o = r + 800,
                c = 600,
                h = i;
            e.length && (s(".cz_headline.letters").find("b").each(function(e) {
                var i = s(this),
                    a = i.text().split(""),
                    t = i.hasClass("is-visible");
                for (e in a) i.parents(".rotate-2").length > 0 && (a[e] = "<em>" + a[e] + "</em>"), a[e] = t ? '<i class="in">' + a[e] + "</i>" : "<i>" + a[e] + "</i>";
                var n = a.join("");
                i.html(n).css("opacity", 1)
            }), e.each(function() {
                var e = s(this),
                    i = a;
                if (e.hasClass("loading-bar")) i = t, setTimeout(function() {
                    e.find(".cz_words-wrapper").addClass("is-loading")
                }, n);
                else if (e.hasClass("clip")) {
                    var l = e.find(".cz_words-wrapper"),
                        d = l.width();
                    l.css("width", d)
                } else if (!e.hasClass("type")) {
                    var r = e.find(".cz_words-wrapper b"),
                        o = 0;
                    r.each(function() {
                        var e = s(this).width();
                        e > o && (o = e)
                    })
                }
                setTimeout(function() {
                    u(e.find(".is-visible").eq(0))
                }, i)
            }));

            function u(e) {
                var i = C(e);

    			// Switch classes
    			if ( ! i.closest( ".clip" ).length ) {

    				if ( i.closest( ".rotate-1" ).length ) {
    					setTimeout(function() {
    						i.parent().css( 'width', '' );
    					}, 750 );
    				}

    				i.addClass( 'is-visible' ).removeClass( 'is-hidden' ).siblings().addClass( 'is-hidden' ).removeClass( 'is-visible' );
    				
    				if ( i.closest( ".rotate-1" ).length ) {
    					setTimeout(function() {
    						i.parent().css( 'width', i.width() );
    					}, 1000 );
    				}
    				
    			}

                if (e.parents(".cz_headline").hasClass("type")) {
                    var h = e.parent(".cz_words-wrapper");
                    h.addClass("selected").removeClass("waiting"), setTimeout(function() {
                        h.removeClass("selected"), e.removeClass("is-visible").addClass("is-hidden").children("i").removeClass("in").addClass("out")
                    }, r), setTimeout(function() {
                        p(i, d)
                    }, o)
                } else if (e.parents(".cz_headline").hasClass("letters")) {
                    var v = e.children("i").length >= i.children("i").length;
                    ! function e(i, t, n, l) {

                        i.removeClass("in").addClass("out");
                        i.is(":last-child") ? n && setTimeout(function() {
                            u(C(t))
                        }, a) : setTimeout(function() {
                            e(i.next(), t, n, l)
                        }, l);
                        if (i.is(":last-child") && s("html").hasClass("no-csstransitions")) {
                            var d = C(t);
                            m(t, d)
                        }
                    }(e.find("i").eq(0), e, v, l), f(i.find("i").eq(0), i, v, l)
                } else e.parents(".cz_headline").hasClass("clip") ? e.parents(".cz_words-wrapper").animate({
                    width: "2px"
                }, c, function() {
                    m(e, i), p(i)
                }) : e.parents(".cz_headline").hasClass("loading-bar") ? (e.parents(".cz_words-wrapper").removeClass("is-loading"), m(e, i), setTimeout(function() {
                    u(i)
                }, t), setTimeout(function() {
                    e.parents(".cz_words-wrapper").addClass("is-loading")
                }, n)) : (m(e, i), setTimeout(function() {
                    u(i)
                }, a))
            }

            function p(s, e) {
                s.parents(".cz_headline").hasClass("type") ? (f(s.find("i").eq(0), s, !1, e), s.addClass("is-visible").removeClass("is-hidden")) : s.parents(".cz_headline").hasClass("clip") && s.parents(".cz_words-wrapper").animate({
                    width: s.width()
                }, c, function() {
                    setTimeout(function() {
                        u(s)
                    }, h)
                })
            }

            function f(s, e, i, t) {
                s.addClass("in").removeClass("out"), s.is(":last-child") ? (e.parents(".cz_headline").hasClass("type") && setTimeout(function() {
                    e.parents(".cz_words-wrapper").addClass("waiting")
                }, 200), i || setTimeout(function() {
                    u(e)
                }, a)) : setTimeout(function() {
                    f(s.next(), e, i, t)
                }, t)
            }

            function C(s) {
                return s.is(":last-child") ? s.parent().children().eq(0) : s.next()
            }

            function m(s, e) {
                s.removeClass("is-visible").addClass("is-hidden"), e.removeClass("is-hidden").addClass("is-visible");
            }
        }
    }, Codevz_Plus.animated_text()
}(jQuery);