<?php
    namespace DAO;

    use models\RegistrationForm;

    class ConnectionDAO extends DAO{

        // Register a new member
        public function registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor){
            // If returns and error, the rest of the code shouldn't execute !!
            $this->checkRegistration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor);

            $sql = 'SELECT login FROM members WHERE login = ?';
            $result = $this->sql($sql, [$login]);
            $row = $result -> fetch();

            // If the login is already used, display a message
            // Otherwise, allows the visitor to register
            if($row){
                echo 'Ce pseudo est déjà utilisé.';
            } else {
                // Check if the visitor has typed the same passwords
                if($passwordVisitor == $passwordVisitorCheck){

                    $passwordVisitorHashed = password_hash($passwordVisitor, PASSWORD_DEFAULT);

                    $sql = 'INSERT INTO members(login, password, email, registrationDate) VALUES (:login, :password, :email, NOW())';
                    $result = $this->sql($sql, [
                        'login' => $login,
                        'email' => $emailVisitor,
                        'password' => $passwordVisitorHashed
                    ]);
                } else {
                    echo 'Vous n\'avez pas saisi les mêmes mots de passe.';
                }
            }
        }

        // Allows a member to connect to his/her personal space
        public function connection($loginConnection, $passwordVisitorConnection){

            $sql = 'SELECT id, login, password, status FROM members WHERE login = ?';
            $result = $this->sql($sql, [$loginConnection]);
            $row = $result -> fetch();

            // Verifies if the login is in the database
            if($row){
                $checkPassword = password_verify($passwordVisitorConnection, $row['password']);

                // And if the password typed is the right one
                if($checkPassword == true){
                    // Charging the credentials of the session
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['login'] = $row['login'];
                    $_SESSION['status'] = $row['status'];

                    // Regarding the status of the member, the redirection is different
                    if($row['status'] == 'admin'){
                        header('Location: ../public/index.php?action='); /* ACTION A DETERMINER */
                    } else {
                        header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].''); /* ACTION A DETERMINER */
                    }
                } else {
                    echo 'Mauvais identifiant ou mot de passe.';
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

        // Verify the credentials before allowing the member/admin to change them
        public function verifyInformations($login, $password){
            $sql = 'SELECT id, password FROM members WHERE login = :login';
            $result = $this->sql($sql, [
                'login' => $login
            ]);
            $row = $result->fetch();

            if($row){
                $confirmPassword = password_verify($password, $row['password']);

                if($confirmPassword == false){
                    echo 'Mauvais identifiant ou mot de passe';
                    if($_SESSION['status'] == 'admin'){
                        header('Location: ../public/index.php?action='/*ACTION A DETERMINER*/);
                    } else {
                        header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].'');
                    }
                }
            }
        }

        // Edit the login
        public function editLogin($login){
            $sql = 'SELECT login FROM members WHERE login = :login';
            $result = $this->sql($sql, [
                'login' => $login
            ]);
            $row = $result->fetch();

            if($row){
                echo 'Ce pseudo est déjà utilisé';
            } else {
                $sql = 'UPDATE members SET login = :login WHERE id = :id';
                $result = $this->sql($sql, [
                    'login' => $login,
                    'id' => $_SESSION['id']
                ]);
            }
             header('Location: ../public/index.php');
        }

        // Edit the password
        public function editPassword($password, $confirmPassword){
            if($password == $confirmPassword){
                $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

                $sql = 'UPDATE members SET password = :password WHERE id = :id';
                $this->sql($sql, [
                    'id' => $_SESSION['id'],
                    'password' => $passwordHashed
                ]);

                header('Location: ../public/index.php');
            } else {
                echo 'Votre mot de passe n\'a pas pu être modifié.';
            }
        }

        // Delete the account
        public function deleteAccount($id, $login, $password){
            $sql = 'SELECT id, password FROM members WHERE login = :login';
            $result = $this->sql($sql, [
                'login' => $login
            ]);
            $row = $result->fetch();

            if($row){
                $confirmPassword = password_verify($password, $row['password']);

                if($confirmPassword == false){
                    echo 'Mauvais identifiant ou mot de passe';
                    header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].'');
                } else {
                    $this->logOut();
                    $sql = 'DELETE FROM members WHERE id = :id';
                    $this->sql($sql, [
                        'id' => $id
                    ]);

                    header('Location: ../public/index.php');
                }
            }
        }


        private function checkRegistration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor){
            $registrationForm = new RegistrationForm();
            $registrationForm->checkLogin($login);
            $registrationForm->checkPassword($passwordVisitor, $passwordVisitorCheck);
            //$registrationForm->checkPasswordConfirmation($passwordVisitor, $passwordVisitorCheck);
            $registrationForm->checkEmail($emailVisitor);

            //return $violations;
        }
    }