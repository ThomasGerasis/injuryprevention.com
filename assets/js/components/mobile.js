import intlTelInput from "intl-tel-input";

let siteUrl = window.location.origin;

export function initPhone(phoneNumberInput) {
    return intlTelInput(phoneNumberInput, {
        // any initialisation options go here
        separateDialCode: true,
        initialCountry: 'gr',
        preferredCountries: ["gr"],
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.21/js/utils.js"
    });
}

export function destroyMobile(phoneNumber) {
    phoneNumber.destroy();
}

export function isPhoneValid(phoneNumber) {
    if (!phoneNumber.isValidNumber()) {
        return false;
    }
    return phoneNumber.getNumber();
}

export function phoneValidation(sendOtpButton, errorHandler, phoneNumber, phoneNumberInput) {
    phoneNumberInput.addEventListener("input", (e) => {
        if (!getValidPhone(phoneNumber)) {
            sendOtpButton.disabled = true;
            errorHandler.classList.add('d-block');
            errorHandler.classList.remove('d-none');
            errorHandler.innerText = 'Please enter a real number';
        } else {
            sendOtpButton.disabled = false;
            errorHandler.classList.add('d-none');
            errorHandler.classList.remove('d-block');
            errorHandler.innerText = '';
        }
    });
}

export function getOtpInput(otpWrapper) {
    let otpValue = '';
    otpWrapper.forEach((element) => {
        otpValue += element.value;
    });
    return otpValue;
}


