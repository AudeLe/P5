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

                // If the login is already used, display a message
                // Otherwise, allows the visitor to register
                if($row){
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

        public function editMail($editMail, $confirmEditMail){
            if($editMail == $confirmEditMail){
                $sql = 'UPDATE members SET email = :email WHERE id = :id';
                $this->sql($sql, [
                    'email' => $editMail,
                    'id' => $_SESSION['id']
                ]);

                header('Location: ../public/index.php');
            } else {
                echo 'Votre email n\'a pas pu être modifié.';
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
                    $sql = 'SELECT * FROM bookslist WHERE id_member = :id';
                    $result = $this->sql($sql, [
                        'id' => $id
                    ]);
                    $row = $result->fetch();

                    if($row){
                        $this->logOut();
                        $sql = 'DELETE members, bookslist FROM members INNER JOIN bookslist ON (members.id = bookslist.id_member) WHERE members.id = :id';
                        $this->sql($sql, [
                            'id' => $id
                        ]);

                    } else {
                            $this->logOut();
                            $sql = 'DELETE FROM members WHERE id = :id';
                            $this->sql($sql, [
                                'id' => $id
                            ]);

                    }

                    $sqlShareBooklist = 'SELECT * FROM sharedbooklist WHERE login_member = :login';
                    $resultShareBookist = $this->sql($sqlShareBooklist, [
                        'login' => $login
                    ]);
                    $rowShareBooklist = $resultShareBookist -> fetch();

                    if($rowShareBooklist){
                        $this->logOut();
                        $sqlBooklist = 'DELETE FROM sharedbooklist WHERE login_member = :login';
                        $this->sql($sqlBooklist, [
                            'login' => $login
                        ]);
                    }

                    $sql = 'SELECT login_share_booklist_1, login_share_booklist_2, login_share_booklist_3 FROM sharedbooklist';
                    $result = $this->sql($sql);
                    $row = $result->fetch();

                    if($row['login_share_booklist_1'] == $login){
                        $sql = 'UPDATE sharedbooklist SET login_share_booklist_1 = :newValue WHERE login_share_booklist_1 = :login';
                        $this->sql($sql, [
                            'newValue' => NULL,
                            'login' => $login
                        ]);

                    } elseif ($row['login_share_booklist_2'] == $login) {
                        $sql = 'UPDATE sharedbooklist SET login_share_booklist_2 = :newValue WHERE login_share_booklist_2 = :login';
                        $this->sql($sql, [
                            'newValue' => NULL,
                            'login' => $login
                        ]);
                    } elseif ($row['login_share_booklist_3'] == $login) {
                        $sql = 'UPDATE sharedbooklist SET login_share_booklist_3 = :newValue WHERE login_share_booklist_3 = :login';
                        $this->sql($sql, [
                            'newValue' => NULL,
                            'login' => $login
                        ]);
                    }
                    header('Location: ../public/index.php');
                }
            }
        }

    }