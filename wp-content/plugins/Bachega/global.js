const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const page_type = urlParams.get('list');

switch(page_type){
    case 'upload':
    break;
}
