
const xhrSend = new XMLHttpRequest();
let formD = new FormData();
formD.append('action', 'my_action');
formD.append('whatever', '1234');
xhrSend.open("POST", ajaxurl);
xhrSend.send(formD);
xhrSend.onreadystatechange = function() {
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
        alert(this.responseText)
    }
 }