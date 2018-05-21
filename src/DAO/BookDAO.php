<?php
    namespace DAO;

    class BookDAO extends DAO {

        public function registerBookDatas($bookTitle, $bookAuthors, $bookPublishedDate, $bookDescription, $bookISBN, $bookNbPages){
            // Vérifier si l'id de session correspond à l'id du visiteur

            $sql = 'SELECT id_member FROM bookslist WHERE id_member = :id_member
                UNION
                SELECT ISBN FROM bookslist WHERE ISBN = :bookISBN';
            $result = $this->sql($sql, [
                'id_member' => $_SESSION['id'],
                'bookISBN' => $bookISBN
            ]);
            $row = $result->fetch();
            $currentISBN = $row['ISBN'];
            var_dump($row);
            die();


            if ($row['ISBN']){
                echo 'Vous avez déjà enregistré ce livre.';
                header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].'');
            } else {
                $sql = 'INSERT INTO bookslist(id_member, title, author, summary, publishingYear, ISBN, nbPages) VALUES(:idMember, :title, :author, :summary, :publishingYear, :ISBN, :nbPages)';
                $result = $this->sql($sql, [
                    'idMember' => $_SESSION['id'],
                    'title' => $bookTitle,
                    'author' => $bookAuthors,
                    'summary' => $bookDescription,
                    'publishingYear' => $bookPublishedDate,
                    'ISBN' => $bookISBN,
                    'nbPages' => $bookNbPages
                ]);
                header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].'');
            }

        }

    }