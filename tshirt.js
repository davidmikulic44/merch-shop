var clicked = false;

document.addEventListener('DOMContentLoaded', function() {
    var bottomImages = document.getElementById('bottom-images');
    var firstChild = bottomImages.firstElementChild;

    replaceImage(firstChild);

});

function replaceImage(clickedImage) {
    var frontImage = document.getElementById('front-image');
    frontImage.src = clickedImage.src;
    var bottomImages = document.querySelectorAll('.bottom-image');
    frontImage.src = clickedImage.src;
    bottomImages.forEach(image => {
        clicked = true;
        image.classList.remove('active');
        var firstChild = bottomImages.firstElementChild;

        if (firstChild) {
            firstChild.style.opacity = '0.6';
        }
    });

    // Add 'active' class to the clicked bottom image
    clickedImage.classList.add('active');
}
