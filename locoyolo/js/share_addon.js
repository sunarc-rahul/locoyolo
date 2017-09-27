var sa_iframe_html, sa_chkTimer;

function sa_tellafriend(sa_url, sa_startpage) {
    var sa_bgdiv = document.createElement("div");
    sa_bgdiv.id = 'sa_bgdiv';
    sa_bgdiv.onclick = sa_tellafriend_close;
    sa_bgdiv.style.width = document.body.scrollWidth + 'px';
    sa_bgdiv.style.display = 'none';
    sa_bgdiv.style.height = sa_getHeight() + 'px';
    sa_bgdiv.style.height = sa_getHeight() + 'px';
    sa_bgdiv.style.zIndex = '1000';
    sa_bgdiv.style.position = 'absolute';
    sa_bgdiv.style.top = '0px';
    sa_bgdiv.style.left = '0px';
    sa_bgdiv.style.background = '#000000';
    document.body.appendChild(sa_bgdiv);
    sa_setOpac(1, 'sa_bgdiv');
    sa_getEl('sa_bgdiv').style.display = '';
    for (i = 1; i <= 50; i++) {
        setTimeout("sa_setOpac(" + i + ",'sa_bgdiv')", (i * 5))
    }
    if (sa_url == undefined || sa_url == '') {
        var sa_pageurl = document.location.href.split('#')[0]
    } else {
        var sa_pageurl = sa_url
    } if (sa_startpage == undefined || sa_startpage == '') {
        var sa_startpage = ''
    }
    var sa_refurl = document.location.href.split('#')[0];
    var sa_iframe = document.createElement("div");
    sa_iframe.onclick = sa_tellafriend_close;
    sa_iframe.id = 'sa_iframe';
    sa_iframe.style.display = 'none';
    sa_iframe.style.zIndex = '1001';
    sa_iframe.style.position = 'absolute';
    sa_iframe.style.width = '100%';
    sa_iframe.style.left = '0px';
    sa_iframe.style.textAlign = 'center';
    sa_iframe_html = '<div style="width:412px;position:relative;margin-left:auto;margin-right:auto;" valign=top> <div style="position:absolute;left:393px;z-index:1002;top:-6px"><a href=# id=sa_x style="outline:0;display:block;width:30px;height:29px;background-position:0px 0px;background-image:url(\'http://s1.smartaddon.com/x.png\');opacity:0.8;filter:alpha(opacity=80)" onmouseover="sa_setOpac(\'100\',this.id)" onmouseout="sa_setOpac(\'80\',this.id)" onclick="return sa_tellafriend_close()"><!----></a></div> <iframe onload="sa_tellafriend_iframe();" scrolling="no" width=412 height=500 src="http://v2.smartaddon.com/?start=' + sa_startpage + '&url=' + escape(sa_pageurl) + '&ref=' + escape(sa_refurl) + '" frameborder="0" allowtransparency="true"></iframe> </div>';
    sa_iframe.innerHTML = sa_iframe_html;
    document.body.appendChild(sa_iframe);
    sa_chkClose();
    sa_tellafriend_setpos(1);
    window.onresize = sa_tellafriend_setpos;
    window.onscroll = sa_tellafriend_setpos;
    return false
}

function sa_tellafriend_setpos(v) {
    var sa_iframe = sa_getEl('sa_iframe'),
        sa_doFix;
    if (!sa_iframe) {
        return false
    }
    if (!v && sa_iframe.style.display == 'none') {
        return false
    }
    if (sa_iframe.style.position != 'fixed') {
        var sa_toppos = (typeof window.innerHeight != 'undefined' ? window.innerHeight : document.body.offsetHeight) / 10;
        if (!sa_toppos) {
            sa_toppos = 100
        }
        if (navigator.appName != 'Microsoft Internet Explorer') {
            sa_iframe.style.position = 'fixed';
            var sa_doFix = 1
        }
        if (navigator.appName == 'Microsoft Internet Explorer' && document.compatMode == 'CSS1Compat' && parseFloat(navigator.appVersion.split("MSIE")[1]) >= 7) {
            sa_iframe.style.position = 'fixed';
            var sa_doFix = 1
        }
        if (!sa_doFix) {
            sa_toppos = sa_toppos + sa_scrollTop()
        }
        sa_iframe.style.top = sa_toppos + 'px'
    }
}

function sa_tellafriend_close() {
    document.body.removeChild(sa_getEl('sa_iframe'));
    document.body.removeChild(sa_getEl('sa_bgdiv'));
    return false
}

function sa_tellafriend_iframe() {
    if (sa_getEl('sa_iframe').style.display == '') {
        return false
    }
    sa_setOpac(1, 'sa_iframe');
    sa_getEl('sa_iframe').style.display = '';
    for (i = 1; i <= 10; i++) {
        setTimeout("sa_setOpac(" + i * 10 + ",'sa_iframe')", (i * 20))
    }
}

function sa_getHeight() {
    var sa_db = document.body;
    var sa_dde = document.documentElement;
    return Math.max(sa_db.scrollHeight, sa_dde.scrollHeight, sa_db.offsetHeight, sa_dde.offsetHeight, sa_db.clientHeight, sa_dde.clientHeight)
}

function sa_scrollTop() {
    return Math.max(document.documentElement.scrollTop, document.body.scrollTop)
}

