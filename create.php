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
<h1>Create</h1>
<form action="upload.php?action=create" method="post" enctype="multipart/form-data">
    <p>Image: <br><img id="image" src="icons/pic_prev.jpg" height="200"></p>
    <p><input type="file" name="fileToUpload" id="fileToUpload" onchange="loadFile(event)"></p>
    <p>Description: <br><textarea width="100" height="20" name="description"></textarea></p>
    <p><input type="submit" name="submit" value="Submit"</p>
</form>
</body>
</html>
