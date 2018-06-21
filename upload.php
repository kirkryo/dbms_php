<?php
$conn = new mysqli('localhost', 'root','','data_manager');
if (!$conn) {
    echo "Cannot connect to database";
    die;
}

if (isset($_POST['description'])) {
    $description = $_POST['description'];
}
//if (isset($_POST['path'])) {
//    $path = $_POST['path'];
//}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$action = $_GET['action'];
if ($action == 'create') {
    //  upload image into directory
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
// Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
// Check file size
    if ($_FILES["fileToUpload"]["size"] > 100000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
// Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        header("Location: index.php?create_success=FALSE");
// if everything is ok, try to upload file and add the record to the database
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            echo $uploadOk;

            //  add record to database
            $conn = new mysqli('localhost', 'root','','data_manager');
            if (!$conn) {
                echo "Cannot connect to database";
                die;
            }
            $description = $_POST['description'];
            $stmt = $conn->prepare("INSERT INTO photos (description, path) VALUES (?, ?)");
            $stmt->bind_param("ss", $description, $target_file);
            $stmt->execute();
            //$last_id = $conn->insert_id;
            $stmt->close();
            $conn->close();
            header("Location: index.php?create_success=TRUE");
        } else {
            header("Location: index.php?create_success=FALSE");
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else if ($action == 'edit') {
    if ($_FILES['fileToUpload']['name'] != '') {
        $stmt = $conn->prepare("SELECT path FROM photos WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($path);
        $stmt->fetch();
        unlink($path);
        $stmt->close();

        //  upload image into directory
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
// Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
// Check file size
        if ($_FILES["fileToUpload"]["size"] > 100000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
// Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            header("Location: view.php?id=$id&edit_success=FALSE");
            echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file and add the record to the database
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                echo $uploadOk;

                //  add record to database
                $stmt = $conn->prepare("UPDATE photos SET description = ?, path = ? WHERE id = ?");
                $stmt->bind_param("ssi", $description, $target_file, $id);
                $stmt->execute();
                $stmt->close();
                $conn->close();
                header("Location: view.php?id=$id&edit_success=TRUE");
            } else {
                header("Location: view.php?id=$id&edit_success=FALSE");
            }
        }
    } else {
        $description = $_POST['description'];
        $stmt = $conn->prepare("UPDATE photos SET description = ? WHERE id = ?");
        if (! $stmt) {
            header("Location: view.php?id=$id&edit_success=FALSE");
        }
        $stmt->bind_param("si", $description, $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        echo "no pic updated<br>";
        print_r($_FILES);
        header("Location: view.php?id=$id&edit_success=TRUE");
    }
} else if ($action == 'delete') {
    //  delete the file from the /images directory
    $stmt = $conn->prepare("SELECT path FROM photos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($path);
    $stmt->fetch();
    unlink($path);
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM photos WHERE id=?");
    if (! $stmt) {
        header("Location: view.php?id=$id&edit_success=FALSE");
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: index.php?delete_success=TRUE");
}
?>