var uForm = document.getElementById('uploadForm');
var typeSelection = document.getElementsByName('uploadType')[0];
function showImg() {
    uForm.style.display = "block";
    typeSelection.value="image";
    //removing and de-requering the wrong fields
    document.getElementsByName('uploadedWebsite')[0].style.display="none";
    document.getElementsByName('uploadedWebsite')[0].required = false;
    document.getElementsByName('uploadedText')[0].style.display="none";
    document.getElementsByName('uploadedText')[0].required = false;
    document.getElementsByName('URL')[0].style.display="none";

    //showing and requering the correct fields
    document.getElementsByName('uploadedImage')[0].style.display ='block';
    document.getElementsByName('uploadedImage')[0].required = 'required';
}
function showTxt() {
    uForm.style.display = "block";
    typeSelection.value="text";

    //removing and de-requering the wrong fields
    document.getElementsByName('uploadedWebsite')[0].style.display="none";
    document.getElementsByName('uploadedWebsite')[0].required = false;
    document.getElementsByName('URL')[0].style.display="none";
    document.getElementsByName('uploadedImage')[0].style.display='none';
    document.getElementsByName('uploadedImage')[0].required = false;

    //showing and requering the correct fields
    document.getElementsByName('uploadedText')[0].style.display="block";
    document.getElementsByName('uploadedText')[0].required = true;

}
function showWeb() {
    uForm.style.display = "block";
    typeSelection.value="website";

    //removing and de-requering the wrong fields
    document.getElementsByName('uploadedText')[0].style.display="none";
    document.getElementsByName('uploadedText')[0].required = false;
    document.getElementsByName('URL')[0].style.display="none";
    document.getElementsByName('uploadedImage')[0].style.display= "none";
    document.getElementsByName('uploadedImage')[0].required = false;

    //showing and requering the correct fields
    document.getElementsByName('uploadedWebsite')[0].style.display="block";
    document.getElementsByName('uploadedWebsite')[0].required = true;
    document.getElementsByName('URL')[0].style.display="block";
}
//Done handling the form part, moving on to the tagging system!

var tagField = document.getElementById('tagInput');
var shownTags = document.getElementById('tagShowing');
var tagData = document.getElementById('tagData');
function createTag() {
    event.preventDefault();
    if (tagInput.value != '') {
        // alert('hey?');
        // console.log(tagData.value);
        shownTags.innerHTML += (tagField.value + ', ');
        if (tagData.value == '') {
            console.log('first');
            tagData.value = tagField.value;
        }else if (tagData.value != '') {
            console.log('second');
            tagData.value += ('?' + tagField.value);

        }
        tagInput.value = '';
        tagInput.focus();
    }
}