export function sendOtpMessage(sendOtpButton, phoneNumber, form, step, verifyTab) {
    sendOtpButton.onclick = function (event) {
        event.preventDefault();
        let ajaxUrl = siteUrl + '/ajaxFunctions/sendOtpMessage';
        let phoneNumberValue = isPhoneValid(phoneNumber);
        if (phoneNumber) {
            fetch(ajaxUrl, {
                method: "POST",
                headers: {
                    'Accept': 'application/json',
                    'Content-type': 'application/json',
                },
                body: JSON.stringify({
                    "email": form.querySelector('#email').value,
                    "mobile": phoneNumberValue,
                    "step": step,
                    "updateUserMobile": false
                })
            })
                .then(
                    response => response.json()
                )
                .then(data => {
                    if (!verifyTab) {
                        document.getElementById("nextBtn").click();
                        document.getElementById("sendAtMobile").innerText = maskCharacter(phoneNumberValue, '*', 3);
                    } else {
                        verifyTab.innerHTML = data;
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
}

export function verifyMobile(verifyOtpButton, phoneNumber, otpWrapper, errorHandler, step, form, email) {
    verifyOtpButton.onclick = function(event) {
        event.preventDefault();
        let ajaxUrl = siteUrl + '/ajaxFunctions/verifyOtp';
        let mobile = isPhoneValid(phoneNumber);
        let userInputOtp = getOtpInput(otpWrapper);
        let updateUserMobile = form.id !== 'signUpForm';
        fetch(ajaxUrl, {
            method: "POST",
            headers: {
                'Accept': 'application/json',
                'Content-type': 'application/json',
            },
            body: JSON.stringify({
                "mobile": mobile,
                "step": step,
                "otpCode": userInputOtp,
                "updateUserMobile": updateUserMobile,
                "email": email
            })
        })
            .then(response => response.json())
            .then(data => {
                if ('status' in data && data.status === 'approved') {
                    if (form.id === 'signUpForm') {
                        form.style.display = 'none';
                        document.getElementById("surveyFormWrapper").style.display = 'block';
                    } else {
                        form.querySelector('#verifyMobile').style.display = 'none';
                        form.querySelector('.tabs-wrapper').style.display = 'block';
                        errorHandler.classList.add('d-block', 'bg-success');
                        errorHandler.classList.remove('d-none');
                        errorHandler.innerText = 'Updated';
                    }
                } else {
                    errorHandler.classList.add('d-block');
                    errorHandler.classList.remove('d-none');
                    errorHandler.innerText = 'The password does not match the one we sent you in the message';

                    // reinitialize otp inputs
                    let otpInputs = document.querySelector("#otp");
                    let submitBtn = document.getElementById('verifyOtp');
                    otpWrapper.forEach((element) => {
                        element.value = "";
                    });
                    updateInputConfig(otpInputs.firstElementChild, false);
                    otpInputsTyping(otpWrapper, submitBtn, otpInputs);
                    setTimeout(() => {
                        errorHandler.innerText = '';
                        errorHandler.classList.add('d-none');
                        errorHandler.classList.remove('d-block');
                    }, 6500);
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }
}

export function maskCharacter(str, mask, n = 1) {
    // Slice the string and replace with
    // mask then add remaining string
    return [...str].reduce((acc, x, i) => (i < str.length - n) ? acc + mask : acc + x, '');
}

const updateInputConfig = (element, disabledStatus) => {
    element.disabled = disabledStatus;
    if (!disabledStatus) {
        element.focus();
    } else {
        element.blur();
    }
}

export function startInput(otpWrapper, otpInputs) {
    //Start
    let inputCount = 0;
    let finalInput = "";
    otpWrapper.forEach((element) => {
        element.value = "";
    });
    updateInputConfig(otpInputs.firstElementChild, false);
}

export function getValidPhone(phoneLibrary) {
    if (!phoneLibrary.isValidNumber()) {
        return false;
    }
    return phoneLibrary.getNumber();
}

export function otpInputsTyping(otpWrapper, submitButton, otpInputs) {
    let inputCount = 0, finalInput = "";
    //Update input
    otpWrapper.forEach((element) => {
        element.addEventListener("keyup", (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, "");
            let { value } = e.target;

            if (value.length === 1) {
                updateInputConfig(e.target, true);
                if (inputCount <= 5 && e.key !== "Backspace") {
                    if (inputCount < 5) {
                        updateInputConfig(e.target.nextElementSibling, false);
                    }
                    inputCount += 1;
                }
            }

            if (value.length > 1) {
                e.target.value = value.split("")[0];
            }

            if (value.length === 0 && e.key === "Backspace") {
                if (inputCount === 0) {
                    updateInputConfig(e.target, false);
                    return false;
                }
                updateInputConfig(e.target, true);
                e.target.previousElementSibling.value = "";
                updateInputConfig(e.target.previousElementSibling, false);
                inputCount -= 1;
            }

            document.getElementById('verifyOtp').disabled = true;
            submitButton.disabled = true;
        });
    });

    window.addEventListener("keyup", (e) => {
        if (inputCount > 5) {
            submitButton.disabled = false;
            document.getElementById('verifyOtp').disabled = false;
            if (e.key === "Backspace") {
                updateInputConfig(otpInputs.lastElementChild, false);
                otpInputs.lastElementChild.value = "";
                inputCount -= 1;
                submitButton.disabled = true;
                document.getElementById('verifyOtp').disabled = true;
            }
        }
    });

}

function checkIfMobileExistsInDatabase(mobileNumber, errorHandler) {
    if (!mobileNumber || mobileNumber.length !== 13) {
        return;
    }
    const formData = new FormData();
    formData.append('phone', mobileNumber);
    let ajaxUrl = siteUrl + '/ajaxFunctions/mobileExists';
    fetch(ajaxUrl, {
        method: "POST",
        body: formData
    })
        .then(
            response => response.json()
        )
        .then(data => {
            if (data.phone) {
                errorHandler.classList.add('d-block');
                errorHandler.classList.remove('d-none');
                errorHandler.innerText = data.phone;
                submitButton.disabled = true;
            } else {
                errorHandler.classList.add('d-none');
                errorHandler.classList.remove('d-block');
                submitButton.disabled = false;
            }
        })
        .catch(function(error) {
            return error;
        });
}

export function uniqueMobileHandler(phoneNumber, errorHandler) {
    const mobileInput = document.getElementById('phone');
    if (!mobileInput) {
        return;
    }
    mobileInput.addEventListener('keyup', () => {
        checkIfMobileExistsInDatabase(phoneNumber.getNumber(), errorHandler);
    });
}


