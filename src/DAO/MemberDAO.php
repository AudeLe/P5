<?php
    namespace DAO;

    use models\BookList;

    class MemberDAO extends DAO{
        public function getMemberBookList(){
            $sql = 'SELECT id, author, title, summary, publishingYear, ISBN, nbPages FROM bookslist WHERE id_member = :idMember';
            $result = $this->sql($sql, [
                'idMember' => $_SESSION['id']
            ]);
            $row = $result->fetch();

            $memberBookList = [];
            foreach($result as $row){
                $bookId = $row['id'];
                $memberBookList[$bookId] = $this->buildObject($row);
            }

            return $memberBookList;

        }

        private function buildObject(array $row){
            $bookList = new BookList();
            $bookList->setAuthor($row['author']);
            $bookList->setTitle($row['title']);
            $bookList->setIdMember($row['id_member']);
            $bookList->setISBN($row['ISBN']);
            $bookList->setSummary($row['summary']);
            $bookList->setPublishingYear($row['publishingYear']);
            $bookList->setNbPages($row['nbPages']);
        }
    }