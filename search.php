<?php

include("config.php");
include("components/SiteResultsProvider.php");
include("components/ImageResultsProvider.php");

if(isset($_GET["term"])) $term = $_GET["term"];
else exit("You must type a search term...");

$type = isset($_GET["type"]) ? $_GET["type"] : "sites";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Search the web for sites and images.">
    <meta name="keywords" content="Dig-Out, Dig, Out, Search Engine, Search, Websites, Images">
    <meta name="author" content="Mahmudul Hasan Sreejon">

    <title>Dig-Out</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css" />

    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/style.css" />

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/icons/favicon.ico" />

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <div class="headerContent">
                <div class="logoContainer">
                    <a href="index.php">
                        <img src="assets/img/search-page-logo.png" title="Dig-Out">
                    </a>
                </div>

                <div class="searchContainer">
                    <form action="search.php" method="GET">
                        <div class="searchBarContainer">
                            <input type="hidden" name="type" value="<?php echo $type; ?>">
                            <input type="text" class="searchBox" name="term" value="<?php echo $term; ?>">
                            <button class="searchButton">
                                <img src="assets/img/icons/search-icon.png">
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="tabsContainer">
                <ul class="tabList">
                    <li class="<?php echo($type == 'sites' ? 'active' : ''); ?>">
                        <a href='<?php echo("search.php?term=$term&type=sites"); ?>'>Sites</a>
                    </li>

                    <li class="<?php echo($type == 'images' ? 'active' : ''); ?>">
                        <a href='<?php echo("search.php?term=$term&type=images"); ?>'>Images</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mainResultsSection">
            <?php

            if($type == "sites") {
                $resultsProvider = new SiteResultsProvider($conn);
                $pageSize = 20;
            }
            else {
                $resultsProvider = new ImageResultsProvider($conn);
                $pageSize = 30;
            }

            $numResults = $resultsProvider->getNumResults($term);

            echo "<p class='resultsCount'>$numResults results found</p>";
            echo $resultsProvider->getResultsHtml($page, $pageSize, $term);

            ?>
        </div>

        <div class="paginationContainer">
            <div class="pageButtons">
                <div class="pageNumberContainer">
                    <img src="assets/img/pagination/pageStart.png">
                </div>
                <?php

                $pagesToShow = 10;
                $numPages = ceil($numResults / $pageSize);
                $pagesLeft = min($pagesToShow, $numPages);
                $currentPage = $page - floor($pagesToShow / 2);

                if($currentPage < 1) $currentPage = 1;
                if(($currentPage + $pagesLeft) > ($numPages + 1)) $currentPage = ($numPages + 1) - $pagesLeft;

                while($pagesLeft != 0 && $currentPage <= $numPages) {
                    if($currentPage == $page) {
                        echo "<div class='pageNumberContainer'>
                            <img src='assets/img/pagination/pageSelected.png'>
                            <span class='pageNumber'>$currentPage</span>
                        </div>";
                    }
                    else {
                        echo "<div class='pageNumberContainer'>
                            <a href='search.php?term=$term&type=$type&page=$currentPage'>
                                <img src='assets/img/pagination/page.png'>
                                <span class='pageNumber'>$currentPage</span>
                            </a>
                        </div>";
                    }

                    $currentPage++;
                    $pagesLeft--;
                }

                ?>
                <div class="pageNumberContainer">
                    <img src="assets/img/pagination/pageEnd.png">
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js"></script>

    <script type="text/javascript" src="assets/js/script.js"></script>
</body>
</html>
