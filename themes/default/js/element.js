(function(){var d=window,e=document,f=".",g="UTF-8",h="complete",i="head",j="link",k="script",l="stylesheet",m="text/css",n="text/javascript";Math.floor(2147483648*Math.random()).toString(36);function p(b){var a=e.getElementsByTagName(i)[0];a||(a=e.body.parentNode.appendChild(e.createElement(i)));a.appendChild(b)}function _loadJs(b){var a=e.createElement(k);a.type=n;a.charset=g;a.src=b;p(a)}function _loadCss(b){var a=e.createElement(j);a.type=m;a.rel=l;a.charset=g;a.href=b;p(a)}function _isNS(b){for(var b=b.split(f),a=d,c=0;c<b.length;++c)if(!(a=a[b[c]]))return!1;return!0}function _setupNS(b){for(var b=b.split(f),a=d,c=0;c<b.length;++c)a=a[b[c]]||(a[b[c]]={});return a}
d.addEventListener&&"undefined"==typeof e.readyState&&d.addEventListener("DOMContentLoaded",function(){e.readyState=h},!1);
if (_isNS('google.translate.Element')){return}var c=_setupNS('google.translate._const');c._cl='en';c._cuc='googleTranslateElementInit';c._cac='';c._cam='';var h='translate.googleapis.com';var b=(window.location.protocol=='https:'?'https://':'http://')+h;c._pah=h;c._pbi=b+'/translate_static/img/te_bk.gif';c._pci=b+'/translate_static/img/te_ctrl3.gif';c._phf=h+'/translate_static/js/element/hrs.swf';c._pli=b+'/translate_static/img/loading.gif';c._plla=h+'/translate_a/l';c._pmi=b+'/translate_static/img/mini_google.png';c._ps=b+'/translate_static/css/translateelement.css';c._puh='translate.google.com';_loadCss(c._ps);_loadJs(b+'/translate_static/js/element/main.js');})();