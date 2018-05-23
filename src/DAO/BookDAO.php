<?php
    namespace DAO;

    use models\BookDatasForm;

    class BookDAO extends DAO {

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

                header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].'');
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
            $result = $this->sql($sql, [
                'id' => $id
            ]);

            header('Location: ../public/index.php?action=memberProfile&login='.$_SESSION['login'].'');

        }

    }