<?php
    namespace DAO;

    use models\BookList;

    class MemberDAO extends DAO{
        public function getMemberBookList(){
            $sql = 'SELECT id, author, title, summary, publishingYear, ISBN, nbPages FROM bookslist WHERE id_member = :id_member';
            $result = $this->sql($sql, [
                'id_member' => $_SESSION['id']
            ]);

            $memberBookList = [];
            foreach($result as $row){
                $bookId = $row['id'];
                $memberBookList[$bookId] = $this->buildObject($row);
            }

            return $memberBookList;

        }

        // Verify if a book has been registered
        public function searchBook($id, $ISBN){
            $sql = 'SELECT id_member, author, title, ISBN FROM bookslist WHERE ISBN = :ISBN';
            $result = $this->sql($sql, [
                'ISBN' => $ISBN
            ]);
            $row = $result->fetch();

            if($row['id_member'] == $id){
                $message = 'Vous avez déjà enregistré cet ouvrage.';
            } else {
                $message = 'Vous n\'avez pas ou n\'avez pas encore enregistré cet ouvrage.';
            }

            //header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].'');
            return $message;
        }

        private function buildObject(array $row){
            $book = new BookList();
            $book->setId($row['id']);
            $book->setAuthor($row['author']);
            $book->setTitle($row['title']);
            //$book->setIdMember($row['id_member']);
            $book->setISBN($row['ISBN']);
            $book->setSummary($row['summary']);
            $book->setPublishingYear($row['publishingYear']);
            $book->setNbPages($row['nbPages']);
            return $book;
        }
    }