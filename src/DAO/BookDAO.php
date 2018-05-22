<?php
    namespace DAO;

    class BookDAO extends DAO {

        public function registerBookDatas($id, $bookTitle, $bookAuthors, $bookPublishedDate, $bookDescription, $bookISBN, $bookNbPages){
            $sql = 'SELECT ISBN, id_member FROM bookslist WHERE id_member = :idMember';
            $result = $this->sql($sql, [
                'idMember' => $id
            ]);
            $row = $result->fetch();

            if ($row['id_member'] == $id){
                echo 'Vous avez déjà enregistré ce livre.';
            } else {
                $sql = 'INSERT INTO bookslist(id_member, title, author, summary, publishingYear, ISBN, nbPages) VALUES(:idMember, :title, :author, :summary, :publishingYear, :ISBN, :nbPages)';
                $result = $this->sql($sql, [
                    'idMember' => $id,
                    'title' => $bookTitle,
                    'author' => $bookAuthors,
                    'summary' => $bookDescription,
                    'publishingYear' => $bookPublishedDate,
                    'ISBN' => $bookISBN,
                    'nbPages' => $bookNbPages
                ]);
                echo 'Votre livre a bien été enregistré.';
            }

            header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].'');
        }

        public function deleteBook($id){
            $sql = 'DELETE FROM bookslist WHERE id = :id ';
            $result = $this->sql($sql, [
                'id' => $id
            ]);

            header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].'');

        }

    }