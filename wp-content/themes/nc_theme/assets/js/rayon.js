var rayonInput = document.getElementById('rayon');
var rayonValue = document.getElementById('rayonValue');

rayonInput.addEventListener('input', function () {
    rayonValue.innerText = rayonInput.value + ' km';
});