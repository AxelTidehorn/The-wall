// Toggle the searchbar

function displaySearchBar() {
	
	document.getElementById('searchpls').classList.toggle('clicked')
}

document.getElementById('searchbtn').addEventListener('click', displaySearchBar);
