
function getValue(varname) {
    var url = window.location.href;
    var qparts = url.split("?");
    if (qparts.length <= 1) { return ""; }
    var query = qparts[1];
    var vars = query.split("&amp;");
    var value = "";
    for (i = 0; i < vars.length; i++) {
        var parts = vars[i].split("=");
        if (parts[0] == varname) {
            value = parts[1];
            break;
        }
    }
    value = unescape(value);
    value.replace(/\+/g, " ");
    return value;
}
function getUrlReference() {
    var value = "";

    if (window.opener) {
        try {
            value = window.opener.location.href;
        }
        catch (e) {
            try {
                value = document.referrer;
            }
            catch (ex) { }
        }
    } else if (window.parent) {
        value = window.parent.location.href;
    } else {
        value = "";
    }
    return encodeURI(value);
}
var scriptLog = document.createElement("script");
//改为引用本地统计代码
scriptLog.src = "//old.nanhutravel.com/behaviorlog.aspx?xfun=" + getValue("xfun") + "&ref=" + getUrlReference() + "";
document.getElementsByTagName('HEAD').item(0).appendChild(scriptLog);

//$("body").append("<script type='text/C#' src='/BehaviorLog.aspx?xfun=" + getValue("xfun") + "&ref=" + getUrlReference() + "'></script>");
//document.write("<script src='/BehaviorLog.aspx?xfun=" + getValue("xfun") + "&ref=" + getUrlReference() + "'></script>");
