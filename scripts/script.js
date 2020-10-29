let formInputs = document.querySelectorAll('.form-item');

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