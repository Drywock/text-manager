<html>
<head>
  <meta charset="UTF-8">
  <title>Text manager</title>
  <link rel="stylesheet" href="https://unpkg.com/mustard-ui@latest/dist/css/mustard-ui.min.css">
  <style>
  .highlighted {
    background: yellow;
  }
  </style>
</head>
<body>

  <header style="height: 200px;">
    <h1>Text manager</h1>
  </header>

  <?php
    $text = "";
    $filePath = "";
    if(isset($_POST["filePath"])) {
      $filePath = $_POST["filePath"];
      if($filePath !== "") {
        $text = file_get_contents($filePath);
      }
    }

    $searchQuery="";
    if(isset($_POST["searchQuery"])) {
      $searchQuery = $_POST["searchQuery"];
    }
  ?>

  <br>
  <div class="row">
    <div class="col col-sm-5">
      <div class="panel">
        <div class="panel-body">
          <h1>1. Get text</h1>
          <form action="index.php" method="post">
            <input type="text" name="filePath" value="<?=$filePath?>" />
            <br>
            <button class="button-success" type="submit">Fetch text</button>
          </form>

          <h1>2. Find keywords</h1>
          <form action="index.php" method="post">
            <input type="hidden" name="filePath" value="<?=$filePath?>" />
            <input type="text" name="searchQuery" value="<?=$searchQuery?>"/>
            <br>
            <button class="button-success" type="submit">Seach text</button>
          </form>

          <?php
            if($searchQuery !== ""){
              echo "<h1>3. Check results</h1>";
              $keyWords = preg_split('/\s+/', $searchQuery);
              
              echo '<div class="stepper">';
              foreach($keyWords as $keyWord) {
                $offset = 0;
                $id = 0;
                while( $offset < strlen($text) && stripos($text, $keyWord, $offset) !== false) {
                  $pos = stripos($text, $keyWord, $offset);
                  $id++;
                  $word = substr($text, $pos, strlen($keyWord));
                  $replacement = "<span class=\"highlighted\" id=\"$keyWord-$id\">$word</span>";
                  $text = substr_replace($text, $replacement, $pos, strlen($keyWord));
                  $offset = $pos + strlen($replacement);
                }
                echo "<div class=\"step\">
                        <p class=\"step-number\">$id</p>
                        <p class=\"step-title\">Keyword: $keyWord</p>";
                for($cpt = 1; $cpt <= $id; $cpt++) {
                  echo "<a href=\"#$keyWord-$cpt\">$cpt</a> ";
                }
                echo "</div>";
              }
              echo "<div class=\"step\"></div>";
              echo "</div>";
            }
          ?>
        </div>
      </div>
    </div>

    <div class="col col-sm-7" style="padding-left: 25px;">
      <pre><code><?=$text?></code></pre>
    </div>
  </div>

</body>
</html>