<?php
    namespace DAO;

    use models\RegistrationForm;
    use DAO\MemberDAO;

    class ConnectionDAO extends DAO{

        // Register a new member
        public function registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor){

            $violations = [];

            $registrationValidator = new RegistrationForm();

            $violations['login'] = $registrationValidator->checkLogin($login);
            $violations['password'] = $registrationValidator->checkPassword($passwordVisitor);
            $violations['passwordConfirmation'] = $registrationValidator->checkPasswordConfirmation($passwordVisitorCheck, $passwordVisitor);
            $violations['email'] = $registrationValidator->checkEmail($emailVisitor);

            // Verifies if there is an error
            if(empty($violations['login']) && empty($violations['password']) && empty($violations['passwordConfirmation']) && empty($violations['email'])){

                // If there is not, the datas are registered
                $sql = 'SELECT login FROM members WHERE login = ?';
                $result = $this->sql($sql, [$login]);
                $row = $result -> fetch();

                $sqlEmail = 'SELECT email FROM members WHERE email = :email';
                $resultEmail = $this->sql($sqlEmail, [
                    'email' => $emailVisitor
                ]);
                $rowEmail = $resultEmail->fetch();

                // If the login or the email is already used, display a message
                // Otherwise, allows the visitor to register
                if($rowEmail){
                    echo 'Ce mail est déjà lié au compte d\'un(e) de nos membres.';
                }
                elseif($row){
                    echo 'Ce pseudo est déjà utilisé.';
                } else {
                    $passwordVisitorHashed = password_hash($passwordVisitor, PASSWORD_DEFAULT);

                    $sql = 'INSERT INTO members(login, password, email, registrationDate) VALUES (:login, :password, :email, NOW())';
                    $result = $this->sql($sql, [
                        'login' => $login,
                        'email' => $emailVisitor,
                        'password' => $passwordVisitorHashed
                    ]);

                    $email = $emailVisitor;
                    $subjectMail = 'Demande de confirmation d\'inscription';
                    $bodyMail = $login . ', afin de confirmer votre inscription, veuillez vous rendre sur cette <a href="http://localhost/P5/public/index.php?action=confirmRegistrationPage&login=' . $login . '">page</a>';
                    $sendEmailFunction = new MemberDAO();
                    $sendEmailFunction->sendEmail($email, $subjectMail, $bodyMail);
                }
            } else {
                // If there is, the error(s) is/are displayed
                foreach ($violations as $violation) {
                    if ($violation !== null){
                        echo $violation . '<br />';
                    }
                }

            }

        }

        public function confirmRegistration($login){
            $sql = 'UPDATE members SET confirmRegistration = :newValue WHERE login = :login';
            $this->sql($sql, [
                'newValue' => 1,
                'login' => $login
            ]);
        }

        public function refuseRegistration($login){
            $sql = 'DELETE FROM members WHERE login = :login';
            $this->sql($sql, [
                'login' => $login
            ]);
        }

        // Allows a member to connect to his/her personal space
        public function connection($loginConnection, $passwordVisitorConnection){

            $sql = 'SELECT id, login, password, email, status, confirmRegistration FROM members WHERE login = ?';
            $result = $this->sql($sql, [$loginConnection]);
            $row = $result -> fetch();

            // Verifies if the login is in the database
            if($row){
                if($row['confirmRegistration'] == 0){
                    // If the confirmation has not been done, redirect to this page
                    header('Location: ../public/index.php?action=awaitingRegistrationConfirmation&login=' . $loginConnection . '');

                } else {
                    $checkPassword = password_verify($passwordVisitorConnection, $row['password']);

                    // And if the password typed is the right one
                    if($checkPassword == true){
                        // Charging the credentials of the session
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['login'] = $row['login'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['status'] = $row['status'];

                        // Regarding the status of the member, the redirection is different
                        if($row['status'] == 'admin'){
                            header('Location: ../public/index.php?action=adminProfile&login=' .$_SESSION['login'].'');
                        } else {
                            header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].'');
                        }
                    } else {
                        echo 'Mauvais identifiant ou mot de passe.';
                    }
                }

            } else {
                echo 'Mauvais identifiant ou mot de passe.';
            }
        }

        // Allows the member/admin connected to log out
        public function logOut(){
            $_SESSION = array();
            session_destroy();
        }

    }