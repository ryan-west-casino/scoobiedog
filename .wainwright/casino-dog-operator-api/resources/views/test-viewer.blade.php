<base href="https://wainwrighted.herokuapp.com/https://d2drhksbtcqozo.cloudfront.net/casino/games-mt/huntresswildvengeance/">
{!! $html !!}

<script defer="">
var DO;
! function(e) {
    var p, i, r;

    function d() {
        if ("body" === p.appWrapperId) return document.body;
        var e = document.getElementById(p.appWrapperId);
        if (e) return e;
        throw new Error("App wrapper element does not exist: " + p.appWrapperId)
    }

    function l() {
        function n() {
            var e, n, t, a, o;
            p.showTempSpinner && ((i = document.createElement("div")).style.backgroundColor = "black", i.style.position = "fixed", i.style.left = "0", i.style.top = "0", i.style.zIndex = "9990", i.className = "temp-loader-container", i.style.width = "100%", i.style.height = "100%", (e = document.createElement("div")).style.position = "absolute", e.style.left = "50%", e.style.top = "50%", e.style.transform = "translate(-50%, -50%)", e.className = "temp-loader-spinner", i.appendChild(e), d().appendChild(i)), n = p.appConfig.scriptTags, t = function() {
                var n, e = p.appConfig.metaTags;
                e && 0 !== e.length && e.forEach(function(e) {
                    var n = document.createElement("meta");
                    n.name = e.name, n.content = e.content, document.head.appendChild(n)
                }), (e = p.appConfig.miscTags) && 0 !== e.length && (n = "", e.forEach(function(e) {
                    n += e
                }), document.head.innerHTML += n), p.appConfig, s(r.LOAD_COMPLETE)
            }, n && 0 !== n.length ? (a = 0, (o = function() {
                var e = document.createElement("script");
                e.type = "text/javascript", e.src = p.applicationUrl + n[a], document.head.appendChild(e), e.onload = function() {
                    a++, s(r.SCRIPT_LOADED, (n.length, n[a - 1])), (a === n.length ? t : o)()
                }, e.onerror = function(e) {}
            })()) : t()
        }
        var t, a, o = p.appConfig.styleTags;
        o && 0 !== o.length ? (t = 0, (a = function() {
            var e = document.createElement("link");
            e.rel = "stylesheet", e.href = p.applicationUrl + o[t], document.head.appendChild(e), e.onload = function() {
                (++t === o.length ? n : a)()
            }, e.onerror = function(e) {}
        })()) : n()
    }

    function c() {}

    function s(e, n) {
        var t;
        "function" == typeof Event ? t = new Event(e) : (t = document.createEvent("Event")).initEvent(e, !0, !0), document.dispatchEvent(t)
    }
    e = e.FW || (e.FW = {}), (p = e.DOMLoader || (e.DOMLoader = {})).useCORS = 1, p.appWrapperId = "body", p.showTempSpinner = !0, p.applicationUrl = "", p.appReady = !1, (e = r = p.DOMLoaderEvents || (p.DOMLoaderEvents = {})).LOAD_START = "domLoaderEvents.loadStart", e.SCRIPT_LOADED = "domLoaderEvents.scriptLoaded", e.INIT_PLUGINS = "domLoaderEvents.initPlugins", e.LOAD_COMPLETE = "domLoaderEvents.loadComplete", p.getAppWrapperElement = d, p.load = function(e, n, t) {
        var a, o;
        p.startTimestamp = Date.now(), s(r.LOAD_START), p.appReadyCallback = e, p.domReadyCallback = n, p.applicationUrl.length && "/" !== p.applicationUrl.substr(-1) && (p.applicationUrl = p.applicationUrl + "/"), window.focus(), window.addEventListener("resize", c), t && "string" != typeof t ? (p.appConfig = t, p.onAppConfigLoaded && p.onAppConfigLoaded(p.appConfig), l()) : (e = p.applicationUrl + (t && "string" == typeof t ? t : "appConfig2.json"), a = function(e) {
            p.appConfig = e, p.onAppConfigLoaded && p.onAppConfigLoaded(p.appConfig), l()
        }, (o = new XMLHttpRequest).overrideMimeType("application/json"), o.onreadystatechange = function() {
            4 === o.readyState && 200 === o.status && a(JSON.parse(o.responseText))
        }, o.open("GET", e, !0), o.send())
    }, p.hideTempLoadingDiv = function() {
        p.showTempSpinner && i.parentElement.removeChild(i)
    }, p.addStyleSheet = function(e) {
        var n = document.createElement("style");
        n.type = "text/css", n.innerHTML = e, document.head.appendChild(n)
    }, p.dispatchEvent = s
}(DO = DO || {});
    </script>