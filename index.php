<html>
<head>
    <title>Text manager</title>
    <link rel="stylesheet" href="https://unpkg.com/mustard-ui@latest/dist/css/mustard-ui.min.css">
</head> 
<body>
    <header style="height: 200px;">
        <h1>Text manager</h1>
    </header>
    <?php              
        $file ="";
        if(isset($_POST['poem_url']) && $_POST['poem_url'] !==""){ 
            $path = $_POST['poem_url']; 
            $words = array();
            $file = file_get_contents($path);                   
        }
        $searchQuery = "";
        if(isset($_POST['text_highlighted'])) {
            $searchQuery = $_POST['text_highlighted'];
        }    
    ?>
    <br>
    <div class="row">
        <div class="col col-sm-5">
            <div class="panel">
                <div class="panel-body">
                    <ol>
                        <div>
                            <h2>1. Get text</h2>                         
                            <?php
                                $path = "";
                                if(isset($_POST["poem_url"])) {
                                    $path = $_POST["poem_url"];
                                }
                            ?>
                            <form action="index.php" method="post">
                                <input type="text" name="poem_url" placeholder="Enter the poem url" value="<?=$path?>">
                                <br>
                                <button type="submit" class="button-success">FETCH TEXT</button>
                            </form>
                        </div>
                        <div>
                            <h2>2. Find keywords</h2>
                            <form action="index.php" method="post">
                                <input type="hidden" name="poem_url" value="<?=$path?>">
                                <input type="text" name="text_highlighted" placeholder="Enter text to be highlighted" value="<?=$searchQuery?>">
                                <br>
                                <button type="submit" class="button-success">SEARCH TEXT</button>
                            </form>
                        </div>                
                    </ol>
                    <?php
                        if($searchQuery !=="") {
                            $searchedKeyword = preg_split('/\s+/', $searchQuery);
                            $numberOfTimeForEachKeyword = array(array());
                            echo "<h2>3. Check results</h2>
                                <br>
                                <div class='stepper'>";

                            for($i =0 ; $i < count($searchedKeyword); $i++) {
                                $positionEvolving = 0;
                                $positionsForEachWord = array();
                                $position = stripos($file, $searchedKeyword[$i], $positionEvolving);
                                $number = 0;
                                while($position !== FALSE) {
                                    $number++;
                                    $replacementStr = "<mark id=\"$searchedKeyword[$i]-$number\">$searchedKeyword[$i]</mark>";
                                    $file = substr_replace($file, $replacementStr, $position, strlen($searchedKeyword[$i]));

                                    array_push($positionsForEachWord, $position);
                                    $positionEvolving = $position + strlen($replacementStr);       
                                    $position = stripos($file, $searchedKeyword[$i], $positionEvolving);
                                }
                                echo "<div class='step'>
                                <p class='step-number'> $number </p> 
                                <p class='step-title'> Keyword: $searchedKeyword[$i]</p>";
                                for($j = 1; $j <= count($positionsForEachWord); $j++){
                                    $y = $j-1;
                                    echo " ";
                                    echo "<a href= '#$searchedKeyword[$i]-$j'>$j</a>";    
                                    echo " ";
                                }
                                echo "</div>";
                                array_push($numberOfTimeForEachKeyword, $positionsForEachWord);
                            }
                            echo "</div>";
                        }
                    ?>
                </div>
            </div>        
        </div>
        <div class="col col-sm-7" style="padding-left: 25px;">
            <pre><code>
                <?php echo $file ?>
            </code></pre>
        </div>
    </div>
</body>
</html>