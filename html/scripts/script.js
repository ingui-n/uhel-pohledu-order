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
        isAllInputsValid()
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
    if (parseInt(quantity.value) < 1 || quantity.value === '' || parseInt(quantity.value) > maxQuantity) {
        totalPrice.textContent = '0';
    } else {
        let transportPrice = document.querySelector('#radio:checked');
        totalPrice.textContent = bookPrice * parseInt(quantity.value) + parseInt(transportPrice.dataset.price);
    }
}

let submitButtonTrue = document.querySelector('.submit-button__true');
let submitButtonFalse = document.querySelector('.submit-button__false');

let firstName = document.querySelector('#first-name');
let lastName = document.querySelector('#last-name');
let street = document.querySelector('#street');
let town = document.querySelector('#town');
let zipCode = document.querySelector('#zip-code');

function isAllInputsValid() {
    if (isQuantityValid())
        if (isFirstNameValid())
            if (isLastNameValid())
                if (isStreetValid())
                    if (isTownValid())
                        if (isZipCodeValid())
                            if (isPhoneNumberValid())
                                if (isEmailValid())
                                    if (isTermsAgree()) {
                                        submitButtonTrue.style.display = 'block';
                                        submitButtonFalse.style.display = 'none';
                                        return true;
                                    }
    //submitButtonTrue.style.display = 'none';
    //submitButtonFalse.style.display = 'false';
    return false;
}

function isQuantityValid() {
    let quantity = document.querySelector('#quantity').value;

    return 100 > quantity > 0;
}

function isFirstNameValid() {
    let firstName = document.querySelector('#first-name').value;

    return 50 > firstName.length > 1;
}

function isLastNameValid() {
    let lastName = document.querySelector('#last-name').value;

    return 50 > lastName.length > 1;
}

function isStreetValid() {
    let street = document.querySelector('#street').value;

    return 80 > street.length > 0;
}

function isTownValid() {
    let town = document.querySelector('#town').value;

    return 70 > town.length > 1;
}

function isZipCodeValid() {
    let zipCode = document.querySelector('#zip-code').value;

    zipCode.replace(' ', '');
    return zipCode === 5;
}

function isPhoneNumberValid() {
    let phoneNumber = document.querySelector('#phone-number').value;

    phoneNumber.replace(' ', '');
    return phoneNumber === 9;
}

function isEmailValid() {
    let email = document.querySelector('#email').value;

    return /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email);
}

function isTermsAgree() {
    return document.querySelector('#terms:checked');
}

