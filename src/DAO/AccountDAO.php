<?php

    namespace DAO;

    class AccountDAO extends DAO{

        private $connectionDAO;

        /**
         * AccountDAO constructor.
         */
        public function __construct(){
            $this->connectionDAO = new ConnectionDAO();
        }

        /**
         * @param $login
         * @param $password
         */
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
                    $errorMessage = 'Mauvais identifiant ou mot de passe.';
                    header('Location: ../public/index.php?action=error&errorMessage=' . $errorMessage . '');
                }
            }

        }

        /**
         * @param $login
         */
        // Edit the login
        public function editLogin($login){

            $sql = 'SELECT login FROM members WHERE login = :login';
            $result = $this->sql($sql, [
                'login' => $login
            ]);
            $row = $result->fetch();

            if($row){
                $errorMessage = 'Ce pseudo est déjà utilisé.';
                header('Location: ../public/index.php?action=error&errorMessage=' . $errorMessage . '');

            } else {
                $sql = 'UPDATE members SET login = :login WHERE id = :id';
                $this->sql($sql, [
                    'login' => $login,
                    'id' => $_SESSION['id']
                ]);

                $this->connectionDAO->logOut();
                header('Location: ../public/index.php');
            }
        }

        /**
         * @param $password
         * @param $confirmPassword
         */
        // Edit the password
        public function editPassword($password, $confirmPassword){

            if($password == $confirmPassword){
                $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

                $sql = 'UPDATE members SET password = :password WHERE id = :id';
                $this->sql($sql, [
                    'id' => $_SESSION['id'],
                    'password' => $passwordHashed
                ]);

                $this->connectionDAO->logOut();
                header('Location: ../public/index.php');
            } else {
                $errorMessage ='Vous n\'avez pas renseigné les mêmes mots de passe.';
                header('Location: ../public/index.php?action=error&errorMessage=' . $errorMessage . '');
            }
        }

        /**
         * @param $editMail
         * @param $confirmEditMail
         */
        // Edit the email
        public function editMail($editMail, $confirmEditMail){

            if($editMail == $confirmEditMail){
                $sql = 'SELECT email FROM members WHERE email = :email';
                $result = $this->sql($sql, [
                    'email' => $editMail
                ]);
                $row = $result->fetch();

                if($row){
                    $errorMessage = 'Votre email est déjà lié à un autre compte.';
                    header('Location: ../public/index.php?action=error&errorMessage=' . $errorMessage . '');
                } else {
                    $sql = 'UPDATE members SET email = :email WHERE id = :id';
                    $this->sql($sql, [
                        'email' => $editMail,
                        'id' => $_SESSION['id']
                    ]);
                    $this->connectionDAO->logOut();
                    header('Location: ../public/index.php');
                }

            } else {
                $errorMessage = 'Vous n\'avez pas renseigné les mêmes emails.';
                header('Location: ../public/index.php?action=error&errorMessage=' . $errorMessage . '');
            }
        }

        /**
         * @param $id
         * @param $login
         * @param $password
         */
        // Delete the account and all elements in the database
        public function deleteAccount($id, $login, $password){
            $sql = 'SELECT id, password FROM members WHERE login = :login';
            $result = $this->sql($sql, [
                'login' => $login
            ]);
            $row = $result->fetch();

            if($row){
                $confirmPassword = password_verify($password, $row['password']);

                // Confirming if the password if false or not
                if($confirmPassword == false){
                    // If the password is false
                    $errorMessage = 'Mauvais identifiant ou mot de passe';
                    header('Location: ../public/index.php?action=error&errorMessage=' . $errorMessage . '');

                } else {
                    // If the password if true
                    // Deletion of all the tables of the database
                    $this->deleteMemberAndBooklist($id);

                    // Then checking if the member is registered as having booklist of others members
                    $this->deleteShareBooklist($login);

                    // Finally, checking if the member if registered as sharing his/her booklist
                    // If yes, the mentions of his/her login are updated to NULL
                    $this->deleteSharedBooklistWith($login);


                    header('Location: ../public/index.php');
                }
            }
        }

        /**
         * @param $id
         */
        // Delete the member from the members table and from the booklist table if necessary
        public function deleteMemberAndBooklist($id){
            $sql = 'SELECT * FROM bookslist WHERE id_member = :id';
            $result = $this->sql($sql, [
                'id' => $id
            ]);
            $row = $result->fetch();

            // First checking if there is a booklist associated with the account
            if($row){
                // If yes, both are deleted
                $this->connectionDAO->logOut();
                $sql = 'DELETE members, bookslist FROM members INNER JOIN bookslist ON (members.id = bookslist.id_member) WHERE members.id = :id';
                $this->sql($sql, [
                    'id' => $id
                ]);

            } else {
                // Otherwise, only the account is deleted
                $this->connectionDAO->logOut();
                $sql = 'DELETE FROM members WHERE id = :id';
                $this->sql($sql, [
                    'id' => $id
                ]);

            }
        }

        /**
         * @param $login
         */
        // Delete the persons who were sharing their booklists with the member who wants to delete his/her account
        public function deleteShareBooklist($login){
            $sqlShareBooklist = 'SELECT * FROM sharedbooklist WHERE login_member = :login';
            $resultShareBookist = $this->sql($sqlShareBooklist, [
                'login' => $login
            ]);
            $rowShareBooklist = $resultShareBookist -> fetch();

            if($rowShareBooklist){
                // If yes, the line is deleted
                $this->connectionDAO->logOut();
                $sqlBooklist = 'DELETE FROM sharedbooklist WHERE login_member = :login';
                $this->sql($sqlBooklist, [
                    'login' => $login
                ]);
            }
        }

        /**
         * @param $login
         */
        // Delete the member from the lists of persons he/she shared his/her booklist with
        public function deleteSharedBooklistWith($login){
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
        }

    }