<?php
  // If PHP >= 5.4 we'll have gzdecode function, if PHP >= 4.0.1 we use gzuncompress
  if(!function_exists("gzdecode")) {
	  function gzdecode($data) {
	  	return gzuncompress($data);
	  }
  }

  // Get the JSON feed and gzunpack
  $file = gzdecode( file_get_contents("http://s.trustpilot.com/tpelements/917278/f.json.gz") );
  
  // JSON decode the string
  $json = json_decode($file);
  
  $settings['review_amount'] = 3;
  $settings['review_max_length'] = 150;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Trustpilot sample plugin</title>
  <link rel="stylesheet" href="assets/css/reset.css">
  <link rel="stylesheet" href="assets/css/sample.css" />
</head>

<body>
  <div class="tp-box<?php if(isset($_GET,$_GET['horizontal'])){?> horizontal<?php } ?>" id="tp-iframe-widget">
    <header>
      <h1><?php echo $json->TrustScore->Human; ?></h1>
      <img src="<?php echo $json->TrustScore->StarsImageUrls->large; ?>" alt="stars"/>
      <p class="review-count"><?php echo $json->ReviewCount->Total; ?> customers have written a review on Trustpilot</p>
    </header>
  
    <section class="reviews">
    <h1>Latest reviews</h1>
    <?php for($i = 1; $i <= $settings['review_amount']; $i++) : ?>
      <?php $review = $json->Reviews[$i]; ?>
      <article>
        <img src="<?php echo $review->TrustScore->StarsImageUrls->small; ?>" alt="review stars"/>
        <time datetime="<?php echo date('c',$review->Created->UnixTime); ?>"><?php echo $review->Created->HumanDate; ?></time>
        <h3><?php echo $review->Title; ?></h3>
        <p class="desc"><?php echo substr($review->Content, 0, $settings['review_max_length']); ?></p>
        <img src="<?php echo $review->User->ImageUrls->i24; ?>" alt="<?php echo $review->User->Name; ?>" class="user-img" />
        <p class="author">
          <?php echo $review->User->Name; ?><br />
          <?php echo $review->User->City; ?>
        </p>
      </article>
    <?php endfor; ?>
    </section>
    <a class="footer" href="<?php echo $json->ReviewPageUrl; ?>" target="_blank">Trust<span class="pilot">pilot</span></a>
  </div>
<?php if(isset($_GET,$_GET['horizontal'])){ ?>
  <p><a href="./sample.php">Vertical</a></p>
<?php }else{ ?>
  <p style="clear:both;"><a href="./sample.php?horizontal">Horizontal</a></p>
<?php } ?>
</body>
</html>
