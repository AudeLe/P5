<?php
    namespace DAO;

    use models\BookList;

    class MemberDAO extends DAO{
        public function getMemberBookList(){
            $sql = 'SELECT id, author, title, summary, publishingYear, ISBN, nbPages FROM bookslist WHERE id_member = :id_member';
            $result = $this->sql($sql, [
                'id_member' => $_SESSION['id']
            ]);
            //$row = $result->fetch();

            $memberBookList = [];
            foreach($result as $row){
                $bookId = $row['id'];
                $memberBookList[$bookId] = $this->buildObject($row);
            }
            //var_dump($memberBookList);
            //die();
            return $memberBookList;

        }

        private function buildObject(array $row){
            $book = new BookList();
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