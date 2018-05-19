<?php
    namespace models;

    class BookList{

        private $idMember;
        private $author;
        private $title;
        private $summary;
        private $publishingYear;
        private $ISBN;
        private $nbPages;
        
        public function getIdMember(){
            return $this->idMember;
        }
        
        public function setIdMember($idMember){
            $this->idMember = $idMember;
        }
        
        public function getAuthor(){
            return $this->author;
        }
        
        public function setAuthor($author){
            $this->author = $author;
        }
        
        public function getTitle(){
            return $this->title;
        }
        
        public function setTitle($title){
            $this->title = $title;
        }
        
        public function getSummary(){
            return $this->summary;
        }
        
        public function setSummary($summary){
            $this->summary = $summary;
        }
        
        public function getPublishingYear(){
            return $this->publishingYear;
        }
        
        public function setPublishingYear($publishingYear){
            $this->publishingYear = $publishingYear;
        }
        
        public function getISBN(){
            return $this->ISBN;
        }
        
        public function setISBN($ISBN){
            $this->ISBN = $ISBN;
        }
        
        public function getNbPages(){
            return $this->nbPages;
        }
        
        public function setNbPages($nbPages){
            $this->nbPages = $nbPages;
        }
    }