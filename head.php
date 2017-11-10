<head>
    <meta charset="UTF-8">
    <?php
        include("config.php");
        $page = explode(".", $currentPage);
        $page = reset($page); //Take the first piece of the string (without .php)
        $page[0] = strtoupper($page[0]); //Make the first character uppercase

        for ($i = 1; $i < strlen($page); $i++) { //Loop through the string, skipping the first character since it should be uppercase and redundant in the loop.
            if ($page[$i] == strtoupper($page[$i])) { //Check if the character is uppercase
                $page = substr_replace($page, " ", $i, 0); //Replace the "0 characters" in position $i with a space, hence inserting
                $i++; //Adding manually so the thing above doesn't continuously make the string longer and loop forever.
            }
        }

        if ($page == "Index") {
            $page = "Home";
        }
    ?>
    <title>The Wall - <?php echo $page ?></title>
    <link rel="stylesheet" href="css/main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display|Roboto:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> In case we want to use jQuery-->
    <script type="text/javascript" src="js/js.js"></script>
</head>
