var checkForm = {

    highlight: function(input, error){
        if(error){
            input.style.backgroundColor = "#fba";
        } else {
            input.style.backgroundColor = "#A9D684";
        }
    },

    checkLogin: function(input){
        if(input.value.length < 2 || input.value.length > 25){
            this.highlight(input, true);
            return false;
        } else {
            this.highlight(input, false);
            return true;
        }
    },

    checkPassword: function(input){
        var regex = /^(?=.*\d)(?=.*[!@#$%^&*;?])(?=.*[a-z])(?=.*[A-Z]).{8,20}$/;
        if(!regex.test(input.value)){
            this.highlight(input, true);
            return false;
        } else {
            this.highlight(input, false);
            return true;
        }
    },

    checkConfirmationPassword: function(input){
        var passwordVisitor = document.getElementById("passwordVisitor");
        if (input.value != passwordVisitor.value){
            this.highlight(input, true);
            return false;
        } else {
            this.highlight(input, false);
            return true;
        }
    },

    checkConfirmationEditPassword: function(input){
        var editPassword = document.getElementById("editPassword");
        if (input.value != editPassword.value){
            this.highlight(input, true);
            return false;
        } else {
            this.highlight(input, false);
            return true;
        }
    },

    checkEmail: function(input){
        var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,6}$/;
        if(!regex.test(input.value)){
            this.highlight(input, true);
            return false;
        } else {
            this.highlight(input, false);
            return true;
        }
    },

    checkBirthDate: function(input){
        var regex = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
        if(!regex.test(input.value)){
            this.highlight(input, true);
            return false;
        } else {
            this.highlight(input, false);
            return true;
        }
    },

    finalCheckForm: function(f){
        var loginOk = this.checkLogin(f.login);
        var passwordOk = this.checkPassword(f.passwordVisitor);
        var confirmPassword = this.checkConfirmationPassword(f.passwordVisitorCheck);
        var emailOk = this.checkEmail(f.emailVisitor);
        var birthDateOk = this.checkBirthDate(f.birthDateVisitor);

        if (loginOk && passwordOk && confirmPassword && emailOk && birthDateOk){
            return true;
        } else {
            alert ("Veuillez remplir correctement tous les champs.");
            return false;
        }
    }
};