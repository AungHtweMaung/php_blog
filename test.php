<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $fruits = ["apple", "orange", "mango"];
    $arr = [];
    foreach ($fruits as $value) {
        $arr[] = $value;
    }
    print_r($arr);

    ?>
</body>

</html>