<!DOCTYPE html>
<?php
	$conn = new mysqli('localhost', 'root','','data_manager');
	if (!$conn) {
	    echo "Cannot connect to database";
	    die;
	}

	$stmt = $conn->prepare("SELECT * FROM photos ORDER BY id DESC");
	$stmt->execute();
	$stmt->bind_result($id, $description, $path, $date_created, $date_updated);

	
?>
<html>
<head>
	<title>List View</title>
</head>
	<body>
    <a href='create.php'>Create</a>
    <?php
        if (isset($_GET['create_success']) && $_GET['create_success'] == 'TRUE') {
            echo "<p style='color:green;'>Your record was successfully created.</p>";
        } else if (isset($_GET['create_success']) && $_GET['create_success'] == 'FALSE') {
            echo "<p style='color:red;'>There was an error creating your record.</p>";
        } else if (isset($_GET['delete_success']) && $_GET['delete_success'] == 'TRUE') {
            echo "<p style='color:green;'>Record successfully deleted.</p>";
        } else if (isset($_GET['delete_success']) && $_GET['delete_success'] == 'FALSE') {
            echo "<p style='color:red;'>There was an error deleting your record.</p>";
        }
    ?>
    <br>
    <table>
        <tr>
            <th>ID</th>
            <th>Desc</th>
            <th>Pic</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Actions</th>
        </tr>
		<?php
			while ($stmt->fetch()) {
			    echo "<tr>";
                echo "<td>$id</td>";
                echo "<td>$description</td>";
                echo "<td><img src='$path' width='200'></td>";
                echo "<td>$date_created</td>";
                echo "<td>$date_updated</td>";
                echo "
                <td>
                        <a href='view.php?id=$id'>View</a>
                        <a href='edit.php?id=$id'>Edit</a>
                        <a href='delete.php?id=$id'>Delete</a>
                </td>";
                echo "</tr>";
			}
			$stmt->close();
			$conn->close();
		?>
    </table>
	</body>
</html>