var bookData = {
    getBookData: function(){
        //bookData.init();

        var isbnSubmission = document.getElementById("ISBNSubmission");

        isbnSubmission.addEventListener("click", function(){
            var isbn = document.getElementById("ISBN").value;

            bookData.googleBookAjaxCall(isbn);

        });
    },

    googleBookAjaxCall: function(isbn){
        ajaxGet("https://www.googleapis.com/books/v1/volumes?q=isbn:"+isbn+"&key=AIzaSyA3aqWS8-tkLICC69ZV55LLixLiac9ULJM", function (reponse) {
            var bookDetails = JSON.parse(reponse);
            //console.log(isbn);
            console.log(bookDetails);
            console.log(bookDetails.totalItems);

            if(bookDetails.totalItems > 0){
                console.log("ok");

                var googleBook = bookDetails.items[0];

                var title = googleBook.volumeInfo.title;
                // Si plusieurs auteurs, les intégrer !!
                var author = googleBook.volumeInfo.authors[0];
                // Intégrer les ISBN (10 et 13)

            }

            var bookDetailsInsert = document.getElementById("bookDetailsInsert");
            bookDetailsInsert.textContent = "titre : "+ title;
            /* FONCTIONNE !
            bookDetailsInsert.textContent = "auteur : "+ author;
             */
        });


    }
}

bookData.getBookData();