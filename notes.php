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

$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or
die(mysqli_connect_error());

mysqli_select_db($db, DB_DATABASE);

$retrieve = "SELECT * FROM notes WHERE email = $email";
$reRetrieve = mysqli_query($db,$retrieve);

?>


<?php


if(isset($_POST['submitting'])){
    $email=$_SESSION['username'];
    $notes=$_POST['notes'];
    $tb=$_POST['tbd'];
    $qi = "INSERT INTO notes (email,notes,tbd) VALUES('$email', '$notes', '$tb')";

    $userInsert = mysqli_query($db, $qi) or die(mysqli_error($db));

}

?>

<div id="wrapper">
    <form action="notes.php" enctype="multipart/form-data" method="post">
        <?php echo '<h2 id="header">'.$_SESSION['username'].' - <span><a href="logout.php">Log out</a></span></h2>'?>


        <div id="section1">

            <div id="column1">
                <h2>notes</h2>
                <textarea cols="16" rows="40" id="notes" name="notes" /></textarea>
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
                <textarea cols="16" rows="40" id="tbd" name="tbd" /></textarea>
            </div>

        </div>

        <div id="footer">
            <input type="submit" value="Save" style="width:200px;height:80px" name="submitting" />
        </div>

</div>

</body>
</html>