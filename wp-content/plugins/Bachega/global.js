const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const page_type = urlParams.get('list') || urlParams.get('page');

var ajax = {
  upload: false,
}

switch(page_type){
    case 'upload':
      ajax.upload = true;
    break;
    case 'exc-main':
      ajax.upload = true;
    break;
}

if(ajax.upload == true) {
  document.getElementById('excelUp').addEventListener('change', handleFileSelect, false);
}

function handleFileSelect(e) {
  console.log(e);
  // const xhrSend = new XMLHttpRequest();
  // xhrSend.open("POST", 'https://localhost/wordpres-fonte/wp-content/plugins/Bachega/pages/ajax.php');
  // xhrSend.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  // xhrSend.send(`taget_send_lead=ss`);
  // xhrSend.onreadystatechange = function() {
  //     if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
  //         console.log(this.responseText);
  //       }
  //   }
}

document.addEventListener('DOMContentLoaded', (event) => {

    var dragSrcEl = null;
    
    function handleDragStart(e) {
      this.style.opacity = '0.4';
      
      dragSrcEl = this;
  
      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData('text/html', this.innerHTML);
    }
  
    function handleDragOver(e) {
      if (e.preventDefault) {
        e.preventDefault();
      }
  
      e.dataTransfer.dropEffect = 'move';
      return false;
    }
  
    function handleDragEnter(e) {
      this.classList.add('over');
    }
  
    function handleDragLeave(e) {
      this.classList.remove('over');
    }
  
    function handleDrop(e) {
      if (e.stopPropagation) {
        e.stopPropagation();// stops the browser from redirecting.
      }
      
      if (dragSrcEl != this) {
        dragSrcEl.innerHTML = this.innerHTML;
        this.innerHTML = e.dataTransfer.getData('text/html');
      }
      
      return false;
    }
  
    function handleDragEnd(e) {
      this.style.opacity = '1';
      
      items.forEach(function (item) {
        item.classList.remove('over');
      });
    }
    
    let items = document.querySelectorAll('.bgbox .box-fields');
    items.forEach(function(item) {
      item.addEventListener('dragstart', handleDragStart, false);
      item.addEventListener('dragenter', handleDragEnter, false);
      item.addEventListener('dragover', handleDragOver, false);
      item.addEventListener('dragleave', handleDragLeave, false);
      item.addEventListener('drop', handleDrop, false);
      item.addEventListener('dragend', handleDragEnd, false);
    });

});