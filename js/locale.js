
let locale = currentLang ? currentLang: 'en'; //default value

let lang = {
    'en': {
        'required': 'This field is required',
        'email-valid': 'Please enter valid email',
        'password-length': 'Password should be at least 6 characters',
        'confirm-password': 'Please enter the same password as above',
    },
    'rus': {
        'required': 'Это поле обязательно к заполнению',
        'email-valid': 'Пожалуйста, введите действительный адрес электронной почты',
        'password-length': 'Пароль должен быть не менее 6 символов',
        'confirm-password': 'Пожалуйста, введите тот же пароль, что и выше',
    },
    'uk': {
        'required': 'Це поле є обов\'язковим',
        'email-valid': 'Будь ласка введіть вірну електронну пошту',
        'password-length': 'Пароль повинен містити не менше 6 символів',
        'confirm-password': 'Введіть той же пароль, що і вище',
    }
};

document.addEventListener("DOMContentLoaded", function(event) {
    document.getElementById('localization').addEventListener('change', handlerGetRequest);

    function handlerGetRequest() {
        let data = this.value;
        handlerAjax('GET', 'index.php?lang='+ data, '', renderingPage);
    }

    function renderingPage(response) { //after changing language
        var parser = new DOMParser();
        var htmlDoc = parser.parseFromString(response, 'text/html');
        let registrationForm = document.querySelector('.registration-form');
        registrationForm.innerHTML = "";
        document.querySelector('.container').replaceChild(htmlDoc.getElementsByClassName('registration-form')[0], registrationForm);
        location.reload();
    }
});