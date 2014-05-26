<?php

    define('JC_ROOT', getcwd() . "/");
    
    include "./inc_header.php";
    
    require "functions.php";
?>

    <!--Skills container-->
    <div class="container skills">
      <h2>My Skills</h2>
      <div class="row">
        <div class="span3">
          <div class="ai">
            <h3>Linux</h3>
          </div>
        </div>
        <div class="span5">
          <h3>Linux (Ubuntu, Fedora, Mint and more...) <span>75%</span></h3>
          <div class="expand-bg"> <span class="expand ai2"> &nbsp; </span> </div>
        </div>
      </div>
      <div class="row">
        <div class="span3">
          <div class="html">
            <h3>HTML5</h3>
          </div>
        </div>
        <div class="span5">
          <h3>HTML5<span>85%</span></h3>
          <div class="expand-bg"> <span class="expand html2"> &nbsp; </span> </div>
        </div>
      </div>
      <div class="row">
        <div class="span3">
          <div class="css">
            <h3>CSS3</h3>
          </div>
        </div>
        <div class="span5">
          <h3>CSS3 <span>75%</span></h3>
          <div class="expand-bg"> <span class="expand css2"> &nbsp; </span> </div>
        </div>
      </div>
      <div class="row">
        <div class="span3">
          <div class="mysql">
            <h3>Servers</h3>
          </div>
        </div>
        <div class="span5">
          <h3>Apache/MySQL <span>65%</span></h3>
          <div class="expand-bg"> <span class="expand mysql5"> &nbsp; </span> </div>
        </div>
      </div>
      <div class="row">
        <div class="span3">
          <div class="php">
            <h3>Php5</h3>
          </div>
        </div>
        <div class="span5">
          <h3>Drupal/Php5 <span>75%</span></h3>
          <div class="expand-bg"> <span class="expand php5"> &nbsp; </span> </div>
        </div>
      </div>
      <div class="row">
        <div class="span3">
          <div class="ps">
            <h3>GIMP</h3>
          </div>
        </div>
        <div class="span5">
          <h3>GIMP (OpenSource Photoshop Equivalent) <span>85%</span></h3>
          <div class="expand-bg"> <span class="expand ps2"> &nbsp; </span> </div>
        </div>
      </div>
    </div>
    <!--END: Skills container-->

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2 class="frontPage">Skills</h2>
          <p>A short list of the most pertinent skills you would most likely be interested in and my related proficiency.</p><br><br><br>
          <p><a class="btn btn-primary" href="./includes/skills.php" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2 class="frontPage">Work</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2 class="frontPage">R&eacute;sum&eacute;</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>
        </div>
      </div>
      <hr>
    
<?php

    include "./inc_footer.php";
