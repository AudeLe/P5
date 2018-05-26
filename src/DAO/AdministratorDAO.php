<?php

    namespace DAO;

    class AdministratorDAO extends DAO{

        public function countMembers(){
            $sql = 'SELECT COUNT(*) FROM members WHERE status = :status';
            $result = $this->sql($sql, [
                'status' => 'member'
            ]);
            $row = $result->fetch();

            $nbMembers = $row['COUNT(*)'];

            return $nbMembers;
        }

        public function countBooks(){
            $sql = 'SELECT COUNT(*) FROM bookslist';
            $result = $this->sql($sql);
            $row = $result->fetch();

            $nbBooks = $row['COUNT(*)'];

            return $nbBooks;
        }
    }