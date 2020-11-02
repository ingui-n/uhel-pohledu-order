const maxQuantity = 100;
const bookPrice = 499;

let formInputs = document.querySelectorAll('.form-item');
let quantity = document.querySelector('#quantity');
let totalPrice = document.querySelector('.total-price');

formInputs.forEach((value) => {
    value.addEventListener('focusout', function () {
        if (value.value === '' || (value.value === '/checkbox' && !value.checked))
            value.style.boxShadow = '0 0 4px 2px red';
        else
            value.style.boxShadow = 'none';
    });
    value.addEventListener('keydown', function (e) {
        if (e.key === 'Enter')
            e.preventDefault();
    });
});

document.addEventListener('readystatechange', function () {

    sumFullPrice();
});

quantity.addEventListener('keyup', function () {
    sumFullPrice();
});

let transportMethod = document.querySelectorAll('#radio');

transportMethod.forEach(value => {
    value.addEventListener('click', function () {
        sumFullPrice();
    })
});

function sumFullPrice() {
    if (parseInt(quantity.value) < 1 || parseInt(quantity.value) > maxQuantity) {
        totalPrice.textContent = '0';
    } else {
        let transportPrice = document.querySelector('#radio:checked');
        totalPrice.textContent = bookPrice * parseInt(quantity.value) + parseInt(transportPrice.dataset.price);
    }
}

let submitButtonTrue = document.querySelector('.submit-button__true');
let submitButtonFalse = document.querySelector('.submit-button__false');

function isAllInputsValid() {
    //todo hide submit button
}
