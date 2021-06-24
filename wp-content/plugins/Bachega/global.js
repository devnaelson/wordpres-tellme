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

if(ajax.upload == true) document.getElementById('excelUp').addEventListener("change",handleFileSelect);
function handleFileSelect(e) {
  var error = false;
  try{
    e.target.files[0];
  }catch(e){
    error = true;
  }
  if(error == false) {
    
  var execKey = document.getElementById('execkeys').value;
  var urlEnd = document.getElementById('url_site').value;

  var dataFile = e.target.files[0];
  const xhrSend = new XMLHttpRequest();
  let formD = new FormData();
  formD.append('reqKey', true);
  formD.append('encrypt', execKey);
  formD.append('fl_exc', dataFile);
  xhrSend.open("POST", urlEnd+'/Bachega/ajax.php');
  xhrSend.send(formD);
  xhrSend.onreadystatechange = function() {
      if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
          let data = JSON.parse(this.responseText);
          var atributes = [];
          Object.keys(data.exec).forEach(function(key) {
             var sheet_excel = '<div class="box-primary">'+data.exec[key].key+'</div>';
             var sheet_detect = '<div draggable="true" class="box-fields" data-column="'+data.exec[key].letter+'" data-side="right">'+data.exec[key].letter+'</div>';
             document.getElementById('sheet_excel').innerHTML += sheet_excel;
             document.getElementById('sheet_detect').innerHTML += sheet_detect;
             atributes[key] = {letter: data.exec[key].letter, value: null};
             if(key == data.exec.length - 1) { startDrag();
               excStrurctur = [{file:data.file_name,spread:atributes}];
               localStorage.setItem('execStructure',JSON.stringify(excStrurctur));
           }
        });
      }
    }
  }
}

function startDrag(){

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
  
    function handleDragEnter(e) { this.classList.add('over'); }
    function handleDragLeave(e) { this.classList.remove('over'); }
  
    function handleDrop(e) {
      if (e.stopPropagation) {
        e.stopPropagation();// stops the browser from redirecting.
      }
      
      if (dragSrcEl != this && dragSrcEl.getAttribute('data-side') != this.getAttribute('data-side')) {
        var execStorage = localStorage.getItem('execStructure');
        var exec = JSON.parse(execStorage);
        let letter = this.getAttribute('data-column');
        console.log(this);
        console.log(dragSrcEl);
        let value = dragSrcEl.innerHTML;
        Object.keys(exec[0].spread).forEach(function(key) {
          if(exec[0].spread[key].letter == letter)
            exec[0].spread[key].value = value;
          if(key == exec[0].spread.length -1)
            localStorage.setItem('execStructure',JSON.stringify(exec));
        });
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

  }
