! function( e ) {
    "use strict";

    Codevz_Plus.r360degree = function() {

        var t = function(e) {
            this.element = e, this.handleContainer = this.element.find(".cz_product-viewer-handle"), this.handleFill = this.handleContainer.children(".fill"), this.handle = this.handleContainer.children(".handle"), this.imageWrapper = this.element.find(".product-viewer"), this.slideShow = this.imageWrapper.children(".product-sprite"), this.frames = this.element.data("frame"), this.friction = this.element.data("friction"), this.action = this.element.data("action"), this.visibleFrame = 0, this.loaded = !1, this.animating = !1, this.xPosition = 0, this.loadFrames()
        };

        function a(e, t) {
            e.css({
                "-moz-transform": t,
                "-webkit-transform": t,
                "-ms-transform": t,
                "-o-transform": t,
                transform: t
            })
        }(t.prototype.loadFrames = function() {
            var t = this,
                a = this.slideShow.data("image"),
                o = e("<img/>");
            this.loading("0.5"), o.load(function() {
                e(this).remove(), t.loaded = !0
            }), setTimeout(function() {
                o.attr("src", a)
            }, 50)
        }, t.prototype.loading = function(e) {
            var t = this;
            a(this.handleFill, "scaleX(" + e + ")"), setTimeout(function() {
                if (t.loaded) t.element.addClass("loaded"), a(t.handleFill, "scaleX(1)"), t.dragImage(), t.handle && t.dragHandle();
                else {
                    var o = parseFloat(e) + .1;
                    o < 1 && t.loading(o)
                }
            }, 500)
        }, t.prototype.dragHandle = function() {
            var e = this;
            e.handle.on("mousedown vmousedown touchstart", function(t) {
                e.handle.addClass("cz_draggable");
                var a = e.handle.outerWidth(),
                    o = e.handleContainer.offset().left,
                    n = e.handleContainer.outerWidth(),
                    i = o - a / 2,
                    s = o + n - a / 2,
                    r = "touchstart" == t.type ? t.originalEvent.touches[0].pageX : t.pageX;
                e.xPosition = e.handle.offset().left + a - r, e.element.on("mousemove vmousemove touchmove", function(t) {
                    e.animating || (e.animating = !0, window.requestAnimationFrame ? requestAnimationFrame(function() {
                        e.animateDraggedHandle(t, a, o, n, i, s)
                    }) : setTimeout(function() {
                        e.animateDraggedHandle(t, a, o, n, i, s)
                    }, 100))
                }).one("mouseup vmouseup touchend", function(t) {
                    e.handle.removeClass("cz_draggable"), e.element.off("mousemove vmousemove touchmove")
                }), t.preventDefault()
            }).on("mouseup vmouseup touchend", function(t) {
                e.handle.removeClass("cz_draggable")
            })
        }, t.prototype.animateDraggedHandle = function(t, a, o, n, i, s) {
            var r = this,
                m = ("touchmove" == t.type ? t.originalEvent.touches[0].pageX : t.pageX) + r.xPosition - a;
            m < i ? m = i : m > s && (m = s);
            var l = Math.ceil(1e3 * (m + a / 2 - o) / n) / 10;
            r.visibleFrame = Math.ceil(l * (r.frames - 1) / 100), r.updateFrame(), e(".cz_draggable", r.handleContainer).css("left", l + "%").one("mouseup vmouseup touchend", function() {
                e(this).removeClass("cz_draggable")
            }), r.animating = !1
        }, t.prototype.dragImage = function() {
            var e, t = this,
                a = "";
            "drag" == t.action ? (e = "mousedown vmousedown touchstart", a = "mouseup vmouseup touchend") : (e = "mouseenter vmouseenter touchstart", a = "mouseout vmouseout touchstart"), t.slideShow.on(e, function(e) {
                t.slideShow.addClass("cz_draggable");
                var o = t.imageWrapper.offset().left,
                    n = t.imageWrapper.outerWidth(),
                    i = (t.frames, "touchmove" == e.type ? e.originalEvent.touches[0].pageX : e.pageX);
                t.xPosition = i, t.element.on("mousemove vmousemove touchmove", function(e) {
                    t.animating || (t.animating = !0, window.requestAnimationFrame ? requestAnimationFrame(function() {
                        t.animateDraggedImage(e, o, n)
                    }) : setTimeout(function() {
                        t.animateDraggedImage(e, o, n)
                    }, 100))
                }).one(a, function(e) {
                    t.slideShow.removeClass("cz_draggable"), t.element.off("mousemove vmousemove touchmove"), t.updateHandle()
                }), e.preventDefault()
            }).on(a, function(e) {
                t.slideShow.removeClass("cz_draggable")
            })
        }, t.prototype.animateDraggedImage = function(e, t, a) {
            var o = this,
                n = "touchmove" == e.type ? e.originalEvent.touches[0].pageX : e.pageX,
                i = o.xPosition - n,
                s = Math.ceil(100 * i / (a * o.friction)) * (o.frames - 1) / 100;
            s = s > 0 ? Math.floor(s) : Math.ceil(s);
            var r = o.visibleFrame + s;
            r < 0 ? r = o.frames - 1 : r > o.frames - 1 && (r = 0), r != o.visibleFrame && (o.visibleFrame = r, o.updateFrame(), o.xPosition = n), o.animating = !1
        }, t.prototype.updateHandle = function() {
            if (this.handle) {
                var e = 100 * this.visibleFrame / this.frames;
                this.handle.animate({
                    left: e + "%"
                }, 200)
            }
        }, t.prototype.updateFrame = function() {
            var e = -100 * this.visibleFrame / this.frames;
            a(this.slideShow, "translateX(" + e + "%)")
        }, e(".cz_product-viewer-wrapper").length) && e(".cz_product-viewer-wrapper").each(function() {
            new t(e(this))
        })
    };

    Codevz_Plus.r360degree();

}( jQuery );