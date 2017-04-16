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
    $createRow = "INSERT INTO notes  VALUES(DEFAULT,'$email',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL)";
    mysqli_query($db,$createRow) or die(mysqli_error($db));
}
mysqli_close($db);
?>


<?php
if(isset($_POST['submitting'])){
    insertNotes();
    if(file_exists($_FILES['i']['tmp_name']) && is_uploaded_file($_FILES['i']['tmp_name'])) {
        insertImage();
    }

    //check if the delete set
    if(isset($_POST['delete'])){
        deleteImage($_POST['delete']);
        //print_r($_POST['delete']);
    }

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

//if return 5, no space
function checkImageNum() {
    $db=connectDB();
    $email=$_SESSION['username'];
    $retrieve = "SELECT * FROM notes WHERE email = '$email'";
    $reRetrieve = mysqli_query($db,$retrieve) or die(mysqli_error($db));
    $reArr = mysqli_fetch_assoc($reRetrieve);
    //the current available index for storing image
    global $indexImag;
    for($indexImag = 1;$indexImag <= 4;$indexImag++) {
        $name = "image" . $indexImag;
        if($reArr["$name"]=== NULL) {
            break;
        }
    }
    mysqli_close($db);
    return $indexImag;

}
//INSERT IMAGE
function insertImage(){

    $email=$_SESSION['username'];

    $allowed =  array('gif' ,'jpg');
    $filename = $_FILES['i']['name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $str = strtolower($ext);
    if(!in_array($str,$allowed) ) {
        die("only can upload gif and jpg.<a href=\"notes.php\">Try again</a>");
    }
    //has 1 to 4 to upload
    $indexImag = checkImageNum();
    if($indexImag <5) {
        $db=connectDB();
        $name = "image" . $indexImag;
        $nameInd = "imageindex". $indexImag;
        $file=addslashes(file_get_contents($_FILES["i"]["tmp_name"]));
        $qi = "UPDATE notes SET $name = '$file'  WHERE email='$email'";
        //get current counter
        $currindex = "SELECT imagecount FROM notes WHERE email='$email'";
        $currIndexRes = mysqli_query($db,$currindex);
        $curr = mysqli_fetch_row($currIndexRes)[0];

        $newCurr = $curr +1 ;

        //update counter, increment 1
        $currindex = "UPDATE notes SET imagecount = $newCurr  WHERE email='$email'";
        mysqli_query($db, $currindex) or die(mysqli_error($db));



        //give new image a new index
        $indeincre = "UPDATE notes SET $nameInd = $curr  WHERE email='$email'";
        mysqli_query($db, $indeincre) or die(mysqli_error($db));


        $imageInsert = mysqli_query($db, $qi) or die(mysqli_error($db));
        mysqli_close($db);
    }
}

//delete IMAGE
function deleteImage($deleteArr){
    $db=connectDB();
    $email=$_SESSION['username'];
    $name = "image";
    foreach ($deleteArr as $deleteIndex) {
        $deleteName = $name . $deleteIndex;
        //echo $deleteName;
        $qi = "UPDATE notes SET $deleteName = NULL WHERE email='$email'";
        $deleteIma = mysqli_query($db, $qi) or die(mysqli_error($db));
    }
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

                <?php
                if(checkImageNum()==5) {
                    echo "<p>maximum 4 images. delete some to upload new images.</p> ";
                } else {
                    echo "<input type=\"file\" name=\"i\" /><br /><br />";
                }
                ?>


                <div>
                    <?php  $db=connectDB();
                    $email = $_SESSION['username'];

                    $queryImageIndex = "SELECT imageindex1,imageindex2,imageindex3,imageindex4 FROM notes WHERE email = '$email'";
                    $indexImageresult = mysqli_query($db, $queryImageIndex);
                    $imageIndexarray = mysqli_fetch_assoc($indexImageresult);
                    asort($imageIndexarray);
                    //print_r($imageIndexarray);
                    $sortedSelectOrder = "";
                    foreach($imageIndexarray as $key => $value) {
                        $keyname = str_replace("imageindex","image",$key).",";
                        $sortedSelectOrder.=$keyname;
                    }
                    $sortedSelectOrder = substr($sortedSelectOrder,0,strlen($sortedSelectOrder)-1);


                    $query = "SELECT $sortedSelectOrder FROM notes WHERE email = '$email'";
                    $result = mysqli_query($db, $query);

                    while($row = mysqli_fetch_assoc($result))
                    {
                        foreach ($row as $key => $oneIm) {

                            if($oneIm===NULL) {
                                continue;
                            }
                            $indexForima = (int)(str_replace("image","",$key));
                            echo '  
                          <tr>  
                               <td>  
                                    <a href="data:image/jpeg;base64,'.base64_encode($oneIm).'" target="_blank"><img src="data:image/jpeg;base64,'.base64_encode($oneIm).'" height="80" width="125" class="img-thumnail" /></a>  
                               		
                               		<input name="delete[]" type="checkbox" value="'.$indexForima.'">
									
									<label for="delete[]" >delete</label>
                               </td>  
                          </tr>  
                          <br>
                     ';
                            mysqli_close($db);
                        }


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