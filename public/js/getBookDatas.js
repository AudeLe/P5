var bookData = {
    getBookData: function(){
        //bookData.init();

        var isbnSubmission = document.getElementById("ISBNSubmission");

        isbnSubmission.addEventListener("click", function(){
            var isbn = document.getElementById("ISBN").value;
            console.log(isbn);
            bookData.googleBookAjaxCall(isbn);

        });
    },

    googleBookAjaxCall: function(isbn){
        ajaxGet("https://www.googleapis.com/books/v1/volumes?q=isbn:"+isbn+"&key=AIzaSyA3aqWS8-tkLICC69ZV55LLixLiac9ULJM", function (reponse) {
            var bookDetails = JSON.parse(reponse);
            //console.log(isbn);
            console.log(bookDetails);
            console.log(bookDetails.totalItems);

            /*
            First draft of the code.
            Have to manage the case of empty datas !
            If there are several datas (such as for the authors) have to display most of them (with a limit of 3,5?)
             */
            var book = [];
            if (bookDetails.totalItems > 0) {
                var googleBook = bookDetails.items[0];

                book['title'] = googleBook.volumeInfo.title;
                book['author'] = googleBook.volumeInfo.authors[0];
                book['publishedDate'] = googleBook.volumeInfo.publishedDate;
                book['description'] = googleBook.volumeInfo.description;
                book['isbnIndustry'] = googleBook.volumeInfo.industryIdentifiers[1].identifier;
                book['nbPages'] = googleBook.volumeInfo.pageCount;
            }

            bookData.bookDetailsInsertion(book);
        });
    },

    bookDetailsInsertion: function(book){
            console.log(book);
            var bookInformations = document.getElementById("bookInformations");
            bookInformations.style.display = "block";

            var bookTitle = document.getElementById("bookTitle");
            bookTitle.textContent = "";
            bookTitle.textContent = book['title'];

            var bookAuthors = document.getElementById("bookAuthors");
            bookAuthors.textContent = "";
            bookAuthors.textContent = book.author;

            var bookPublishedDate = document.getElementById("bookPublishedDate");
            bookPublishedDate.textContent = "";
            bookPublishedDate.textContent = book.publishedDate;

            var bookDescription = document.getElementById("bookDescription");
            bookDescription.textContent = "";
            bookDescription.textContent = book.description;

            var bookISBN = document.getElementById("bookISBN");
            bookISBN.textContent = "";
            bookISBN.textContent = book.isbnIndustry;

            var bookNbPages = document.getElementById("bookNbPages");
            bookNbPages.textContent = "";
            bookNbPages.textContent = book.nbPages;
    }
}

bookData.getBookData();