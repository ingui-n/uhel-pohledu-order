const maxQuantity = 1000;
const bookPrice = 499;
const errorMessages = [
    'Zadejte požadovanou hodnotu!',
    'Zadejte číslo!',
    'Zadejte platnou číselnou hodnotu!',
    'Zadejte Vaše jméno!',
    'Zadejte své pravé jméno!',
    'Zadejte Vaše příjmení!',
    'Zadejte své pravé příjmení!',
    'Zadejte vaší ulici se jménem popisným!',
    'Zadejte platný název ulice!',
    'Zadejte název vašeho města/obce!',
    'Zadejte platný název města/obce!',
    'Zadejte vaše PSČ!',
    'Zadejte platné PSČ!',
    'Zadejte vaše telefonní číslo!',
    'Zadejte platné telefonní číslo!',
    'Zadejte svůj email!',
    'Zadejte platný email!',
    'Musíte souhlasit s našimi obchodními podmínkami!'
];
const validationTypeArray = {
    'quantity' : 'isQuantityValid(true)',
    'first-name' : 'isFirstNameValid(true)',
    'last-name' : 'isLastNameValid(true)',
    'street' : 'isStreetValid(true)',
    'town' : 'isTownValid(true)',
    'zip-code' : 'isZipCodeValid(true)',
    'phone-number' : 'isPhoneNumberValid(true)',
    'email' : 'isEmailValid(true)',
    'terms' : 'isTermsValid(true)'
};

let formInputs = document.querySelectorAll('.form-item');
let quantity = document.querySelector('#quantity');
let totalPrice = document.querySelector('.total-price');

