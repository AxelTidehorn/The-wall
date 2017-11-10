window.onload = function() {
    // Toggle the searchbar

    function displaySearchBar(e) {
        e.preventDefault();
        document.getElementById('searchpls').classList.toggle('clicked');
    }

    document.getElementById('searchbtn').addEventListener('click', displaySearchBar);
}
