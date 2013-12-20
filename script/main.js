//Javascript Main file

function offMsg() {
    document.getElementsByClassName('msg').item('p').style.display = 'none';
}
var t = setTimeout("offMsg()", 14000);

$(document).tooltip();