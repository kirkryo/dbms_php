<!DOCTYPE html>

<html>
<head>

</head>
<body>
<h1>View</h1>
<?php
if (!isset($_GET['edit_success'])) {
    echo "<br>";
} else if ($_GET['edit_success'] == 'TRUE') {
    echo "<p style='color:green;'>Your file was successfully edited.</p>";
} else if ($_GET['edit_success'] == 'FALSE') {
    echo "<p style='color:red;'>There was an error editing your file.</p>";
}

$conn = new mysqli('localhost', 'root', '', 'data_manager');
if (!$conn) {
    echo "Cannot connect to dataase";
    die;
}
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT description, path, date_created, date_updated FROM photos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($description, $path, $date_created, $date_updated);
$stmt->fetch();
?>
    <p>ID: <?php echo $id; ?></p>
    <p>Description: <?php echo $description; ?></p>
    <p>Date Created: <?php echo $date_created ?></p>
    <p>Date Updated: <?php echo $date_updated ?></p>
    <p><image src="<?php echo "$path" ?>" width="35%"</p>
    <p><a href="index.php">Back to list</a> <a href="edit.php?id=<?php echo $id ?>">Edit</a> <a href="delete.php?id=<?php echo $id ?>">Delete</a></p>
</body>
</html>
