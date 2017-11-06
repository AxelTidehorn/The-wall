window.onload = function() {
    // Hide the welcome box

    function hide() {
        document.getElementById('welcome').classList.toggle('clicked');
    }

    document.querySelectorAll('hide > a')[0].addEventListener('click', hide);
}
