
<script>
	var url_str = window.location.href;
	//On successful authentication, AWS Cognito will redirect to Call-back URL and pass the access_token as a request parameter. 
	//If you notice the URL, a “#” symbol is used to separate the query parameters instead of the “?” symbol. 
	//So we need to replace the “#” with “?” in the URL and call the page again.
	
	if(url_str.includes("#")){
		var url_str_hash_replaced = url_str.replace("#", "?");
		window.location.href = url_str_hash_replaced;
	}
	
</script>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="#">FestivalCloud</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <?php
        require_once 'functions.php';
        require_once __DIR__ . '/../classes/Auth.php'; 
        $auth = new Auth();
        $query = "";
        
        if($auth->loggedIn()) {
          $query = 'access_token='.$auth->getAccessToken();
        } else if(strpos($_SERVER['REQUEST_URI'], 'views/')) {
          //$auth->redirect("You have to be logged in to view this page");        
        }

        if ($auth->loggedIn()){
          echo '<a class="nav-link" href="'.BASE_URL.'/views/festivals/index.php?' .$query .'">Festivals</a>';
          echo '<a class="nav-link" href="'.BASE_URL.'/views/stages/index.php?'. $query .'">Stages</a>';
          echo '<a class="nav-link" href="'.BASE_URL.'/views/shows/index.php?'. $query .'">Shows</a>';
          echo '<a class="nav-link" href="'.BASE_URL.'/views/performers/index.php?'. $query .'">Performers</a>';
          echo '<a class="nav-link" href="'.BASE_URL.'?' . $query . '&logout=true">Sign Out</a>';
        } else echo '<a class="nav-link" href="'. $auth->getSignInURL() . '">Sign In</a>';
          
        
        ?>
      </div>
    </div>
  </div>
</nav>
<br>
<br>



