<?php
    namespace DAO;

    class BookDAO extends DAO {

        public function registerBookDatas($bookTitle, $bookAuthors, $bookPublishedDate, $bookDescription, $bookISBN, $bookNbPages){
            // Vérifier si l'id de session correspond à l'id du visiteur

            $sql = 'SELECT ISBN FROM bookslist WHERE ISBN = ?';
            $result = $this->sql($sql, ['$bookISBN']);
            $row = $result->fetch();

            if ($row){
                echo 'Vous avez déjà enregistré ce livre.';
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
            }
        }

    }