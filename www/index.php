<?php
    $URL = "";
    $error = "";

    $con = new PDO("sqlite:../data/recipes.db");

    // Convert back to a usable URL and check for safety
    if(isset($_GET) && is_string($_GET["url"]) && strlen($_GET["url"]) > 0) {
        $URL = escapeshellcmd(urldecode($_GET["url"]));

        // Bad URL, try searching db instead
        if(!filter_var($URL, FILTER_VALIDATE_URL)) {
            $stmt = $con->prepare("SELECT json, url FROM recipes WHERE name LIKE ? ORDER BY name");
            $res = $stmt->execute(["%".$URL."%"]);

            // Fetch the recipes
            $search = $stmt->fetchAll();

        // Check if we already cached the results
        }else {
            $stmt = $con->prepare("SELECT json FROM recipes WHERE url=?");
            $res = $stmt->execute([$URL]);
            $rows = $stmt->fetchAll();

            // Delete the row
            if(isset($_GET["del"])) {
                
                if(count($rows) > 0) {
                    $stmt = $con->prepare("DELETE FROM recipes WHERE url=?");
                    $res = $stmt->execute([$URL]);
                }
                header("Location: ./");

            // Run parser script and retrieve the new HTML
            }elseif(count($rows) == 0) {
                $recipe = json_decode(shell_exec("python3 ../scripts/parser.py \"" . $URL . "\""), true);

                // Error reading from the site
                if(count($recipe) == 0) {
                    $error = "No recipe found!";

                // Successfull
                }else {
                    $stmt = $con->prepare("INSERT INTO recipes VALUES(?, ?, ?)");
                    $res = $stmt->execute([$URL, $recipe["name"], json_encode($recipe)]);
                }

            // Get the cached results
            }else {
                $recipe = json_decode($rows[0]["json"], true);
            }
        }
    }
?>

<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recipe Parser</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="p-5">

    <div class="container">

        <!-- Search Bar -->
        <form class="form-floating" method="get" action="./">
            <input name="url" class="form-control form-control-lg" type="text" placeholder="" value="<?php echo $_GET["url"]; ?>">
            <label for="url">Search for Recipe</label>
        </form>

        <!-- Error Bar -->
<?php 
    if (strlen($error) > 0) { 
?>
        <div class="alert alert-danger mt-5"> <?php echo $error; ?> </div>
<?php 
    } 
?>

        <!-- Search Results -->
<?php 
    if (count($search) > 0) { 
?>
        <div class="row mt-5 text-center justify-content-center">

<?php   
        foreach($search as $row) {
            $url = $row["url"];
            $json = json_decode($row["json"], true);
?>
            <div class="card m-2" style="width: 20rem">
                <img class="card-img-top" src="<?php echo $json["image_src"]; ?>">
                <div class="card-body">
                    <h5 class="card-title"> <?php echo $json["name"]; ?> </h5>
                    <a href="./?url=<?php echo $url; ?>"> Go to Recipe </a>
                </div>
            </div>
<?php   
        } 
?>
            </div>
        </div>
<?php 
    } 
?>

        <!-- Recipe Contents -->
<?php 
    if (count($recipe) > 0) { 
?>
        <div class="container mt-5">
            <h1> <?php echo $recipe["name"]; ?> </h1>
            <img class="img-thumbnail" style="max-width:20rem" src="<?php echo $recipe["image_src"]; ?>" alt="<?php echo $recipe["image_alt"]; ?>">
        
            <!-- Recipe Ingredients -->
<?php   
        if (count($recipe["ingredients"]) > 0) { 
?>
            <div class="mt-5">
                <h2 class="fw-bold"> Ingredients </h2>
                <ul>
<?php       
            foreach($recipe["ingredients"] as $item) {
                $str = htmlspecialchars(trim($item));

                if(strlen($str) > 0) { 
?>
                    <li class="mt-3"> <?php echo $str; ?> </li>
<?php           
                } 
            }
?>
                </ul>
            </div>
<?php   
        } 
?>

            <!-- Recipe Steps -->
<?php   
        if (count($recipe["steps"]) > 0) { 
?>
            <div class="mt-5">
                <h2 class="fw-bold"> Steps </h2>
                <ol>
<?php       
            foreach($recipe["steps"] as $item) {
                $str = htmlspecialchars(trim($item));

                if(strlen($str) > 0) { 
?>
                    <li class="mt-3"> <?php echo $str; ?> </li>
<?php           
                } 
            }
?>
                </ol>
            </div>
<?php   
        } 
?>

            <!-- Recipe Equipment -->
<?php   
        if (count($recipe["equipment"]) > 0) { 
?>
            <div class="mt-5">
                <h2 class="fw-bold"> Equipment </h2>
                <ul>
<?php       
            foreach($recipe["equipment"] as $item) {
                $str = htmlspecialchars(trim($item));

                if(strlen($str) > 0) { 
?>
                    <li class="mt-3"> <?php echo $str; ?> </li>
<?php           
                } 
            }
?>
                </ul>
            </div>
<?php   
        } 
?>

            <!-- Recipe Notes -->
<?php   
        if (count($recipe["notes"]) > 0) { 
?>
            <div class="mt-5">
                <h2 class="fw-bold"> Notes </h2>
                <ul>
<?php       
            foreach($recipe["notes"] as $item) {
                $str = htmlspecialchars(trim($item));

                if(strlen($str) > 0) { 
?>
                    <li class="mt-3"> <?php echo $str; ?> </li>
<?php           
                } 
            }
?>
                </ul>
            </div>
<?php   
        } 
?>


            <!-- Recipe Nutrition -->
<?php   
        if (count($recipe["nutrition"]) > 0) { 
?>
            <div class="mt-5">
                <h2 class="fw-bold"> Nutrition </h2>
                <ul>
<?php       
            foreach($recipe["nutrition"] as $item) {
                $str = htmlspecialchars(trim($item));

                if(strlen($str) > 0) { 
?>
                    <li class="mt-3"> <?php echo $str; ?> </li>
<?php           
                } 
            }
?>
                </ul>
            </div>
<?php   
        } 
?>
            <!-- Trash -->
            <form method="get" action="./" id="delete">
                <input type="hidden" name="del" value="true">
                <input type="hidden" name="url" value="<?php echo $_GET["url"]; ?>">
            </form>
            <button type="submit" form="delete" class="btn btn-danger float-end">Delete🗑️</button>
        </div>
<?php
    } 
?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>