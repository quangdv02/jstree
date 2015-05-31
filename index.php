<?php
include('db.php');
$sql = "select * from categories order by pos asc";
$data = fetchAll($sql);
$list = create_list($data);

?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>jstree</title>
    <link rel="stylesheet" href="dist/themes/default/style.min.css"/>
    <script src="dist/jquery.min.js" type="text/javascript"></script>
    <script src="dist/jstree.min.js" type="text/javascript"></script>
    <script src="dist/custom.js" type="text/javascript"></script>
</head>
<body>
<div id="html1">
    <?php echo $list; ?>
</div>
<div id="event_result"></div>
</body>
</html>