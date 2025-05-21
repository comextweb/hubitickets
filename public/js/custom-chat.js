/*  jQuery Nice Select - v1.0
https://github.com/hernansartorio/jquery-nice-select
Made by Hernán Sartorio  */
!function (e) { e.fn.niceSelect = function (t) { function s(t) { t.after(e("<div></div>").addClass("nice-select").addClass(t.attr("class") || "").addClass(t.attr("disabled") ? "disabled" : "").attr("tabindex", t.attr("disabled") ? null : "0").html('<span class="current"></span><div class="list-wrp"><ul class="list"></ul></div>')); var s = t.next(), n = t.find("option"), i = t.find("option:selected"); s.find(".current").html(i.data("display") || i.text()), n.each(function (t) { var n = e(this), i = n.data("display"); s.find("ul").append(e("<li></li>").attr("data-value", n.val()).attr("data-display", i || null).addClass("option" + (n.is(":selected") ? " selected" : "") + (n.is(":disabled") ? " disabled" : "")).html(n.text())) }) } if ("string" == typeof t) return "update" == t ? this.each(function () { var t = e(this), n = e(this).next(".nice-select"), i = n.hasClass("open"); n.length && (n.remove(), s(t), i && t.next().trigger("click")) }) : "destroy" == t ? (this.each(function () { var t = e(this), s = e(this).next(".nice-select"); s.length && (s.remove(), t.css("display", "")) }), 0 == e(".nice-select").length && e(document).off(".nice_select")) : console.log('Method "' + t + '" does not exist.'), this; this.hide(), this.each(function () { var t = e(this); t.next().hasClass("nice-select") || s(t) }), e(document).off(".nice_select"), e(document).on("click.nice_select", ".nice-select", function (t) { var s = e(this); e(".nice-select").not(s).removeClass("open"), s.toggleClass("open"), s.hasClass("open") ? (s.find(".option"), s.find(".focus").removeClass("focus"), s.find(".selected").addClass("focus")) : s.focus() }), e(document).on("click.nice_select", function (t) { 0 === e(t.target).closest(".nice-select").length && e(".nice-select").removeClass("open").find(".option") }), e(document).on("click.nice_select", ".nice-select .option:not(.disabled)", function (t) { var s = e(this), n = s.closest(".nice-select"); n.find(".selected").removeClass("selected"), s.addClass("selected"); var i = s.data("display") || s.text(); n.find(".current").text(i), n.prev("select").val(s.data("value")).trigger("change") }), e(document).on("keydown.nice_select", ".nice-select", function (t) { var s = e(this), n = e(s.find(".focus") || s.find(".list .option.selected")); if (32 == t.keyCode || 13 == t.keyCode) return s.hasClass("open") ? n.trigger("click") : s.trigger("click"), !1; if (40 == t.keyCode) { if (s.hasClass("open")) { var i = n.nextAll(".option:not(.disabled)").first(); i.length > 0 && (s.find(".focus").removeClass("focus"), i.addClass("focus")) } else s.trigger("click"); return !1 } if (38 == t.keyCode) { if (s.hasClass("open")) { var l = n.prevAll(".option:not(.disabled)").first(); l.length > 0 && (s.find(".focus").removeClass("focus"), l.addClass("focus")) } else s.trigger("click"); return !1 } if (27 == t.keyCode) s.hasClass("open") && s.trigger("click"); else if (9 == t.keyCode && s.hasClass("open")) return !1 }); var n = document.createElement("a").style; return n.cssText = "pointer-events:auto", "auto" !== n.pointerEvents && e("html").addClass("no-csspointerevents"), this } }(jQuery);
$(document).ready(function () {
    /******  Nice Select  ******/
    $('select').niceSelect();
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            navItems.forEach(nav => nav.classList.remove('active'));
            item.classList.add('active');
        });
    });
    // dropdown-toggle
    $(".dropdown-toggle").click(function (e) {
        e.stopPropagation();
        const dropdownMenu = $(this).siblings(".dropdown-menu");
        $(".dropdown-menu").not(dropdownMenu).removeClass("show");
        dropdownMenu.toggleClass("show");
    });
    $(document).click(function () {
        $(".dropdown-menu").removeClass("show");
    });
    // chat-tabs-js
    // $("ul.chat-tabs li").click(function () {
    //     var $this = $(this);
    //     var $theTab = $this.attr("data-tab");
    //     if (!$this.hasClass("active")) {
    //         $this.closest(".chat-footer").find("ul.chat-tabs li, .chat-tab-content").removeClass("active");
    //         $this.addClass("active");
    //         $('#' + $theTab).addClass("active");
    //     }
    // });

    // $("ul.chat-tabs li").click(function () {
    //     var $this = $(this);
    //     var $theTab = $(this).attr("data-tab");
    //     if ($this.hasClass("active")) {
    //     } else {
    //         $this
    //             .closest(".tabs-wrapper")
    //             .find("ul.tabs li, .tabs-container .tab-content")
    //             .removeClass("active");
    //         $(
    //             '.tabs-container .tab-content[id="' +
    //             $theTab +
    //             '"], ul.tabs li[data-tab="' +
    //             $theTab +
    //             "]"
    //         ).addClass("active");
    //     }
    //     $(this).addClass("active");
    // });

    $(document).ready(function() {
        // Tab switcher
        $('.chat-tabs li').click(function() {
            var tabId = $(this).attr('data-tab'); // Get tab ID
            
            // Remove active class from all tabs and contents
            $('.chat-tabs li').removeClass('active');
            $('.tab-content').removeClass('active');
    
            // Add active class to clicked tab and corresponding content
            $(this).addClass('active');
            $('#' + tabId).addClass('active');
        });
    });
    

});