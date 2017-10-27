// Toggle the searchbar

window.onload = function() {
    function displaySearchBar() {
        document.getElementById('searchpls').classList.toggle('clicked');
    }

    document.getElementById('searchbtn').addEventListener('click', displaySearchBar);
}
