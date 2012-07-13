
var editableForm;

function profileInit() {
  editableForm = document.getElementById("editableForm");

  var editButton = document.getElementById("editButton");
  editButton.addEventListener("click", enableEditing);

  new Validator(document.forms["profile"]);
}

function enableEditing() {
  editableForm.className = "editable"; 
  return false;
}

function disableEditing() {
  editableForm.className = "uneditable";
}

window.addEventListener("load", profileInit);

