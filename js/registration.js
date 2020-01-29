document.addEventListener("DOMContentLoaded", function(event) {
    let rules = {
        'first-name': {
            required: { required: true, error: lang[locale]['required'] },
        },

        'last-name': {
            required: { required: true, error: lang[locale]['required'] },
        },

        'email': {
            required: { required: true, error: lang[locale]['required'] },
            email: { email: true, error: lang[locale]['email-valid']},
        },

        'password' : {
            required: { required: true, error: lang[locale]['required'] },
            minLength: { minLength: 6, error: lang[locale]['password-length']},
        },

        'confirm-password' : {
            required: { required: true, error: lang[locale]['required'] },
            equal: { equal: true, error: lang[locale]['confirm-password']},
        }
    };

    function validateRules(name, value) {
        let result = '';
        Object.keys(rules).forEach(function (key) {
            if(key == name) {
                if(rules[key].required && value == '') {
                    result = rules[key].required.error;
                }
                else if(rules[key].minLength && value.length < 6) {
                    result =  rules[key].minLength.error;
                }
                else if(rules[key].equal) {
                    let password = document.getElementById('password').value;
                    if(value !== password) {
                        result =  rules[key].equal.error;
                    }
                }
                else if(rules[key].email) {
                    let regExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    if(!regExp.test(String(value).toLowerCase())) {
                        result =  rules[key].email.error;
                    }
                }
            }
        });
        return result;
    }

    let buttonRegistration = document.getElementById('user-registration-btn');

    //form submit
    buttonRegistration.addEventListener("click", function(event) {
        let form = document.forms["user-registration"];
        for (let i = 0; i < form.elements.length; i++) {
            if (form.elements[i].name === "") {
                continue;
            }

            switch (form.elements[i].nodeName) {
                case 'INPUT':
                    let event = new Event('input');
                    form.elements[i].dispatchEvent(event);
                    break;
                case 'file':
                    break;

            }
        }

        let errorsMsgElements = document.getElementsByClassName('err-msg');
        let errorsArray = [];

        for(let i = 0; i < errorsMsgElements.length; i++) {
            errorsArray.push(errorsMsgElements[i].innerText);
        }

        for(let i = 0; i < errorsArray.length; i++) {
            if(errorsArray[i].length == 0) {
                let data = new FormData(document.querySelector('#user-registration'));
                handlerAjax('POST','server/registration.php', data, displayResponseRegistrationUser);
                return;
            }
        }
    });

    //input change
    document.querySelectorAll('.input-group input').forEach(item => {
        item.addEventListener('input', event => {
            let siblings = item.parentElement.nextElementSibling;
            let error = validateRules(item.name, item.value);
            if(error) {
                showErrorMessage(error, siblings);
            } else {
                deleteErrorMessage(siblings);
            }

        })
    });

    //display error
    function showErrorMessage(error, siblings, className = 'err-msg') {
        let message = document.createTextNode(error);
        deleteErrorMessage(siblings); //delete previous error message
        siblings.appendChild(message);
    }

    function deleteErrorMessage(siblings, className = 'err-msg') {
        if(siblings.className == className) {
            if (siblings.hasChildNodes()) { // check if error message exists
                siblings.removeChild(siblings.childNodes[0]);
            }
        }
    }

    function displayResponseRegistrationUser(response) {
        let elementMsgResponse = document.getElementById("msg-response");
        if(!response.isSuccess) {
            changeClassName( elementMsgResponse, "success", "error");

        } else {
            changeClassName( elementMsgResponse, "error", "success");
        }

        elementMsgResponse.innerHTML = response.message;
    }

    function changeClassName(el, className1, className2) {
        if(el.classList.contains(className1)) {
            el.classList.remove(className1);
        }
        el.classList.add(className2);
    }

});




