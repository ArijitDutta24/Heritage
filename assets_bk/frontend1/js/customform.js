﻿/*
 *
 * Fancy fields 1.2
 * URI: http://www.jqfancyfields.com
 *
 * Date: July 07 2013
 *
 * Copyrights 2012 Gilad Korati & Matan Gottlieb
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 *
 */
var _mouseX = 0;
var _mouseY = 0;
var _ffIsMobile = (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent));
(function(f) {
    var m = null;
    var c = false;
    var l = false;
    var o = true;
    var a = false;
    var k = false;
    var i = false;
    var e;
    var q = "";
    var n;
    var p = {
        init: function(u) {
            if (o) {
                f(document).keydown(function(w) {
                    if (m != null) {
                        k = false;
                        var x = w.keyCode || w.which;
                        if (x == "38" || x == "104") {
                            k = true;
                            g();
                            w.preventDefault()
                        } else {
                            if (x == "40" || x == "98") {
                                k = true;
                                h();
                                w.preventDefault()
                            } else {
                                if (x == "13") {
                                    k = true;
                                    f(".on", m).click();
                                    w.preventDefault()
                                } else {
                                    if (x == "27") {
                                        k = true;
                                        m.closest(".ffSelectMenuWrapper").prev(".ffSelectButton").click();
                                        w.preventDefault()
                                    }
                                }
                            }
                        }
                    }
                });
                f(document).on("keypress", function(B) {
                    if (m != null) {
                        if (k) {
                            k = false;
                            return false
                        }
                        var C = B.keyCode || B.which;
                        var A = String.fromCharCode(B.keyCode | B.charCode);
                        var D = m.data("cts");
                        clearTimeout(e);
                        if (i && (q != A)) {
                            q = q + A
                        } else {
                            q = A
                        }
                        i = true;
                        e = setTimeout(function() {
                            i = false
                        }, D);
                        var z = q.toLowerCase();
                        var y = q.length;
                        if (f(".on SPAN", m).text().substring(0, y).toLowerCase() == z) {
                            if (y < 2) {
                                if (f(".on", m).next("LI").children("SPAN").text().substring(0, y).toLowerCase() == z) {
                                    f(".on", m).removeClass("on").next("LI").addClass("on");
                                    d(m)
                                } else {
                                    var x = f("LI", m).index(f(".on", m));
                                    var w = true;
                                    f("LI SPAN", m).slice(x + 1).each(function() {
                                        if (f(this).text().substring(0, y).toLowerCase() == z) {
                                            f(".on", m).removeClass("on");
                                            f(this).parent("LI").addClass("on");
                                            d(m);
                                            w = false;
                                            return false
                                        }
                                    });
                                    if (w) {
                                        f("LI SPAN", m).each(function() {
                                            if (f(this).text().substring(0, y).toLowerCase() == z) {
                                                f(".on", m).removeClass("on");
                                                f(this).parent("LI").addClass("on");
                                                d(m);
                                                return false
                                            }
                                        })
                                    }
                                }
                            }
                        } else {
                            f("LI SPAN", m).each(function() {
                                if (f(this).text().substring(0, y).toLowerCase() == z) {
                                    f(".on", m).removeClass("on");
                                    f(this).parent("LI").addClass("on");
                                    d(m);
                                    return false
                                }
                            })
                        }
                        B.preventDefault()
                    }
                });
                f(document).click(function(w) {
                    if (m != null) {
                        if (!c && !m.data("ds")) {
                            m.closest(".ffSelectMenuWrapper").prev(".ffSelectButton").click()
                        } else {
                            m.data("ds", false)
                        }
                        c = false
                    }
                });
                o = false
            }
            var v = f(this).length;
            var s = 1;
            var t = f("");
            return this.each(function() {
                var D = f(this);
                if ((!D.is("input")) && (!D.is("textarea")) && (!D.is("select"))) {
                    if (D.is("FORM")) {
                        D.prop("autocomplete", "off")
                    } else {
                        f("FORM", D).prop("autocomplete", "off")
                    }
                    t = t.add(f("INPUT,SELECT,TEXTAREA", D));
                    if (s == v) {
                        if ((typeof u != "undefined") && (typeof u.exclude != "undefined")) {
                            var w = u.exclude.split(",");
                            f.each(w, function(T, U) {
                                t = t.not(("" + U))
                            })
                        }
                        t.filter("INPUT,TEXTAREA").fancyfields(u);
                        t.filter("SELECT").fancyfields(u)
                    } else {
                        s++
                    }
                } else {
                    var P = f.extend({
                        enableOnClean: false,
                        cleanDisableOnClean: false,
                        cleanOnFocus: true,
                        appendInputClassToWrapper: false,
                        customScrollBar: false,
                        continueTypingSpees: 1000
                    }, u);
                    D.data("settings", P);
                    if (D.data("defaultSettings") == null) {
                        D.data("defaultSettings", P)
                    }
                    var A = null;
                    D.data("default", D.clone());
                    var O = ((D.prop("class") != null) && (P.appendInputClassToWrapper)) ? " " + D.prop("class") : "";
                    if (D.is(":text")) {
                        var S = D.val();
                        A = f('<div class="ffTextBoxWrapper' + O + '"></div>');
                        A.insertAfter(D).append(f('<div class="ffTextBoxRight"></div>').append(f('<div class="ffTextBoxLeft"></div>').append(D)));
                        b(D, A);
                        D.focusin(function() {
                            if (P.cleanOnFocus) {
                                if (S == D.val()) {
                                    D.val("")
                                }
                            }
                            A.addClass("focus")
                        });
                        D.focusout(function() {
                            if (P.cleanOnFocus) {
                                if (D.val() == "") {
                                    D.val(S)
                                }
                            }
                            A.removeClass("focus")
                        })
                    }
                    if (D.is(":password")) {
                        A = f('<div class="ffPasswordWrapper' + O + '"></div>');
                        A.insertAfter(D).append(f('<div class="ffPasswordRight"></div>').append(f('<div class="ffPasswordLeft"></div>').append(D)));
                        b(D, A);
                        D.focusin(function() {
                            A.addClass("focus")
                        });
                        D.focusout(function() {
                            A.removeClass("focus")
                        })
                    }
                    if (D.is("textarea")) {
                        var S = D.val();
                        A = f('<div class="ffTextAreaWrapper' + O + '"></div>');
                        A.append('<div class="ffTextAreaTop"><span></span></div>').insertAfter(D).append(f('<div class="ffTextAreaMid"></div>').append(f('<div class="ffTextAreaLeft"></div>').append(D))).append('<div class="ffTextAreaBottom"><span></span></div>');
                        if (navigator.appVersion.indexOf("MSIE 7.") != -1) {
                            var R = A.width();
                            f(".ffTextAreaTop", A).css("width", R);
                            f(".ffTextAreaBottom", A).css("width", R)
                        }
                        b(D, A);
                        D.focusin(function() {
                            if (P.cleanOnFocus) {
                                if (S == D.val()) {
                                    D.val("")
                                }
                            }
                            A.addClass("focus")
                        });
                        D.focusout(function() {
                            if (P.cleanOnFocus) {
                                if (D.val() == "") {
                                    D.val(S)
                                }
                            }
                            A.removeClass("focus")
                        })
                    }
                    if (D.is(":checkbox")) {
                        A = f('<div class="ffCheckboxWrapper' + O + '" ></div>');
                        var x = f('<div class="ffCheckbox"></div>');
                        if (D.is(":checked")) {
                            A.addClass("on")
                        }
                        b(D, A);
                        var G = null;
                        var N = D.next();
                        if (N.is("LABEL")) {
                            G = f('<a href="javascript:void(0)">' + N.text() + "</a>")
                        }
                        A.append(x).insertAfter(D).append(D.css("display", "none"));
                        if (G != null) {
                            r(D, x, G, N, A)
                        }
                        x.click(function() {
                            if (!A.hasClass("disabled")) {
                                $curField = f(this);
                                $curInput = $curField.siblings("input");
                                A.toggleClass("on");
                                var T = false;
                                if (!l) {
                                    if ($curInput.is(":checked")) {
                                        $curInput.prop("checked", false)
                                    } else {
                                        $curInput.prop("checked", true);
                                        T = true
                                    }
                                } else {
                                    if (!$curInput.is(":checked")) {
                                        T = true
                                    }
                                }
                                if (!l) {
                                    var U = P.onCheckboxChange;
                                    if (typeof U === "function") {
                                        U($curInput, T)
                                    }
                                }
                            } else {
                                if (l) {
                                    $curField = f(this);
                                    $curInput = $curField.siblings("input");
                                    if ($curInput.is(":checked")) {
                                        $curInput.prop("checked", false)
                                    } else {
                                        $curInput.prop("checked", true)
                                    }
                                }
                            }
                            l = false
                        });
                        D.click(function() {
                            l = true;
                            x.click()
                        })
                    }
                    if (D.is(":radio")) {
                        A = f('<div class="ffRadioWrapper' + O + '" ></div>');
                        var x = f('<div class="ffRadio"></div>');
                        if (D.is(":checked")) {
                            A.addClass("on")
                        }
                        b(D, A);
                        var G = null;
                        var N = D.next();
                        if (N.is("LABEL")) {
                            G = f('<a href="javascript:void(0)">' + N.text() + "</a>")
                        } else {}
                        A.append(x).insertAfter(D).append(D.css("display", "none"));
                        if (G != null) {
                            r(D, x, G, N, A)
                        }
                        x.click(function() {
                            var T = D.prop("name");
                            if (!A.hasClass("disabled")) {
                                if (D.is(":checked")) {} else {
                                    D.prop("checked", true);
                                    A.addClass("on");
                                    if (T != "") {
                                        f("input:radio").not(D).each(function() {
                                            if (f(this).prop("name") == T) {
                                                f(this).closest(".ffRadioWrapper").removeClass("on")
                                            }
                                        })
                                    }
                                    var U = P.onRadioChange;
                                    if (typeof U === "function") {
                                        U(D)
                                    }
                                }
                            } else {
                                if (l) {
                                    $curChecked = f("input[name=" + D.prop("name") + "]:checked");
                                    timer = setTimeout(function() {
                                        D.prop("checked", false);
                                        $curChecked.prop("checked", true)
                                    }, 1)
                                }
                            }
                            l = false
                        })
                    }
                    if (D.is("select")) {
                        A = f('<div class="ffSelectWrapper' + O + '" ></div>');
                        var x = f('<div class="ffSelect"></div>').css({
                            "z-index": 10,
                            position: "relative"
                        });
                        var B = f('<A href="javascript:void(0)" class="ffSelectButton"><span></span></A>');
                        if (D.prop("tabindex")) {
                            B.prop("tabindex", D.prop("tabindex"));
                            D.data("ti", D.prop("tabindex"))
                        }
                        b(D, A);
                        if (_ffIsMobile) {
                            A.append(x.append(B)).insertAfter(D.addClass("mobileSelect"));
                            x.append(D.css({
                                width: A.width(),
                                height: A.innerHeight()
                            }));
                            B.click(function() {
                                D.trigger("click")
                            });
                            f("span", B).text(f("option:selected", D).text());
                            D.change(function() {
                                var U = f("option:selected", D);
                                f("span", B).text(U.text());
                                var T = P.onSelectChange;
                                if (typeof T === "function") {
                                    T(D, U.text(), U.val())
                                }
                            })
                        } else {
                            var M = f('<div class="ffSelectMenuWrapper"><div class="ffSelectMenuTop"><span></span></div></div>').css("position", "absolute"),
                                E = f('<ul data-cts="' + P.continueTypingSpees + '" data-ds="' + false + '">');
                            var K, F = "",
                                Q = false,
                                y = "",
                                I = "";
                            $objOptions = f(">option,optgroup", D);
                            $objOptions.each(function() {
                                K = f(this);
                                if (K.prop("tagName") == "OPTION") {
                                    y = K.prop("class") ? ' class="' + K.prop("class") + '"' : "";
                                    if (K.prop("selected")) {
                                        Q = true;
                                        var T = f("span", B);
                                        T.text(K.text());
                                        if (y != "") {
                                            T.prepend(f("<i>").addClass(K.prop("class")).css("float", T.css("direction") == "rtl" ? "right" : "left"))
                                        }
                                        y = y == "" ? ' class="on selected"' : y.substring(0, y.length - 1) + ' on selected"'
                                    }
                                    if (K.prop("disabled")) {
                                        y = y == "" ? ' class="disabled"' : y.substring(0, y.length - 1) + ' disabled"'
                                    }
                                    F += "<li" + y + '><span data-val="' + K.val() + '"' + (K.prop("disabled") == "disabled" ? "data-dis='disabled'" : "") + ">" + K.text() + "</span></li>"
                                } else {
                                    I = K.prop("class") ? " " + K.prop("class") : K.prop("label") != "" ? " " + (K.prop("label").replace(/\s+/g, " ")) : "";
                                    F += '<li class="ffGroup disabled' + I + '"><span>' + K.prop("label") + "</span></li>";
                                    var U = f(">option", K);
                                    U.each(function() {
                                        K = f(this);
                                        y = K.prop("class") ? ' class="' + K.prop("class") + '"' : "";
                                        if (K.prop("selected")) {
                                            Q = true;
                                            var V = f("span", B);
                                            V.text(K.text());
                                            if (y != "") {
                                                V.prepend(f("<i>").addClass(K.prop("class")).css("float", V.css("direction") == "rtl" ? "right" : "left"))
                                            }
                                            y = y == "" ? ' class="on selected"' : y.substring(0, y.length - 1) + ' on selected"'
                                        }
                                        if (K.prop("disabled")) {
                                            y = y == "" ? ' class="disabled"' : y.substring(0, y.length - 1) + ' disabled"'
                                        }
                                        F += "<li" + y + '><span data-val="' + K.val() + '"' + (K.prop("disabled") == "disabled" ? "data-dis='disabled'" : "") + ">" + K.text() + "</span></li>"
                                    })
                                }
                            });
                            E.html(F);
                            var C = f('<div class="ffSelectMenuMid"></div>').css("overflow", "auto");
                            M.append(f('<div class="ffSelectMenuMidBG"></div>').append(C.append(E))).append('<div class="ffSelectMenuBottom"><span></span></div>');
                            M.css("display", "none");
                            A.append(x.append(B).append(M)).insertAfter(D.css("display", "none")).append(D);
                            var J = M.height();
                            var z = false;
                            var H = f(document).height() > f("html").height() ? f(document).height() : f("html").height();
                            if (H < (parseInt(A.offset().top) + parseInt(A.height()) + J + 15)) {
                                z = true;
                                M.height(0)
                            } else {
                                //M.css("top", A.height())
                            }
                            //B.css("height", A.innerHeight());
                            B.click(function() {
                                if ((m != null) && (m != E)) {
                                    m.closest(".ffSelectMenuWrapper").prev(".ffSelectButton").click()
                                }
                                c = true;
                                if (M.is(":hidden")) {
                                    if (!A.hasClass("disabled")) {
                                        m = E;
                                        x.css("z-index", 20);
                                        A.addClass("active");
                                        if (!z) {
                                            M.slideDown(300, function() {
                                                d(E);
                                                C.focus()
                                            })
                                        } else {
                                            M.show(0);
                                            M.animate({
                                                height: J,
                                                top: "-" + J + "px"
                                            }, 300, function() {
                                                d(E);
                                                C.focus()
                                            })
                                        }
                                    }
                                } else {
                                    m = null;
                                    A.removeClass("active");
                                    f("LI.on", M).removeClass("on");
                                    f("LI.selected", M).addClass("on");
                                    if (!z) {
                                        M.slideUp(300, function() {
                                            x.css("z-index", 10)
                                        })
                                    } else {
                                        M.animate({
                                            height: 0,
                                            top: 0
                                        }, 300, function() {
                                            M.hide(0);
                                            x.css("z-index", 10)
                                        })
                                    }
                                }
                            });
                            j(B, A);
                            var L = 0;
                            E.on("click", "LI", function() {
                                var Z = f(this);
                                if (!Z.hasClass("selected") && !Z.hasClass("disabled")) {
                                    var Y = f("span", Z).data("val");
                                    var X = f("span", Z).text();
                                    var T = P.validateSelectChange;
                                    if ((typeof T !== "function") || (T(D, X, Y) !== false)) {
                                        var V = f("LI", E).not(".ffGroup").index(Z);
                                        D.val(Y);
                                        var U = f("span", B).prop("class", "").text(X);
                                        if (Z.prop("class")) {
                                            U.prepend(f("<i>").addClass(Z.prop("class")).removeClass("on").css("float", U.css("direction") == "rtl" ? "right" : "left"))
                                        }
                                        Z.siblings(f("li")).removeClass("on");
                                        Z.addClass("on");
                                        Z.siblings(f("li.selected")).removeClass("selected");
                                        Z.addClass("selected");
                                        A.removeClass("active");
                                        f("option:selected", D).prop("selected", false);
                                        f("option:eq(" + V + ")", D).prop("selected", true);
                                        m = null;
                                        if (M.is(":visible")) {
                                            if (!z) {
                                                M.slideUp(300, function() {
                                                    x.css("z-index", 10)
                                                })
                                            } else {
                                                M.animate({
                                                    height: 0,
                                                    top: 0
                                                }, 300, function() {
                                                    M.hide(0);
                                                    x.css("z-index", 10)
                                                })
                                            }
                                        }
                                        if (!a) {
                                            var W = P.onSelectChange;
                                            if (typeof W === "function") {
                                                W(D, X, Y)
                                            }
                                        }
                                    } else {
                                        B.click()
                                    }
                                } else {
                                    if (Z.hasClass("selected") && M.is(":visible")) {
                                        B.click()
                                    }
                                }
                                a = false;
                                return false
                            }).on("mousemove", "LI", function(T) {
                                if ((_mouseX != T.pageX) || (_mouseY != T.pageY)) {
                                    f(".on", E).removeClass("on");
                                    f(this).addClass("on");
                                    _mouseX = T.pageX;
                                    _mouseY = T.pageY
                                }
                            });
                            if (f.fn.ffCustomScroll && P.customScrollBar) {
                                A.ffCustomScroll()
                            }
                        }
                    }
                    if (D.is(":submit")) {
                        A = f('<div class="ffButtonWrapper ffSubmitWrapper' + O + '"></div>');
                        var B = f('<A href="javascript:void(0)"><span>' + D.val() + "</span></A>");
                        A.insertAfter(D).append(B.append(D.css("display", "none")));
                        b(D, A);
                        j(B, A);
                        B.click(function() {
                            D.closest("FORM").submit()
                        })
                    }
                    if (D.is(":button")) {
                        A = f('<div class="ffButtonWrapper' + O + '"></div>');
                        var B = f('<A href="javascript:void(0)"><span>' + D.val() + "</span></A>");
                        A.insertAfter(D).append(B).append(D.css("display", "none"));
                        b(D, A);
                        j(B, A);
                        B.click(function() {
                            if (!A.hasClass("disabled")) {
                                D.click()
                            }
                        })
                    }
                    if (D.is(":reset")) {
                        A = f('<div class="ffButtonWrapper ffResetWrapper' + O + '"></div>');
                        var B = f('<A href="javascript:void(0)"><span>' + D.val() + "</span></A>");
                        A.insertAfter(D).append(B).append(D.css("display", "none"));
                        b(D, A);
                        j(B, A);
                        B.click(function() {
                            if (f(this).closest("form").length > 0) {
                                D.closest("FORM").fancyfields("reset")
                            } else {
                                D.click()
                            }
                        })
                    }
                    D.data("wrapper", A)
                }
            })
        },
        option: function(t, s) {
            return this.each(function() {
                var u = f(this);
                settings = u.data("settings");
                if (settings != null) {
                    settings[t] = s
                }
            })
        },
        bind: function(t, s) {
            return this.each(function() {
                var u = f(this);
                settings = u.data("settings");
                if (settings != null) {
                    settings[t] = s
                }
            })
        },
        unbind: function(s) {
            return this.each(function() {
                var t = f(this);
                settings = t.data("settings");
                if (settings != null) {
                    settings[s] = null
                }
            })
        },
        disable: function() {
            return this.each(function() {
                var s = f(this);
                settings = s.data("settings");
                wrapper = s.data("wrapper");
                if (wrapper != null) {
                    wrapper.addClass("disabled")
                }
                if (s.is(":text") || s.is("textarea") || s.is(":password") || s.is(":checkbox") || s.is(":radio") || s.is("select")) {
                    s.prop("disabled", "disabled")
                }
            })
        },
        enable: function() {
            return this.each(function() {
                var s = f(this);
                settings = s.data("settings");
                wrapper = s.data("wrapper");
                if (wrapper != null) {
                    wrapper.removeClass("disabled")
                }
                if (s.is(":text") || s.is("textarea") || s.is(":password") || s.is(":checkbox") || s.is(":radio") || s.is("select")) {
                    s.removeAttr("disabled")
                }
            })
        },
        toggleEnable: function() {
            return this.each(function() {
                var s = f(this);
                wrapper = s.data("wrapper");
                if (wrapper != null) {
                    wrapper.toggleClass("disabled")
                }
                if (s.is(":text") || s.is("textarea") || s.is(":password") || s.is(":checkbox") || s.is(":radio") || s.is("select")) {
                    if ((s.prop("disabled") == "disabled") || (s.prop("disabled") == true)) {
                        s.removeAttr("disabled")
                    } else {
                        s.prop("disabled", "disabled")
                    }
                }
            })
        },
        clean: function() {
            return this.each(function() {
                var s = f(this);
                if ((!s.is(":reset")) && (!s.is(":button")) && (!s.is(":submit")) && (!s.is("input[type=hidden]"))) {
                    if ((!s.is("input")) && (!s.is("textarea")) && (!s.is("select"))) {
                        f("INPUT,SELECT,TEXTAREA", s).fancyfields("clean")
                    } else {
                        settings = s.data("settings");
                        wrapper = s.data("wrapper");
                        if (settings.enableOnClean) {
                            wrapper.removeClass("disabled");
                            if (s.is(":text") || s.is("textarea") || s.is(":password") || s.is(":checkbox") || s.is(":radio") || s.is("select")) {
                                s.removeAttr("disabled")
                            }
                        }
                        if ((s.prop("disabled") != "disabled") || (settings.cleanDisableOnClean)) {
                            if (s.is(":checkbox") || s.is(":radio")) {
                                if (!s.is(":checked")) {
                                    invokeChange = false
                                }
                                s.removeAttr("checked");
                                wrapper.removeClass("on")
                            } else {
                                if (s.is("select")) {
                                    wrapper = s.data("wrapper");
                                    if (_ffIsMobile) {
                                        f("option:first", s).prop("selected", "selected");
                                        f("span", wrapper).text(f("option:first", s).text())
                                    } else {
                                        a = true;
                                        f("LI:first", wrapper).click()
                                    }
                                } else {
                                    if (s.is(":text") || s.is("textarea") || s.is(":password")) {
                                        s.val("");
                                        wrapper.removeClass("on")
                                    } else {
                                        if (s.is(":file")) {
                                            s.fancyfields("reset")
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            })
        },
        reset: function() {
            return this.each(function() {
                var t = f(this);
                if ((!t.is(":reset")) && (!t.is(":button")) && (!t.is(":submit")) && (!t.is("input[type=hidden]"))) {
                    if ((!t.is("input")) && (!t.is("textarea")) && (!t.is("select"))) {
                        f("INPUT,SELECT,TEXTAREA", t).fancyfields("reset")
                    } else {
                        defaultObj = t.data("default");
                        wrapper = t.data("wrapper");
                        var s = t.data("settings");
                        if (t.is(":checkbox") || t.is(":radio")) {
                            t.data("defaultLabel").insertAfter(defaultObj.insertAfter(wrapper))
                        } else {
                            defaultObj.insertAfter(wrapper)
                        }
                        wrapper.remove();
                        defaultObj.fancyfields(s)
                    }
                }
            })
        },
        checked: function() {
            return this.each(function() {
                var s = f(this);
                if ((s.is(":checkbox")) && (!s.is(":checked"))) {
                    s.click()
                }
            })
        },
        unchecked: function() {
            return this.each(function() {
                var s = f(this);
                if ((s.is(":checkbox")) && (s.is(":checked"))) {
                    s.click()
                }
            })
        }
    };

    function d(t) {
        if (f.fn.ffCustomScroll && t.closest(".ffSelect").next("select").data("settings").customScrollBar) {
            t.ffCustomScroll("ffCustomScrollCheckPosition")
        } else {
            var w = t.parent(".ffSelectMenuMid");
            var u = w.scrollTop();
            var s = w.height();
            var v = f("LI.on", t);
            if ((s + u) < (v.offset().top - t.offset().top + v.outerHeight())) {
                w.scrollTop(v.offset().top - t.offset().top)
            } else {
                if (u > (v.offset().top - t.offset().top)) {
                    w.scrollTop(v.offset().top - t.offset().top - s + v.outerHeight())
                }
            }
        }
    }

    function r(u, t, s, v, w) {
        s.insertAfter(t);
        s.click(function() {
            t.click()
        });
        u.data("defaultLabel", v.clone());
        u.data("labelElement", s);
        v.remove();
        if (u.prop("tabindex")) {
            s.prop("tabindex", u.prop("tabindex"));
            u.data("ti", u.prop("tabindex"));
            u.removeAttr("tabindex")
        }
        j(s, w)
    }

    function j(t, s) {
        t.focusin(function() {
            s.addClass("focus")
        });
        t.focusout(function() {
            s.removeClass("focus")
        });
        t.mouseout(function() {
            s.removeClass("focus")
        })
    }

    function h() {
        if (f(".on", m).next("LI").length > 0) {
            f(".on", m).toggleClass("on").next("LI").toggleClass("on");
            d(m)
        }
    }

    function g() {
        if (f(".on", m).prev("LI").length > 0) {
            f(".on", m).toggleClass("on").prev("LI").toggleClass("on");
            d(m)
        }
    }

    function b(t, s) {
        if (t.is(":disabled")) {
            s.addClass("disabled")
        }
    }
    f.fn.fancyfields = function(s) {
        if (p[s]) {
            return p[s].apply(this, Array.prototype.slice.call(arguments, 1))
        } else {
            if (typeof s === "object" || !s) {
                return p.init.apply(this, arguments)
            } else {
                f.error("Method " + s + " does not exist on jQuery.tooltip")
            }
        }
    };
    f.fn.submitIncluseDisebeld = function() {
        var s = f(this);
        f("input:disabled , textarea:disabled , select:disabled ", s).removeAttr("disabled");
        s.submit()
    };
    f.fn.setVal = function(s) {
        return this.each(function() {
            var u = f(this);
            if (u.is("select")) {
                u.val(s);
                wrapper = u.data("wrapper");
                if (_ffIsMobile) {
                    f("span", wrapper).text(f("option:selected", u).text())
                } else {
                    var t = f("option", u).index(f("option:selected", u));
                    f("LI:eq(" + t + ")", wrapper).click()
                }
            }
        })
    };
    f.fn.setOptions = function(s) {
        return this.each(function() {
            var u = f(this);
            if (u.is("select")) {
                var v;
                var t = u.data("settings");
                wrapper = u.data("wrapper");
                u.html("").insertAfter(wrapper);
                f.each(s, function(w, x) {
                    v = x[1] == null ? x[0] : x[1];
                    u.append('<option value="' + v + '" >' + x[0] + "</option>")
                });
                wrapper.remove();
                u.fancyfields(t)
            }
        })
    };
    f.fancyfields = {
        GroupVal: function(s) {
            return f("input[name=" + s + "]:checked").val()
        }
    }
})(jQuery);
jQuery.single = function(b) {
    return function(a) {
        b[0] = a;
        return b
    }
}(jQuery([1]));