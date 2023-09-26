
<?php
//Path to TwitterOAuth library
require_once(ADMIN_PATH . "inc/twitteroauth/twitteroauth.php");

//Configuration
//$twitterID = (isset($_REQUEST['twitterUsername']) && !empty($_REQUEST['twitterUsername']))?$_REQUEST['twitterUsername']:"codexworldblog";
$twitterID = "demo1258";
$tweetNum = 3;

$consumerKey = "Hea5FaNhHb87GtAlAhMZlxhL0";
$consumerSecret = "CAw8MiQWejh7kTruw9iNDZk8WlOAUHKvvVdu6REovQKswcW75t";
$accessToken = "2837980179-inArdGY0moQ31Sypw5RTQBJtBFnLFdI8QYIY7xF";
$accessTokenSecret = "KPBvDc23p4qWvYKlHzo5ddRmUp3dXlhukxo66Kkn9BARI"; 

if($twitterID && $consumerKey && $consumerSecret && $accessToken && $accessTokenSecret) {
      //Authentication with twitter
      $twitterConnection = new TwitterOAuth(
          $consumerKey,
          $consumerSecret,
          $accessToken,
          $accessTokenSecret
      );
      //Get user timeline feeds
      $twitterData = $twitterConnection->get(
          'statuses/user_timeline',
          array(
              'screen_name'     => $twitterID,
              'count'           => $tweetNum,
              'exclude_replies' => false
          )
      );

?>
    <?php
    if(!empty($twitterData)) {
        foreach($twitterData as $tweet):
            $latestTweet = $tweet->text;
            $latestTweet = preg_replace('/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '<a href="http://$1" target="_blank">http://$1</a>', $latestTweet);
            $latestTweet = preg_replace('/@([a-z0-9_]+)/i', '<a class="tweet-author" href="http://twitter.com/$1" target="_blank">@$1</a>', $latestTweet);
            $tweetDate = date("d M Y",strtotime($tweet->created_at));
            $tweetTime = date("g:i A",strtotime($tweet->created_at));
        ?>
            
            <p><?php echo $latestTweet; ?></p>
            <div class="fs_date"><?php echo $tweetTime; ?> - <?php echo $tweetDate; ?></div>
        
        <?php
        endforeach; 
    }else{
        echo '<p>Tweets not found for the given username.</p>'; 
    }
    ?>
             
<?php   
}else{
      echo '<p>Authentication failed, please try again.<p/>';
}
?>