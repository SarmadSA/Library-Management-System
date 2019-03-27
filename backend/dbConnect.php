<?php
    $myPDO = new PDO('sqlite:../database/LibraryDatabase.db');
    $result = "SELECT COUNT(*) FROM book_copies";
    $res1 = $myPDO->query($result);
    while ($row = $res1->fetch()) {
        $Count_Books = $row[0];
    }
    $result = "SELECT COUNT(distinct b.ISBN) "
        . "FROM Book b, Book_copies bc, Loans l, Returns r "
        . "WHERE b.ISBN = bc.ISBN AND (bc.Copy_ID NOT IN (SELECT Book_ID FROM Loans) "
        . "OR (bc.Copy_ID IN(SELECT Book_ID FROM Loans) "
        . "AND l.ID IN (SELECT Loans_ID from Returns))) "
        . "AND (SELECT count(Copy_ID) FROM book_copies) > 0";
    $res1 = $myPDO->query($result);
    while ($row = $res1->fetch()) {
        $Available_Books = $row[0];
    }
    $result = "SELECT (SELECT COUNT(ID) FROM loans) - (SELECT COUNT(l.ID) FROM loans l, returns r WHERE l.ID = r.loans_ID)";
    $res1 = $myPDO->query($result);
    while ($row = $res1->fetch()) {
        $Borrowed_Books = $row[0];
    }
    $result = "SELECT COUNT(*) FROM loans l, returns r WHERE l.Due_Date < date('now') AND  l.ID != r.loans_ID";
    $res1 = $myPDO->query($result);
    while ($row = $res1->fetch()) {
        $Late_Books = $row[0];
    }
    ?>