
// Removal of event handling
function remove(el){
  var element = el;
  element.parentElement.remove();
  var arr = document.getElementsByClassName('list-group-item list-group-item-primary rounded');
  document.getElementById('courseCount').innerHTML = arr.length;
  if (document.getElementById('courseCount').innerHTML === '0'){
    document.getElementById('courseCount').innerHTML = '';

    let para = document.createElement('p');
    let x = document.createAttribute('id');
    x.value = 'emptyCRN';
    para.setAttributeNode(x);
    para.innerHTML = "No courses picked yet.";
    document.getElementById('listJumbo').appendChild(para);
  }

}

// Adding to course List
function addToList(data, id, time){
  if (document.getElementById(id)){
    alert('Course already added.');
    return;
  }

  var li = document.createElement('li');
  var att = document.createAttribute("class");
  var i = document.createAttribute("id");
  spanId = "span" + id.substr(3);
  console.log(spanId);
  i.value = id;
  att.value = "list-group-item list-group-item-primary rounded";
  li.setAttributeNode(att);
  li.setAttributeNode(i);
  li.innerHTML = data+'<span class="close" id = "' + spanId + '" onclick="remove(this)">X</span>';
  document.getElementById('crnList').appendChild(li);

  var arr = document.getElementsByClassName('list-group-item list-group-item-primary rounded');
  document.getElementById('courseCount').innerHTML = arr.length;
  if (arr.length){
    var el = document.getElementById('emptyCRN');
    el.remove();
  }
  document.getElementById("finalList").value += id.substr(3);
}

document.addEventListener('DOMContentLoaded', function() {

  document.getElementById("copyCRNButton").addEventListener('click', function() {
    let textarea = document.getElementById("finalList");
    console.log(textarea);
  });
});
