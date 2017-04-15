<?php
require_once "commonHead.php";

session_start();

if($_SESSION['login'] != 1) {
    die("</head><body>Please try again to <a href=\"register2.php\">register</a> or <a href=\"index.php\">log in</a>.</body></html>");
}

$email = $_SESSION['username'];
$db = connectDB();
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
    insertNotes();
    insertImage();
}

//function for inserting notes and TBD to DB
function insertNotes(){
    $db=connectDB();
    $email=$_SESSION['username'];
    $notes=$_POST['notes'];
    $tb=$_POST['tbd'];
    $qi = "UPDATE notes SET notes = '$notes',tbd = '$tb'  WHERE email='$email'";
    $notesInsert = mysqli_query($db, $qi) or die(mysqli_error($db));

    //insert websites
    $websites = $_POST['websites'];
    $urlsStr = "";
    foreach ($websites as $oneurl) {
        $urlsStr .= $oneurl."<#<#<#<>#>#>#>";
    }
    $urlsStr = mysqli_real_escape_string($db,$urlsStr);
    $queryInURLS = "UPDATE notes SET websitesUrls = '$urlsStr'  WHERE email='$email'";
    $urlsInsert = mysqli_query($db, $queryInURLS) or die(mysqli_error($db));
    //-------------------

    mysqli_close($db);
}
//INSERT IMAGE
function insertImage(){
    $db=connectDB();
    $email=$_SESSION['username'];

    $file=addslashes(file_get_contents($_FILES["i"]["tmp_name"]));
    $qi = "UPDATE notes SET image1 = '$file'  WHERE email='$email'";
    $notesInsert = mysqli_query($db, $qi) or die(mysqli_error($db));
    mysqli_close($db);
}

?>

<?php
//load and display notes and TBD
$db=connectDB();
$retrieve = "SELECT * FROM notes WHERE email = '$email'";
$reRetrieve = mysqli_query($db,$retrieve) or die(mysqli_error($db));

$reArr = mysqli_fetch_assoc($reRetrieve);
mysqli_close($db);

function printurls($reArr) {
    $urlsString = $reArr['websitesUrls'];
    if($urlsString !== NULL) {
        $urlsArray = explode("<#<#<#<>#>#>#>",$urlsString);
        //echo "GETcsv<br>";
        //print_r($urlsArray);
        foreach ($urlsArray as $oneurl) {
            if(!empty($oneurl)) {
                echo "<input type='text' name='websites[]' value='$oneurl' onclick='openInNew(this);' /><br >";
            }

        }
    }
}

?>


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

                <?php  printurls($reArr) ?>

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
                    <?php  $db=connectDB();
                    $query = "SELECT image1 FROM notes";
                    $result = mysqli_query($db, $query);
                    while($row = mysqli_fetch_array($result))
                    {
                        echo '  
                          <tr>  
                               <td>  
                                    <img src="data:image/jpeg;base64,'.base64_encode($row['image1'] ).'" height="200" width="200" class="img-thumnail" />  
                               </td>  
                          </tr>  
                     ';
                    }
                    ?>

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