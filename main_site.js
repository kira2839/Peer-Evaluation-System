function isValid(inputMailId) {
    clearClass();
    var email_id = inputMailId.value.toLowerCase();

    if (email_id.endsWith("@buffalo.edu")) {
        if (!(email_id === ".@buffalo.edu")) {
            if (!((email_id.match(/\./g) || []).length < 3 && (email_id.match(/@/g) || []).length < 2 && email_id.split("@")[0].match("^[a-z0-9.]+$"))) {
                document.getElementById("email_id").classList.add('ui-state-error');
                document.getElementById("error_text").innerHTML = "Valid Email ID can contain only letters, numbers and '.'";
                document.getElementById("error_span").classList.add('ui-icon');
                document.getElementById("error_span").classList.add('ui-icon-alert');
                return false;
            }
            postToServerForSendingMail();
            return false;
        } else {
            document.getElementById("email_id").classList.add('ui-state-error');
            document.getElementById("error_text").innerHTML = "Invalid Email Address";
            document.getElementById("error_span").classList.add('ui-icon');
            document.getElementById("error_span").classList.add('ui-icon-alert');
            return false;
        }
    } else {
        document.getElementById("email_id").classList.add('ui-state-error');
        document.getElementById("error_text").innerHTML = "Kindly make sure that you enter your UB Email Address";
        document.getElementById("error_span").classList.add('ui-icon');
        document.getElementById("error_span").classList.add('ui-icon-alert');
        return false;
    }
}

function postToServerForSendingMail() {
    $.ajax({
        type: "POST",
        url: "backend/send_mail.php",
        data: {email_id: document.email_form.email_id.value}
    }).done(function (msg) {
        if (msg.includes("Thank")) {
            document.getElementById("from_server_text").innerHTML = msg;
            document.getElementById("from_server_span").classList.add('ui-icon');
            document.getElementById("from_server_span").classList.add('ui-icon-circle-check');
        } else {
            document.getElementById("error_text").innerHTML = msg;
            document.getElementById("error_span").classList.add('ui-icon');
            document.getElementById("error_span").classList.add('ui-icon-alert');
        }
    });
};

function isValidMailAndConfirmation(inputMailId, inputConfirmationCode) {
    clearClassTab2();
    var email_id = inputMailId.value.toLowerCase();

    if (email_id.endsWith("@buffalo.edu")) {
        if (!(email_id === ".@buffalo.edu")) {
            if (!((email_id.match(/\./g) || []).length < 3 && (email_id.match(/@/g) || []).length < 2 && email_id.split("@")[0].match("^[a-z0-9.]+$"))) {
                document.getElementById("email_id_tab2").classList.add('ui-state-error');
                document.getElementById("error_text_tab2").innerHTML = "Valid Email ID can contain only letters, numbers and '.'";
                document.getElementById("error_span_tab2").classList.add('ui-icon');
                document.getElementById("error_span_tab2").classList.add('ui-icon-alert');
                return false;
            }
        } else {
            document.getElementById("email_id_tab2").classList.add('ui-state-error');
            document.getElementById("error_text_tab2").innerHTML = "Invalid Email Address";
            document.getElementById("error_span_tab2").classList.add('ui-icon');
            document.getElementById("error_span_tab2").classList.add('ui-icon-alert');
            return false;
        }
    } else {
        document.getElementById("email_id_tab2").classList.add('ui-state-error');
        document.getElementById("error_text_tab2").innerHTML = "Kindly make sure that you enter your UB Email Address";
        document.getElementById("error_span_tab2").classList.add('ui-icon');
        document.getElementById("error_span_tab2").classList.add('ui-icon-alert');
        return false;
    }

    var confirmation_code = inputConfirmationCode.value;
    if (confirmation_code.length < 0) {
        document.getElementById("confirmation_code").classList.add('ui-state-error');
        document.getElementById("error_confirmation_text").innerHTML = "Invalid confirmation code";
        document.getElementById("error_confirmation_span").classList.add('ui-icon');
        document.getElementById("error_confirmation_span").classList.add('ui-icon-alert');
        return false;
    }

    postToServerForConfirmationCodeValidation();
    return false;

}

function postToServerForConfirmationCodeValidation() {
    $.ajax({
        type: "POST",
        url: "backend/confirmation_code_validation.php",
        data: {
            email_id_tab2: document.email_form_tab2.email_id_tab2.value,
            confirmation_code: document.email_form_tab2.confirmation_code.value
        }
    }).done(function (msg) {
        if (msg.includes("Validated")) {
            document.getElementById("from_server_cr_text").innerHTML = msg;
            document.getElementById("from_server_cr_span").classList.add('ui-icon');
            document.getElementById("from_server_cr_span").classList.add('ui-icon-circle-check');
        } else {
            document.getElementById("error_text_tab2").innerHTML = msg;
            document.getElementById("error_span_tab2").classList.add('ui-icon');
            document.getElementById("error_span_tab2").classList.add('ui-icon-alert');
        }
    });
};

function clearClass() {
    document.getElementById("email_id").classList.remove('ui-state-error');
    document.getElementById("error_text").innerHTML = "";
    document.getElementById("error_span").classList.remove('ui-icon-alert');
    document.getElementById("error_span").classList.remove('ui-icon');
    document.getElementById("from_server_text").innerHTML = "";
    document.getElementById("from_server_span").classList.remove('ui-icon-circle-check');
    document.getElementById("from_server_span").classList.remove('ui-icon');
}

function clearClassTab2() {
    document.getElementById("email_id_tab2").classList.remove('ui-state-error');
    document.getElementById("error_text_tab2").innerHTML = "";
    document.getElementById("error_span_tab2").classList.remove('ui-icon-alert');
    document.getElementById("error_span_tab2").classList.remove('ui-icon');

    document.getElementById("confirmation_code").classList.remove('ui-state-error');
    document.getElementById("error_confirmation_text").innerHTML = "";
    document.getElementById("error_confirmation_span").classList.remove('ui-icon-alert');
    document.getElementById("error_confirmation_span").classList.remove('ui-icon');

    document.getElementById("from_server_cr_text").innerHTML = "";
    document.getElementById("from_server_cr_span").classList.remove('ui-icon-circle-check');
    document.getElementById("from_server_cr_span").classList.remove('ui-icon');
}