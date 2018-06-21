<!DOCTYPE html>
<html>
<head>
    <script>
        var loadFile = function(event) {
            var output = document.getElementById('image');
            output.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
</head>
<body>
<h1>Edit</h1>
<?php
$conn = new mysqli('localhost', 'root', '', 'data_manager');
if (!$conn) {
    echo "Cannot connect to dataase";
    die;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT description, path FROM photos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($description, $path);
$stmt->fetch();
?>
<form action="upload.php?action=edit&id=<?php echo $id ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <input type="hidden" name="path" value="<?php echo $path ?>">
    <p>Image: <br><img id="image" src="<?php echo $path ?>" height="200"></p>
    <p><input type="file" name="fileToUpload" id="fileToUpload" onchange="loadFile(event)"></p>
    <p>Description: <br><textarea width="100" height="20" name="description"><?php echo $description ?></textarea></p>
    <p><input type="submit" name="submit" value="Submit"</p>
    <p><a href="index.php">Back to list</a> <a href="view.php?id=<?php echo $id ?>">View</a></p>
</form>
</body>
</html>