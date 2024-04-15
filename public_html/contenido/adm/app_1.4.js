/*
 *  Document   : app.js
 *  Author     : pixelcave
 */
var App = function() {
    var e, a, t, s, i, l, o, n = function() {
        e = $("#page-container"), a = $("#page-content"), t = $("header"), s = $("#page-content + footer"), i = $("#sidebar"), l = $("#sidebar-alt"), o = $(".sidebar-scroll"), d("init"), r(), v(), p(), g(), b(), $(window).resize(function() {
            b()
        }), $(window).bind("orientationchange", b);
        var n = $("#year-copy"), c = new Date;
        n.html(2014 === c.getFullYear() ? "2014" : "2014-" + c.getFullYear().toString().substr(2, 2)), u(), $('[data-toggle="tabs"] a, .enable-tabs a').click(function(e) {
            e.preventDefault(), $(this).tab("show")
        }), $('[data-toggle="tooltip"], .enable-tooltip').tooltip({container: "body", animation: !1}), $('[data-toggle="popover"], .enable-popover').popover({container: "body", animation: !0}), $('[data-toggle="lightbox-image"]').magnificPopup({type: "image", image: {titleSrc: "title"}}), $('[data-toggle="lightbox-gallery"]').magnificPopup({delegate: "a.gallery-link", type: "image", gallery: {enabled: !0, navigateByImgClick: !0, arrowMarkup: '<button type="button" class="mfp-arrow mfp-arrow-%dir%" title="%title%"></button>', tPrev: "Previous", tNext: "Next", tCounter: '<span class="mfp-counter">%curr% of %total%</span>'}, image: {titleSrc: "title"}}), $(".textarea-editor").wysihtml5(), $(".select-chosen").chosen({width: "100%"}), $(".select-select2").select2(), $(".input-slider").slider(), $(".input-tags").tagsInput({width: "auto", height: "auto"}), $(".input-datepicker, .input-daterange").datepicker({weekStart: 1}), $(".input-datepicker-close").datepicker({weekStart: 1}).on("changeDate", function() {
            $(this).datepicker("hide")
        }), $(".input-timepicker").timepicker({minuteStep: 1, showSeconds: !0, showMeridian: !0}), $(".input-timepicker24").timepicker({minuteStep: 1, showSeconds: !0, showMeridian: !1}), $(".pie-chart").easyPieChart({barColor: $(this).data("bar-color") ? $(this).data("bar-color") : "#777777", trackColor: $(this).data("track-color") ? $(this).data("track-color") : "#eeeeee", lineWidth: $(this).data("line-width") ? $(this).data("line-width") : 3, size: $(this).data("size") ? $(this).data("size") : "80", animate: 800, scaleColor: !1}), $("input, textarea").placeholder()
    }, r = function() {
        var e = 250, a = 250, t = ($(".sidebar-nav a"), $(".sidebar-nav-menu")), s = $(".sidebar-nav-submenu");
        t.click(function() {
            var t = $(this);
            return t.parent().hasClass("active") !== !0 && (t.hasClass("open") ? (t.removeClass("open").next().slideUp(e), setTimeout(b, e)) : ($(".sidebar-nav-menu.open").removeClass("open").next().slideUp(e), t.addClass("open").next().slideDown(a), setTimeout(b, e > a ? e : a))), !1
        }), s.click(function() {
            var t = $(this);
            return t.parent().hasClass("active") !== !0 && (t.hasClass("open") ? (t.removeClass("open").next().slideUp(e), setTimeout(b, e)) : (t.closest("ul").find(".sidebar-nav-submenu.open").removeClass("open").next().slideUp(e), t.addClass("open").next().slideDown(a), setTimeout(b, e > a ? e : a))), !1
        })
    }, d = function(a, s) {
        if ("init" === a)
            (t.hasClass("navbar-fixed-top") || t.hasClass("navbar-fixed-bottom")) && d("sidebar-scroll"), $(".sidebar-partial #sidebar").mouseenter(function() {
                d("close-sidebar-alt")
            }), $(".sidebar-alt-partial #sidebar-alt").mouseenter(function() {
                d("close-sidebar")
            });
        else {
            var i = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            "toggle-sidebar" === a ? i > 991 ? (e.toggleClass("sidebar-visible-lg"), e.hasClass("sidebar-visible-lg") && d("close-sidebar-alt"), "toggle-other" === s && (e.hasClass("sidebar-visible-lg") || d("open-sidebar-alt"))) : (e.toggleClass("sidebar-visible-xs"), e.hasClass("sidebar-visible-xs") && d("close-sidebar-alt")) : "toggle-sidebar-alt" === a ? i > 991 ? (e.toggleClass("sidebar-alt-visible-lg"), e.hasClass("sidebar-alt-visible-lg") && d("close-sidebar"), "toggle-other" === s && (e.hasClass("sidebar-alt-visible-lg") || d("open-sidebar"))) : (e.toggleClass("sidebar-alt-visible-xs"), e.hasClass("sidebar-alt-visible-xs") && d("close-sidebar")) : "open-sidebar" === a ? (e.addClass(i > 991 ? "sidebar-visible-lg" : "sidebar-visible-xs"), d("close-sidebar-alt")) : "open-sidebar-alt" === a ? (e.addClass(i > 991 ? "sidebar-alt-visible-lg" : "sidebar-alt-visible-xs"), d("close-sidebar")) : "close-sidebar" === a ? e.removeClass(i > 991 ? "sidebar-visible-lg" : "sidebar-visible-xs") : "close-sidebar-alt" === a ? e.removeClass(i > 991 ? "sidebar-alt-visible-lg" : "sidebar-alt-visible-xs") : "sidebar-scroll" == a && o.length && !o.parent(".slimScrollDiv").length && (o.slimScroll({height: $(window).height(), color: "#fff", size: "3px", touchScrollStep: 100}), $(window).resize(c), $(window).bind("orientationchange", h))
        }
        return!1
    }, c = function() {
        o.add(o.parent()).css("height", $(window).height())
    }, h = function() {
        setTimeout(o.add(o.parent()).css("height", $(window).height()), 500)
    }, b = function() {
        var o = $(window).height(), n = i.outerHeight(), r = l.outerHeight(), d = t.outerHeight(), c = s.outerHeight();
        t.hasClass("navbar-fixed-top") || t.hasClass("navbar-fixed-bottom") || o > n && o > r ? e.hasClass("footer-fixed") ? a.css("min-height", o - d + "px") : a.css("min-height", o - (d + c) + "px") : e.hasClass("footer-fixed") ? a.css("min-height", (n > r ? n : r) - d + "px") : a.css("min-height", (n > r ? n : r) - (d + c) + "px")
    }, v = function() {
        $('[data-toggle="block-toggle-content"]').on("click", function() {
            var e = $(this).closest(".block").find(".block-content");
            $(this).hasClass("active") ? e.slideDown() : e.slideUp(), $(this).toggleClass("active")
        }), $('[data-toggle="block-toggle-fullscreen"]').on("click", function() {
            var e = $(this).closest(".block");
            $(this).hasClass("active") ? e.removeClass("block-fullscreen") : e.addClass("block-fullscreen"), $(this).toggleClass("active")
        }), $('[data-toggle="block-hide"]').on("click", function() {
            $(this).closest(".block").fadeOut()
        })
    }, p = function() {
        var e = $("#to-top"), a = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        $(window).scroll(function() {
            $(this).scrollTop() > 150 && a > 991 ? e.fadeIn(100) : e.fadeOut(100)
        }), e.click(function() {
            return $("html, body").animate({scrollTop: 0}, 400), !1
        })
    }, u = function() {
        var e = $(".chat-users"), a = $(".chat-talk"), t = $(".chat-talk-messages"), s = $("#sidebar-chat-message"), i = "";
        $(".chat-talk-messages").slimScroll({height: 210, color: "#fff", size: "3px", position: "left", touchScrollStep: 100}), $("a", e).click(function() {
            return e.slideUp(), a.slideDown(), s.focus(), !1
        }), $("#chat-talk-close-btn").click(function() {
            return a.slideUp(), e.slideDown(), !1
        }), $("#sidebar-chat-form").submit(function(e) {
            i = s.val(), i && (t.append('<li class="chat-talk-msg chat-talk-msg-highlight themed-border animation-slideLeft">' + $("<div />").text(i).html() + "</li>"), t.animate({scrollTop: t[0].scrollHeight}, 500), s.val("")), e.preventDefault()
        })
    }, g = function() {
        var a, s = $(".sidebar-themes"), i = $("#theme-link");
        i.length && (a = i.attr("href"), $("li", s).removeClass("active"), $('a[data-theme="' + a + '"]', s).parent("li").addClass("active")), $("a", s).click(function() {
            a = $(this).data("theme"), $("li", s).removeClass("active"), $(this).parent("li").addClass("active"), "default" === a ? i.length && (i.remove(), i = $("#theme-link")) : i.length ? i.attr("href", a) : ($('link[href="css/themes_1.3.css"]').before('<link id="theme-link" rel="stylesheet" href="' + a + '">'), i = $("#theme-link"))
        }), $(".dropdown-options a").click(function(e) {
            e.stopPropagation()
        });
        var l = $("#options-main-style"), o = $("#options-main-style-alt");
        e.hasClass("style-alt") ? o.addClass("active") : l.addClass("active"), l.click(function() {
            e.removeClass("style-alt"), $(this).addClass("active"), o.removeClass("active")
        }), o.click(function() {
            e.addClass("style-alt"), $(this).addClass("active"), l.removeClass("active")
        });
        var n = $("#options-header-default"), r = $("#options-header-inverse"), c = $("#options-header-top"), h = $("#options-header-bottom");
        t.hasClass("navbar-default") ? n.addClass("active") : r.addClass("active"), t.hasClass("navbar-fixed-top") ? c.addClass("active") : t.hasClass("navbar-fixed-bottom") && h.addClass("active"), n.click(function() {
            t.removeClass("navbar-inverse").addClass("navbar-default"), $(this).addClass("active"), r.removeClass("active")
        }), r.click(function() {
            t.removeClass("navbar-default").addClass("navbar-inverse"), $(this).addClass("active"), n.removeClass("active")
        }), c.click(function() {
            e.removeClass("header-fixed-bottom").addClass("header-fixed-top"), t.removeClass("navbar-fixed-bottom").addClass("navbar-fixed-top"), $(this).addClass("active"), h.removeClass("active"), d("sidebar-scroll"), b()
        }), h.click(function() {
            e.removeClass("header-fixed-top").addClass("header-fixed-bottom"), t.removeClass("navbar-fixed-top").addClass("navbar-fixed-bottom"), $(this).addClass("active"), c.removeClass("active"), d("sidebar-scroll"), b()
        });
        var v = $("#options-footer-static"), p = $("#options-footer-fixed");
        e.hasClass("footer-fixed") ? p.addClass("active") : v.addClass("active"), v.click(function() {
            e.removeClass("footer-fixed"), $(this).addClass("active"), p.removeClass("active"), b()
        }), p.click(function() {
            e.addClass("footer-fixed"), $(this).addClass("active"), v.removeClass("active"), b()
        })
    }, f = function() {
        $.extend(!0, $.fn.dataTable.defaults, {sDom: "<'row'<'col-sm-6 col-xs-5'l><'col-sm-6 col-xs-7'f>r>t<'row'<'col-sm-5 hidden-xs'i><'col-sm-7 col-xs-12 clearfix'p>>", sPaginationType: "bootstrap", oLanguage: {sLengthMenu: "_MENU_", sSearch: '<div class="input-group">_INPUT_<span class="input-group-addon"><i class="fa fa-search"></i></span></div>', sInfo: "<strong>_START_</strong>-<strong>_END_</strong> of <strong>_TOTAL_</strong>", oPaginate: {sPrevious: "", sNext: ""}}})
    };
    return{init: function() {
            n()
        }, sidebar: function(e, a) {
            d(e, a)
        }, datatables: function() {
            f()
        }}
}();
$(function() {
    App.init()
});