function sa_setOpac(lev, obj) {
    if (lev < 0) {
        sa_getEl(obj).style.opacity = '';
        sa_getEl(obj).style.filter = ''
    } else {
        sa_getEl(obj).style.opacity = (lev / 100);
        sa_getEl(obj).style.filter = "alpha(opacity=" + lev + ")"
    }
}

function sa_getEl(o) {
    return document.getElementById(o)
}

function sa_chkClose() {
    if (sa_getEl('sa_iframe')) {
        if (document.location.hash.indexOf('#share_close') == 0) {
            document.location.hash = '#';
            sa_tellafriend_close()
        } else {
            sa_chkTimer = setTimeout('sa_chkClose()', 100)
        }
    }
}

function sa_share_popup(sa_s, sa_u) {
    window.open('http://share.smartaddon.com/?s=' + sa_s + '&u=' + escape(sa_u), '', 'scrollbars=yes,menubar=no,height=420,width=550,resizable=yes,toolbar=no,location=no,status=no,left=' + (screen.width - 550) / 2 + ',top=' + (screen.height - 420) / 3);
    return false
}

function sa_dobar() {
    var sa_icons = new Array('share|0|24|Share this page', 'facebook|39|111|Share on Facebook', 'twitter|63|126|Tweet this page', 'googleplus|+1 this page', 'email|87|141|Tell your friends');
    var sa_len = sa_icons.length,
        sa_iconEl, sa_iconData, sa_iconSize, sa_iconURL, sa_iconBG, sa_iconBar;
    sa_iconBar = sa_getEl('sa_share_bar');
    if (!sa_iconBar) {
        return false
    }
    var sa_clearLeft = document.createElement('div');
    sa_clearLeft.style.clear = 'left';
    sa_iconBar.appendChild(sa_clearLeft);
    for (var i = 0; i < sa_len; i++) {
        sa_iconData = sa_icons[i].split('|');
        sa_iconEl = sa_getEl('sa_share_' + sa_iconData[0]);
        if (sa_iconEl) {
            if (sa_iconEl.getAttribute('layout') == 'icon' || sa_iconEl.getAttribute('layout') == '') {
                if (!sa_iconEl.getAttribute('url')) {
                    sa_iconURL = document.location.href.split('#')[0]
                } else {
                    sa_iconURL = sa_iconEl.getAttribute('url')
                }
                sa_iconURL = sa_iconURL.replace('http://', '');
                sa_iconEl.style.fontSize = '0pt';
                sa_iconEl.style.display = 'block';
                sa_iconEl.style.marginRight = '2px';
                sa_iconEl.title = sa_iconData[3];
                if (document.all) {
                    sa_iconEl.style.styleFloat = 'left'
                } else {
                    sa_iconEl.style.cssFloat = 'left'
                } if (sa_iconEl.getAttribute('size') == 15) {
                    sa_iconSize = 15;
                    sa_iconBG = sa_iconData[2]
                } else {
                    sa_iconSize = 24;
                    sa_iconBG = sa_iconData[1]
                } if (sa_iconData[0] == 'googleplus') {
                    sa_iconEl.innerHTML = '<iframe style="border:0" width=' + (sa_iconSize == 24 ? '38' : 24) + ' height=' + sa_iconSize + ' scrolling=no border=0 frameborder=0 src="https://plusone.google.com/u/0/_/+1/fastbutton?url=http%3A%2F%2F' + sa_iconURL + '&size=' + (sa_iconSize == 24 ? 'standard' : 'small') + '&count=false"></iframe>'
                } else {
                    sa_iconEl.onmouseover = function () {
                        sa_setOpac(80, this.id)
                    };
                    sa_iconEl.onmouseout = function () {
                        sa_setOpac(100, this.id)
                    };
                    sa_iconEl.style.background = 'url(http://s1.smartaddon.com/icons.png) 0 -' + sa_iconBG + 'px';
                    if (sa_iconData[0] == 'share') {
                        if (sa_iconSize == 24) {
                            sa_iconEl.style.width = '63px'
                        } else {
                            sa_iconEl.style.width = '42px'
                        }
                    } else {
                        sa_iconEl.style.width = sa_iconSize + 'px'
                    }
                    sa_iconEl.style.height = sa_iconSize + 'px';
                    if (sa_iconData[0] == 'share') {
                        sa_iconEl.onclick = new Function('return sa_tellafriend(\'' + sa_iconURL + '\')');
                        sa_iconEl.href = '#'
                    } else if (sa_iconData[0] == 'email') {
                        sa_iconEl.onclick = new Function('return sa_tellafriend(\'' + sa_iconURL + '\',\'email\')');
                        sa_iconEl.href = '#'
                    } else {
                        sa_iconEl.onclick = new Function('return sa_share_popup(\'' + sa_iconData[0] + '\',\'' + sa_iconURL + '\')');
                        sa_iconEl.href = 'http://share.smartaddon.com/?s=' + sa_iconData[0] + '&u=' + escape(sa_iconURL)
                    }
                }
            }
        }
    }
}

function sa_addLoad(sa_func) {
    if (window.addEventListener) {
        window.addEventListener('load', sa_func, false);
        return true
    }
    if (window.attachEvent) {
        window.attachEvent('onload', sa_func);
        return true
    }
}
sa_addLoad(sa_dobar);