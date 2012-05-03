function checkForm(formObj) {

    formErrors = false;    

    if (formObj.message.value.length < 2) {
        formErrors = "You must enter a message to be e-mailed.";
    }
    else if ( formObj.subject.value.length < 2)
    {
        formErrors = "You must specify a subject for the e-mail.";
    }

    if (formErrors) {
        alert(formErrors);
        return false;
    }
}