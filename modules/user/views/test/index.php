<?php

use app\controllers\BaseController;
use yii\data\ActiveDataProvider;

use app\models\Users;
use app\models\UserTracking;
use app\models\Products;
use app\models\PriceLogs;
use app\models\Controllers;
use app\models\Mailer;

use app\helpers\Checks;
use app\helpers\MyFormat;
use app\helpers\Constants;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
?>


  <header>
    <h1>Push Codelab</h1>
  </header>

  <main>
    <p>Welcome to the push messaging codelab. The button below needs to be
    fixed to support subscribing to push.</p>
    <p>
      <button  class="js-push-btn mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
        Enable Push Messaging
      </button>
    </p>
    <div id="data"></div>
    <section class="subscription-details js-subscription-details is-invisible">
      <p>Once you've subscribed your user, you'd send their subscription to your
      server to store in a database so that when you want to send a message
      you can lookup the subscription and send a message to it.</p>
      <p>To simplify things for this code lab copy the following details
      into the <a href="https://web-push-codelab.glitch.me//">Push Companion
      Site</a> and it'll send a push message for you, using the application
      server keys on the site - so make sure they match.</p>
      <pre><code class="js-subscription-json"></code></pre>
    </section>
  </main>

  <script src="http://localhost/trackers/web/js/notifications.js"></script>


<?php
$subscription = '';
$notifications = '';
if(isset($_GET['subscription'])){
    $subscription = json_decode($_GET['subscription'], true);
    $notifications = Subscription::create($subscription);
}
if(empty($subscription)) return;
//$auth = [
//    'GCM' => 'AIzaSyD4nGP3KEYGIzJsasugHuPtKaPgEJo1IXw', // deprecated and optional, it's here only for compatibility reasons
//    'VAPID' => [
//        'subject' => 'mailto:admin@chartcost.com', // can be a mailto: or your website address
//        'publicKey' => $subscription['keys']['p256dh'], // (recommended) uncompressed public key P-256 encoded in Base64-URL
//        'privateKey' => 'EgDk-s0GqLfiTzAuJ3_KRQf17uytVCD4UD32U377EKk', // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL
//        'pemFile' => 'http://localhost/trackers/private_key.pem', // if you have a PEM file and can link to it on your filesystem
////        'pem' => 'MHcCAQEEIBIA5PrNBqi34k8wLid/ykUH9e7srVQg+FA99lN++xCpoAoGCCqGSM49AwEHoUQDQgAEipLf4kVyemEPIr34yhzXNi5r8lIMMU0DDQHY3evpIga3DUAD5jD025tr5mpz7EHbuMX102f81wwn2027qgQDGA==', // if you have a PEM file and want to hardcode its content
//    ],
//];

$auth = array(
    'VAPID' => array(
        'subject' => 'https://github.com/Minishlink/web-push-php-example/',
        'publicKey' => file_get_contents('http://localhost/trackers/public_key.txt'), // don't forget that your public key also lives in app.js
        'privateKey' => file_get_contents('http://localhost/trackers/private_key.txt'), // in the real world, this would be in a secret file
    ),
);
$webPush = new WebPush($auth);
    $webPush->sendNotification(
        $notifications,
        '{"msg":"MY MSG"}'
    );
// handle eventual errors here, and remove the subscription from your server if it is expired
foreach ($webPush->flush() as $report) {
    $endpoint = $report->getRequest()->getUri()->__toString();
    if ($report->isSuccess()) {
        echo "[v] Message sent successfully for subscription {$endpoint}.";
    } else {
        echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
    }
}
