<?php

    namespace DAO;

    class AccountDAO extends DAO{
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