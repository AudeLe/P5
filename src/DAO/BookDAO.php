<?php
    namespace DAO;

    use models\BookDatasForm;


    class BookDAO extends DAO {

        protected $commonFunctionalities;

        /**
         * BookDAO constructor.
         */
        public function __construct(){
            $this->commonFunctionalities = new CommonFunctionalitiesDAO();
        }

        /**
         * @param $id
         * @param $bookTitle
         * @param $bookAuthors
         * @param $bookPublishedDate
         * @param $bookDescription
         * @param $bookISBN
         * @param $bookNbPages
         */
        // Verifies the validity of the inputs and register them if they are okay
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
                $sql = 'SELECT ISBN, id_member FROM bookslist WHERE ISBN = :ISBN';
                $result = $this->sql($sql, [
                    'ISBN' => $bookISBN
                ]);

                $totalId = [];
                // Storage of the ids linked to the ids linked to the ISBN
                foreach($result as $row){
                    $id_member = $row['id_member'];
                    array_push($totalId, $id_member);
                }

                // Checking if the person already registered the book
                if (in_array($id, $totalId)){
                    $errorMessage = 'Vous avez déjà enregistré ce livre.';
                    header('Location: ../public/index.php?action=error&errorMessage=' . $errorMessage . '');

                } else {
                    $sql = 'INSERT INTO bookslist(id_member, title, author, summary, publishingYear, ISBN, nbPages) VALUES(:idMember, :title, :author, :summary, :publishingYear, :ISBN, :nbPages)';
                    $this->sql($sql, [
                        'idMember' => $id,
                        'title' => $bookTitle,
                        'author' => $bookAuthors,
                        'summary' => $bookDescription,
                        'publishingYear' => $bookPublishedDate,
                        'ISBN' => $bookISBN,
                        'nbPages' => $bookNbPages
                    ]);
                    header('Location: ../public/index.php?action=getMemberBookList&login='. $_SESSION['login'] .'');
                }


            } else {
                // Otherwise the error(s) is/are displayed
                foreach ($violations as $violation){
                    if($violation !== null){
                        echo $violation . '<br />';
                    }
                }
            }

        }

        /**
         * @param $id
         */
        // Delete a book from the member booklist
        public function deleteBook($id){
            $sql = 'DELETE FROM bookslist WHERE id = :id ';
            $this->sql($sql, [
                'id' => $id
            ]);

            header('Location: ../public/index.php?action=getMemberBookList&login='.$_SESSION['login'].'');
        }

        /**
         * @param $bookId
         * @return array
         */
        // Recover the datas to the book the member wants to edit
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

        /**
         * @param $bookId
         * @return array
         */
        // Display the informations of the selected book
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

        /**
         * @param $bookId
         * @param $editTitle
         * @param $editAuthor
         * @param $editPublishingYear
         * @param $editSummary
         * @param $editISBN
         * @param $editNbPages
         */
        // Verify and register the edited book datas if they are okay
        public function registerEditBookDatas($bookId, $editTitle, $editAuthor, $editPublishingYear, $editSummary, $editISBN, $editNbPages){

            $violations = [];

            $bookFormValidator = new BookDatasForm();

            $violations['bookTitle'] = $bookFormValidator->inputNotBlank($editTitle);
            $violations['bookAuthors'] = $bookFormValidator->inputNotBlank($editAuthor);
            $violations['publishedDate'] = $bookFormValidator->checkYear($editPublishingYear);
            $violations['bookDescription'] = $bookFormValidator->inputNotBlank($editSummary);
            $violations['bookISBN'] = $bookFormValidator->checkISBN($editISBN);
            $violations['nbPages'] = $bookFormValidator->inputNotBlank($editNbPages);

            // Verifies if there is an error
            if (empty($violations['bookTitle']) && empty($violations['bookAuthors']) && empty($violations['publishedDate']) && empty($violations['bookDescription']) && empty($violations['bookISBN']) && empty($violations['nbPages'])) {

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

                header('Location: ../public/index.php?action=getMemberBookList&login='.$_SESSION['login'].'');

            } else{
                // Otherwise the error(s) is/are displayed
                foreach ($violations as $violation){
                    if($violation !== null){
                        echo $violation . '<br />';
                    }
                }
            }

        }

        /**
         * @param $ISBN
         * @param $loginFriend
         * @return string
         */
        // Verifies if a friend already has a book or not
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