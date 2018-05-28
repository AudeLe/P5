<?php
    namespace DAO;

    use models\BookDatasForm;


    class BookDAO extends DAO {

        protected $commonFunctionalities;

        public function __construct(){
            $this->commonFunctionalities = new CommonFunctionalitiesDAO();
        }

        public function registerBookDatas($id, $bookTitle, $bookAuthors, $bookPublishedDate, $bookDescription, $bookISBN, $bookNbPages){
            $violations = [];

            $bookFormValidator = new BookDatasForm();

            $violations['bookTitle'] = $bookFormValidator->inputNotBlank($bookTitle);
            $violations['bookAuthors'] = $bookFormValidator->inputNotBlank($bookAuthors);
            $violations['publishedDate'] = $bookFormValidator->checkYear($bookPublishedDate);
            $violations['bookDescription'] = $bookFormValidator->inputNotBlank($bookDescription);
            $violations['bookISBN'] = $bookFormValidator->checkISBN($bookISBN);
            $violations['nbPages'] = $bookFormValidator->inputNotBlank($bookNbPages);

            // Verifies if there is an error
            if (empty($violations['bookTitle']) && empty($violations['bookAuthors']) && empty($violations['publishedDate']) && empty($violations['bookDescription']) && empty($violations['bookISBN']) && empty($violations['nbPages'])){
                // If there is not, the code continues to execute
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

                header('Location: ../public/index.php?action=getMemberBookList&login='. $_SESSION['login'] .'');
            } else {
                // Otherwise the error(s) is/are displayed
                foreach ($violations as $violation){
                    if($violation !== null){
                        echo $violation . '<br />';
                    }
                }
            }

        }

        public function deleteBook($id){
            $sql = 'DELETE FROM bookslist WHERE id = :id ';
            $this->sql($sql, [
                'id' => $id
            ]);

            header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].'');

        }

        public function editBookDatas($bookId){
            $sql = 'SELECT id, author, title, summary, publishingYear, ISBN, nbPages FROM bookslist WHERE id = :id';
            $result = $this->sql($sql, [
                'id' => $bookId
            ]);

            $bookDatas = [];
            foreach($result as $row){
                $bookId = $row['id'];
                $bookDatas[$bookId] = $this->commonFunctionalities->buildObject($row);
            }

            return $bookDatas;
        }

        public function displayBook($bookId){
            $sql = 'SELECT * FROM bookslist WHERE id = :id';
            $result = $this->sql($sql, [
                'id' => $bookId
            ]);

            $bookDatas = [];
            foreach($result as $row){
                $bookId = $row['id'];
                $bookDatas[$bookId] = $this->commonFunctionalities->buildObject($row);
            }

            return $bookDatas;
        }

        public function registerEditBookDatas($bookId, $editTitle, $editAuthor, $editPublishingYear, $editSummary, $editISBN, $editNbPages){
            $sql = 'UPDATE bookslist SET author = :newAuthor, title = :newTitle, summary = :newSummary, publishingYear = :newPublishingYear, ISBN = :ISBN, nbPages = :newNbPages WHERE id = :id';
            $this->sql($sql, [
                'newAuthor' => $editAuthor,
                'newTitle' => $editTitle,
                'newSummary' => $editSummary,
                'newPublishingYear' => $editPublishingYear,
                'ISBN' => $editISBN,
                'newNbPages' => $editNbPages,
                'id' => $bookId
            ]);

            header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].'');
        }

        public function checkBookFriend($ISBN, $loginFriend){
            $sql = 'SELECT id FROM members WHERE login = :login';
            $result = $this->sql($sql, [
                'login' => $loginFriend
            ]);
            $row = $result->fetch();

            $id = $row['id'];


            if($row){
                $sql = 'SELECT id_member, ISBN FROM bookslist WHERE id_member = :id';
                $result = $this->sql($sql, [
                   'id' => $id
                ]);

                foreach($result as $row){

                    $bookISBN = $row['ISBN'];

                    if($bookISBN == $ISBN){
                        $message = $loginFriend . ' a déjà cet ouvrage.';
                        return $message;
                    } else {
                        $message = $loginFriend . ' n\'a pas enregistré cet ouvrage.';
                        return $message;
                    }

                }

            } else{
                $message = 'Il n\' a pas d\'ouvrages enregistrés pour l\'instant.';
                return $message;
            }
        }

    }