formInputs.forEach((value) => {
    value.addEventListener('focusout', function () {
        validateInput(value.id);
        validateAllInputs();
    });
    value.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
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

submitButtonFalse.addEventListener('click', function () {
   validateAllInputs();
});

function validateInput(type) {
    eval(validationTypeArray[type]);
}

function validateAllInputs() {
    isQuantityValid();
    isFirstNameValid();
    isLastNameValid();
    isStreetValid();
    isTownValid();
    isZipCodeValid();
    isPhoneNumberValid();
    isEmailValid();
    isTermsValid(false, true);
}

function getErrorMessage(message, type, errorNum=null) {
    let setError = document.querySelector('.error__' + type);
    let input = document.querySelector('#' + type);

    if (message === true) {
        input.classList.add('error-input');
        setError.textContent = errorMessages[errorNum];
    } else {
        input.classList.remove('error-input');
        setError.textContent = '';
    }
}

/**
 * @returns {boolean}
 */
function isQuantityValid(b=false) {
    let quantity = document.querySelector('#quantity').value;

    if (quantity !== '') {
        if (parseInt(quantity)) {
            if (quantity > 0 && quantity <= maxQuantity) {
                if (b)
                    getErrorMessage(false, 'quantity');
                return true;
            } else {
                if (b)
                    getErrorMessage(true, 'quantity', 2);
            }
        } else {
            if (b)
                getErrorMessage(true, 'quantity', 1);
        }
    } else {
        if (b)
            getErrorMessage(true, 'quantity', 0);
    }
    return false;
}

/**
 * @returns {boolean}
 */
function isFirstNameValid(b=false) {
    let firstName = document.querySelector('#first-name').value;

    if (firstName !== '') {
        if (/^[A-Za-zÁáČčĎďÉéĚěÍíŇňÓóŘřŠšŤťŮůÚúÝýŽž]*$/.test(firstName)) {
            if (firstName.length > 1 && firstName.length < 50) {
                if (b)
                    getErrorMessage(false, 'first-name');
                return true;
            } else {
                if (b)
                    getErrorMessage(true, 'first-name', 4);
            }
        } else {
            if (b)
                getErrorMessage(true, 'first-name', 4);
        }
    } else {
        if (b)
            getErrorMessage(true, 'first-name', 3);
    }
    return false;
}

/**
 * @returns {boolean}
 */
function isLastNameValid(b=false) {
    let lastName = document.querySelector('#last-name').value;

    if (lastName !== '') {
        if (/^[A-Za-zÁáČčĎďÉéĚěÍíŇňÓóŘřŠšŤťŮůÚúÝýŽž]*$/.test(lastName)) {
            if (lastName.length > 1 && lastName.length < 50) {
                if (b)
                    getErrorMessage(false, 'last-name');
                return true;
            } else {
                if (b)
                    getErrorMessage(true, 'last-name', 6);
            }
        } else {
            if (b)
                getErrorMessage(true, 'last-name', 6);
        }
    } else {
        if (b)
            getErrorMessage(true, 'last-name', 5);
    }
    return false;
}

/**
 * @returns {boolean}
 */
function isStreetValid(b=false) {
    let street = document.querySelector('#street').value;

    if (street !== '') {
        if (/^[A-Za-zÁáČčĎďÉéĚěÍíŇňÓóŘřŠšŤťŮůÚúÝýŽž0-9]*$/.test(street)) {
            if (street.length > 0 && street.length < 80) {
                if (b)
                    getErrorMessage(false, 'street');
                return true;
            } else {
                if (b)
                    getErrorMessage(true, 'street', 8);
            }
        } else {
            if (b)
                getErrorMessage(true, 'street', 8);
        }
    } else {
        if (b)
            getErrorMessage(true, 'street', 7);
    }
    return false;
}

/**
 * @returns {boolean}
 */
function isTownValid(b=false) {
    let town = document.querySelector('#town').value;

    if (town !== '') {
        if (/^[A-Za-zÁáČčĎďÉéĚěÍíŇňÓóŘřŠšŤťŮůÚúÝýŽž0-9-]*$/.test(town)) {
            if (town.length > 1 && town.length < 70) {
                if (b)
                    getErrorMessage(false, 'town');
                return true;
            } else {
                if (b)
                    getErrorMessage(true, 'town', 10);
            }
        } else {
            if (b)
                getErrorMessage(true, 'town', 10);
        }
    } else {
        if (b)
            getErrorMessage(true, 'town', 9);
    }
    return false;
}

/**
 * @returns {boolean}
 */
function isZipCodeValid(b=false) {
    let zipCode = document.querySelector('#zip-code').value;

    if (zipCode !== '') {
        if (/^[0-9]*$/.test(zipCode)) {
            if (zipCode.length === 5) {
                if (b)
                    getErrorMessage(false, 'zip-code');
                return true;
            } else {
                if (b)
                    getErrorMessage(true, 'zip-code', 12);
            }
        } else {
            if (b)
                getErrorMessage(true, 'zip-code', 12);
        }
    } else {
        if (b)
            getErrorMessage(true, 'zip-code', 11);
    }
    return false;
}

/**
 * @returns {boolean}
 */
function isPhoneNumberValid(b=false) {
    let phoneNumber = document.querySelector('#phone-number').value;

    if (phoneNumber !== '') {
        if (/^[0-9]*$/.test(phoneNumber)) {
            if (phoneNumber.length === 9) {
                if (b)
                    getErrorMessage(false, 'phone-number');
                return true;
            } else {
                if (b)
                    getErrorMessage(true, 'phone-number', 14);
            }
        } else {
            if (b)
                getErrorMessage(true, 'phone-number', 14);
        }
    } else {
        if (b)
            getErrorMessage(true, 'phone-number', 13);
    }
    return false;
}

/**
 * @returns {boolean}
 */
function isEmailValid(b=false) {
    let email = document.querySelector('#email').value;

    if (email !== '') {
        if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email)) {
            if (b)
                getErrorMessage(false, 'email');
            return true;
        } else {
            if (b)
                getErrorMessage(true, 'email', 16);
        }
    } else {
        if (b)
            getErrorMessage(true, 'email', 15);
    }
    return false;
}

/**
 * @returns {boolean}
 */
function isTermsValid(b=false, c=false) {
    let terms = document.querySelector('#terms');

    if (terms.checked) {
        if (b)
            getErrorMessage(false, 'terms');
        if (c) {
            submitButtonTrue.style.display = 'block';
            submitButtonFalse.style.display = 'none';
        }
        return true;
    }
    else {
        if (b)
            getErrorMessage(true, 'terms', 17);
        if (c) {
            submitButtonTrue.style.display = 'none';
            submitButtonFalse.style.display = 'block';
        }
        return false;
    }
}
