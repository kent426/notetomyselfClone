<html><head>
    <title>Note-to-myself : notes</title>
    <link rel="shortcut icon" href="pencil.ico" />
    <script type="text/javascript">
        function openInNew(textbox){
            window.open(textbox.value);
            this.blur();
        }

    </script>


    <link href="css/notes.css" rel="stylesheet" type="text/css" media="screen" />



</head>
<body>
<?php

require_once "commonHead.php";

session_start();

if($_SESSION['login'] != 1) {
    die("</head><body>Please try again to <a href=\"register2.php\">register</a> or <a href=\"index.php\">log in</a>.</body></html>");
}

$email = $_SESSION['username'];
$db=connectDB();
$retrieve = "SELECT * FROM notes WHERE email = '$email'";
$reRetrieve = mysqli_query($db,$retrieve) or die(mysqli_error($db));

if(mysqli_num_rows($reRetrieve)==0) {

    //only the first time will be created
    $createRow = "INSERT INTO notes (notes_id,
	email,
	websitesUrls,
	image1,
	image2,
	image3,
	image4,
	notes,
	tbd) VALUES(DEFAULT,'$email',NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
    mysqli_query($db,$createRow) or die(mysqli_error($db));
}
mysqli_close($db);
?>


<?php


if(isset($_POST['submitting'])){
    $db=connectDB();
    $email=$_SESSION['username'];
    $notes=$_POST['notes'];
    $tb=$_POST['tbd'];
    $qi = "UPDATE notes SET notes = '$notes',tbd = '$tb'  WHERE email='$email'";

    $userInsert = mysqli_query($db, $qi) or die(mysqli_error($db));
    mysqli_close($db);



}

?>

<?php
$db=connectDB();
$retrieve = "SELECT * FROM notes WHERE email = '$email'";
$reRetrieve = mysqli_query($db,$retrieve) or die(mysqli_error($db));

$reArr = mysqli_fetch_assoc($reRetrieve);

//$doc = new DOMDocument()



print_r($reArr);
?>

<div id="wrapper">
    <form action="notes.php" enctype="multipart/form-data" method="post">
        <?php echo '<h2 id="header">'.$_SESSION['username'].' - <span><a href="logout.php">Log out</a></span></h2>'?>


        <div id="section1">

            <div id="column1">
                <h2>notes</h2>
                <textarea cols="16" rows="40" id="notes" name="notes" /><?php echo $reArr['notes']; ?></textarea>
            </div>

            <div id="column2">
                <h2>websites</h2><h3>click to open</h3>



                <input type="text" name="websites[]" /><br >
                <input type="text" name="websites[]" /><br >
                <input type="text" name="websites[]" /><br >
                <input type="text" name="websites[]" /><br >

            </div>

        </div>

        <div id="section2">

            <div id="column3">
                <h2>images</h2><h3>click for full size</h3>
                <!-- <textarea cols="16" rows="40" id="image" name="image" /></textarea> -->

                <input type="file" name="i" /><br /><br />


                <div>


                </div>



            </div>

            <div id="column4">
                <h2>tbd</h2>
                <textarea cols="16" rows="40" id="tbd" name="tbd" /><?php echo $reArr['tbd']; ?></textarea>
            </div>

        </div>

        <div id="footer">
            <input type="submit" value="Save" style="width:200px;height:80px" name="submitting" />
        </div>

</div>

</body>
</html>