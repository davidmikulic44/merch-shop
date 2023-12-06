// dynamicLoader.js

function loadPage(url) {
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            document.getElementById('contentContainer').innerHTML = html;
        })
        .catch(error => {
            console.error('Error during fetch:', error);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    // Attach the loadPage function to each item with the class "load-page-button"
    const loadPageButtons = document.querySelectorAll('.load-page-button');
    loadPageButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default behavior of the anchor tag
            const pageUrl = button.getAttribute('href');
            loadPage(pageUrl);
        });
    });
});
