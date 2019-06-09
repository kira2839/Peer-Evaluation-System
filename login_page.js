function isValid(inputMailId) {
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
            postToServer();
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

function postToServer() {
    $.ajax({
        type: "POST",
        url: "send_mail.php",
        data: {email_id: document.email_form.email_id.value}
    }).done(function (msg) {
        document.getElementById("from_server_text").innerHTML = msg;
        document.getElementById("from_server_span").classList.add('ui-icon');
        document.getElementById("from_server_span").classList.add('ui-icon-circle-check');
    });
};

function isValidMailAndConfirmation(inputMailId, inputConfirmationCode) {
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
    }

}

function isValidConfirmation(inputConfirmationCode) {
    var confirmation_code = inputConfirmationCode.value;
    if (confirmation_code.length < 0) {
        document.getElementById("confirmation_code").classList.add('ui-state-error');
        document.getElementById("error_confirmation_text").innerHTML = "Invalid confirmation code";
        document.getElementById("error_confirmation_span").classList.add('ui-icon');
        document.getElementById("error_confirmation_span").classList.add('ui-icon-alert');
        return false;
    }
    return true;
}

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
}