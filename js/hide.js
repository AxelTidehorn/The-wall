window.onload = function() {
    // Hide the welcome box

    function hide() {
        document.getElementById('welcome').classList.toggle('clicked');
        var date = new Date();
        var time = (date.getTime() + 60*60*24*30);
        document.cookie = "welcomed=true; expires=" + time; //Setting a cookie through JavaScript so the page does not have to be updated.
    }

    document.querySelectorAll('.hide > a')[0].addEventListener('click', hide);
}
