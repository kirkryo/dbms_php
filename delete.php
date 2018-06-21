<!DOCTYPE html>

<html>
<head>

</head>
<body>
<h1>Delete</h1>
<?php
$conn = new mysqli('localhost', 'root', '', 'data_manager');
if (!$conn) {
    echo "Cannot connect to database";
    die;
}
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT description, path, date_created, date_updated FROM photos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($description, $path, $date_created, $date_updated);
$stmt->fetch();

?>

<h3>Are you sure you want to delete this record?</h3>
<p>ID: <?php echo $id; ?></p>
<p>Description: <?php echo $description; ?></p>
<p><image src="<?php echo "$path" ?>" width="35%"</p>
<form action="upload.php?action=delete&id=<?php echo $id ?>" method="post" enctype="multipart/form-data">
    <p><input type="submit" name="submit" value="Delete"</p>
</form>
<p><a href="index.php">Back to list</a> <a href="edit.php?id=<?php echo $id ?>">Edit</a></p>
</body>
</html>