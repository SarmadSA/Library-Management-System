<?php

//Select number of unique books
$numberOfBook = "SELECT COUNT(*) FROM Book";

//Select number of available books
$result = "SELECT COUNT(distinct b.ISBN)
        . FROM Book b, Book_copies bc, Loans l, Returns r
        . WHERE b.ISBN = bc.ISBN AND (bc.Copy_ID NOT IN (SELECT Book_ID FROM Loans)
        . OR (bc.Copy_ID IN(SELECT Book_ID FROM Loans) 
        . AND l.ID IN (SELECT Loans_ID from Returns))) 
        . AND (SELECT count(Copy_ID) FROM book_copies) > 0";

//Select number of borrowed book copies
$result = "SELECT (SELECT COUNT(ID) FROM loans) - (SELECT COUNT(l.ID) 
          FROM loans l, returns r 
          WHERE l.ID = r.loans_ID)";

//Select number of not returned books
$result = "SELECT COUNT(DISTINCT l.ID) 
           FROM loans l, returns r 
           WHERE l.Due_Date < date('now') 
           AND l.ID NOT IN (SELECT loans_ID FROM Returns)";
