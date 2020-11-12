if (document.querySelector('.container')) {
    const maxQuantity = 1000;
    const bookPrice = 499;
    const errorMessages = [
        'Zadejte požadovanou hodnotu!',
        'Zadejte číslo!',//--
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
        'quantity': 'isQuantityValid(true)',
        'first-name': 'isFirstNameValid(true)',
        'last-name': 'isLastNameValid(true)',
        'street': 'isStreetValid(true)',
        'town': 'isTownValid(true)',
        'zip-code': 'isZipCodeValid(true)',
        'phone-number': 'isPhoneNumberValid(true)',
        'email': 'isEmailValid(true)',
        'terms': 'isTermsValid(true)'
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
        value.addEventListener('click', function () {
            validateAllInputs();
        });
        value.addEventListener('keyup', function () {
            validateAllInputs();
        });
    });

    document.addEventListener('readystatechange', function () {
        sumFullPrice();
    });

    quantity.addEventListener('keyup', function () {
        sumFullPrice();
    });

    let transportMethod = document.querySelectorAll('.radio');

    transportMethod.forEach(value => {
        value.addEventListener('click', function () {
            sumFullPrice();
        })
    });

    function sumFullPrice() {
        if (parseInt(quantity.value) < 1 || quantity.value === '' || parseInt(quantity.value) > maxQuantity) {
            totalPrice.textContent = '0';
        } else {
            let transportPrice
            if (document.querySelector('#radio1:checked')) {
                transportPrice = document.querySelector('#radio1:checked');
            } else {
                transportPrice = document.querySelector('#radio2:checked');
            }
            let price = bookPrice * parseInt(quantity.value) + parseInt(transportPrice.dataset.price);
            totalPrice.textContent = stylePrice(price);
        }
    }

    function stylePrice(price) {
        return price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ')
    }

    /**
     * return true if is numeric
     * @param event
     * @returns {boolean}
     */
    const isNumericInput = (event) => {
        const key = event.key;
        return ((key >= 0 && key <= 9));
    };

    /**
     * return true if is: tab, ctrl + a/c/v/x
     * @param event
     * @returns {boolean}
     */
    const isModifierKey = (event) => {
        const key = event.key;
        return key === 'Tab' || key === 'Backspace' || key === 'Delete' || key === 'ArrowLeft' || key === 'F5'
            || key === 'ArrowRight' || key === 'ArrowUp' || key === 'ArrowDown' ||
            ((event.ctrlKey === true || event.metaKey === true) && (key === 'c' || key === 'v' || key === 'a' || key === 'x'));
    };

    /**
     * if is not isNumericInput && isModifierKey input is blocked
     * @param event
     */
    const enforceFormat = (event) => {
        if (!isNumericInput(event) && !isModifierKey(event)) {
            event.preventDefault();
        }
    };

    /**
     * format phone input
     * @param event
     */
    const formatPhone = (event) => {
        if (isModifierKey(event))
            return;

        const target = event.target;
        const input = event.target.value.replace(/\D/g, '').substring(0, 10);
        const zip = input.substring(0, 3);
        const middle = input.substring(3, 6);
        const last = input.substring(6, 9);

        if (input.length > 6) {
            target.value = `${zip} ${middle} ${last}`;
        } else if (input.length > 3) {
            target.value = `${zip} ${middle}`;
        } else if (input.length > 0) {
            target.value = `${zip}`;
        }
    };

    const phoneNumberInput = document.querySelector('#phone-number');

    phoneNumberInput.addEventListener('keydown', enforceFormat);
    phoneNumberInput.addEventListener('keyup', formatPhone);

    /**
     * format zip code input
     * @param event
     */
    const formatZipCode = (event) => {
        if (isModifierKey(event))
            return;

        const target = event.target;
        const input = event.target.value.replace(/\D/g, '').substring(0, 5);
        const zip = input.substring(0, 3);
        const last = input.substring(3, 5);

        if (input.length > 3) {
            target.value = `${zip} ${last}`;
        } else {
            target.value = `${zip}`;
        }
    };

    const zipCodeInput = document.querySelector('#zip-code');

    zipCodeInput.addEventListener('keydown', enforceFormat);
    zipCodeInput.addEventListener('keyup', formatZipCode);


    let submitButton = document.querySelector('.submit-button');

    submitButton.addEventListener('click', function (e) {
        if (validateAllInputs() === false) {
            e.preventDefault();
            validateAllInputs(true);
        }
        console.log('sad');
    });

    function validateInput(type) {
        eval(validationTypeArray[type]);
    }

    function validateAllInputs(message = false) {
        const arr = [
            isQuantityValid(message),
            isFirstNameValid(message),
            isLastNameValid(message),
            isStreetValid(message),
            isTownValid(message),
            isZipCodeValid(message),
            isPhoneNumberValid(message),
            isEmailValid(message),
            isTermsValid(message)
        ];

        let err = false;
        arr.forEach(value => {
            if (value === false)
                err = true;
        })
        return err === false;
    }

    /**
     * Shows error message to user
     * @param message
     * @param type
     * @param errorNum
     */
    function getErrorMessage(message, type, errorNum = null) {
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
    function isQuantityValid(b = false) {
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
                    getErrorMessage(true, 'quantity', 2);
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
    function isFirstNameValid(b = false) {
        let firstName = document.querySelector('#first-name').value;

        if (firstName !== '') {
            if (/^[A-Za-zÁáČčĎďÉéĚěÍíŇňÓóŘřŠšŤťŮůÚúÝýŽž ]*$/.test(firstName)) {
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
    function isLastNameValid(b = false) {
        let lastName = document.querySelector('#last-name').value;

        if (lastName !== '') {
            if (/^[A-Za-zÁáČčĎďÉéĚěÍíŇňÓóŘřŠšŤťŮůÚúÝýŽž ]*$/.test(lastName)) {
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
    function isStreetValid(b = false) {
        let street = document.querySelector('#street').value;

        if (street !== '') {
            if (/^[A-Za-zÁáČčĎďÉéĚěÍíŇňÓóŘřŠšŤťŮůÚúÝýŽž ]+[ ]+[0-9]/.test(street) || /^[0-9]/.test(street)) {
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
    function isTownValid(b = false) {
        let town = document.querySelector('#town').value;

        if (town !== '') {
            if (/^[A-Za-zÁáČčĎďÉéĚěÍíŇňÓóŘřŠšŤťŮůÚúÝýŽž ]+[ ]+[0-9]/.test(town) || /^[A-Za-zÁáČčĎďÉéĚěÍíŇňÓóŘřŠšŤťŮůÚúÝýŽž ]/.test(town)) {
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
    function isZipCodeValid(b = false) {
        let zipCode = document.querySelector('#zip-code').value;

        if (zipCode !== '') {
            if (/^[0-9 ]*$/.test(zipCode)) {
                zipCode = zipCode.replaceAll(' ', '');
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
    function isPhoneNumberValid(b = false) {
        let phoneNumber = document.querySelector('#phone-number').value;

        if (phoneNumber !== '') {
            if (/^[0-9 ]/.test(phoneNumber)) {
                phoneNumber = phoneNumber.replaceAll(' ', '');
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
    function isEmailValid(b = false) {
        let email = document.querySelector('#email').value;

        if (email !== '') {
            if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+[@]+[a-zA-Z0-9-]+[.]+[a-zA-Z0-9-.]/.test(email)) {
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
    function isTermsValid(b = false) {
        let terms = document.querySelector('#terms');

        if (terms.checked) {
            if (b)
                getErrorMessage(false, 'terms');
            return true;
        } else {
            if (b)
                getErrorMessage(true, 'terms', 17);
            return false;
        }
    }
}
