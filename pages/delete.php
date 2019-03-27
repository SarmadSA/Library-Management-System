<?php
$select = "DELETE from data where empid='".$_GET['del_id']."'";
$query = mysqli_query($conn, $select) or die($select);
header ("Location: book.php");
