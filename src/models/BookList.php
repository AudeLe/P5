<?php
    namespace models;

    class BookList{

        private $id;
        private $id_member;
        private $author;
        private $title;
        private $summary;
        private $publishingYear;
        private $ISBN;
        private $nbPages;

        /**
         * @return mixed
         */
        public function getId(){
            return $this->id;
        }

        /**
         * @param $id
         */
        public function setId($id){
            $this->id = $id;
        }

        /**
         * @return mixed
         */
        public function getIdMember(){
            return $this->id_member;
        }

        /**
         * @param $id_member
         */
        public function setIdMember($id_member){
            $this->id_member = $id_member;
        }

        /**
         * @return mixed
         */
        public function getAuthor(){
            return $this->author;
        }

        /**
         * @param $author
         */
        public function setAuthor($author){
            $this->author = $author;
        }

        /**
         * @return mixed
         */
        public function getTitle(){
            return $this->title;
        }

        /**
         * @param $title
         */
        public function setTitle($title){
            $this->title = $title;
        }

        /**
         * @return mixed
         */
        public function getSummary(){
            return $this->summary;
        }

        /**
         * @param $summary
         */
        public function setSummary($summary){
            $this->summary = $summary;
        }

        /**
         * @return mixed
         */
        public function getPublishingYear(){
            return $this->publishingYear;
        }

        /**
         * @param $publishingYear
         */
        public function setPublishingYear($publishingYear){
            $this->publishingYear = $publishingYear;
        }

        /**
         * @return mixed
         */
        public function getISBN(){
            return $this->ISBN;
        }

        /**
         * @param $ISBN
         */
        public function setISBN($ISBN){
            $this->ISBN = $ISBN;
        }

        /**
         * @return mixed
         */
        public function getNbPages(){
            return $this->nbPages;
        }

        /**
         * @param $nbPages
         */
        public function setNbPages($nbPages){
            $this->nbPages = $nbPages;
        }
    }