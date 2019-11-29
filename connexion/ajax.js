
    function inscription () {
        // Je recupere les valeurs des inputs
        let valEmail = $("#email").val();
        let valDuPass = $("#password").val();
        let valDuConfirm = $("#confirmPassword").val();

        // On execute une requete ajax avec les valeurs d'inputs
        $.ajax({
            url: "http://localhost/inscription.php",
            type: "POST",
            data: {
                email: valEmail,
                password: valDuPass,
                confirmPassword: valDuConfirm
            },
            success: function success (result) {
                alert(result);
            },
            error: function error (erreur) {
                console.log(erreur);
            }
        });
    }

    function connexion () {
        // Je recupere les valeurs des inputs
        let valEmail = $("#inscr_email").val();
        let valDuPass = $("#inscr_password").val();

        // On execute une requete ajax avec les valeurs d'inputs
        $.ajax({
            url: "http://localhost/connexion.php",
            type: "POST",
            data: {
                email: valEmail,
                password: valDuPass
            },
            success: function success (result) {
                alert(result);
            },
            error: function error (erreur) {
                console.log(erreur);
            }
        });
    }
