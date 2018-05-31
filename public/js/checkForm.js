// Checks the inputs with set crateria
var checkForm = {

    /**
     *
     * @param input
     * @param error
     */
    highlight: function(input, error){
        if(error){
            input.style.backgroundColor = "#fba";
        } else {
            input.style.backgroundColor = "#A9D684";
        }
    },

    /**
     *
     * @param input
     * @returns {boolean}
     */
    checkLogin: function(input){
        if(input.value.length < 2 || input.value.length > 25){
            this.highlight(input, true);
            return false;
        } else {
            this.highlight(input, false);
            return true;
        }
    },

    /**
     *
     * @param input
     * @returns {boolean}
     */
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

    /**
     *
     * @param input
     * @returns {boolean}
     */
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

    /**
     *
     * @param input
     * @returns {boolean}
     */
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

    /**
     *
     * @param input
     * @returns {boolean}
     */
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

    checkPublishedYearInput: function(input){
        if(input.value.length !== 4){
            this.highlight(input, true);
            return false;
        } else {
            this.highlight(input, false);
            return false;
        }
    },

    /**
     *
     * @param f
     * @returns {boolean}
     */
    finalCheckForm: function(f){
        var loginOk = this.checkLogin(f.login);
        var passwordOk = this.checkPassword(f.passwordVisitor);
        var confirmPassword = this.checkConfirmationPassword(f.passwordVisitorCheck);
        var emailOk = this.checkEmail(f.emailVisitor);

        if (loginOk && passwordOk && confirmPassword && emailOk){
            return true;
        } else {
            alert ("Veuillez remplir correctement tous les champs.");
            return false;
        }
    }
};