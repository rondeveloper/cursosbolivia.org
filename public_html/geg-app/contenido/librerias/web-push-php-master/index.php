<?php

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// array of notifications
$notifications = [
    [
        'subscription' => Subscription::create([
            'endpoint' => 'https://updates.push.services.mozilla.com/push/abc...', // Firefox 43+,
            'publicKey' => 'BPcMbnWQL5GOYX/5LKZXT6sLmHiMsJSiEvIFvfcDvX7IZ9qqtq68onpTPEYmyxSQNiH7UD/98AUcQ12kBoxz/0s=', // base 64 encoded, should be 88 chars
            'authToken' => 'CxVX6QsVToEGEcjfYPqXQw==', // base 64 encoded, should be 24 chars
        ]),
        'payload' => 'hello !',
    ], [
        'subscription' => Subscription::create([
            'endpoint' => 'https://android.googleapis.com/gcm/send/abcdef...', // Chrome
        ]),
        'payload' => null,
    ], [
        'subscription' => Subscription::create([
            'endpoint' => 'https://example.com/other/endpoint/of/another/vendor/abcdef...',
            'publicKey' => '(stringOf88Chars)',
            'authToken' => '(stringOf24Chars)',
            'contentEncoding' => 'aesgcm', // one of PushManager.supportedContentEncodings
        ]),
        'payload' => '{msg:"test"}',
    ], [
          'subscription' => Subscription::create([ // this is the structure for the working draft from october 2018 (https://www.w3.org/TR/2018/WD-push-api-20181026/) 
              "endpoint" => "https://example.com/other/endpoint/of/another/vendor/abcdef...",
              "keys" => [
                  'p256dh' => '(stringOf88Chars)',
                  'auth' => '(stringOf24Chars)'
              ],
          ]),
          'payload' => '{msg:"Hello World!"}',
      ],
];

$webPush = new WebPush();

// send multiple notifications with payload
foreach ($notifications as $notification) {
    $webPush->sendNotification(
        $notification['subscription'],
        $notification['payload'] // optional (defaults null)
    );
}

/**
 * Check sent results
 * @var MessageSentReport $report
 */
foreach ($webPush->flush() as $report) {
    $endpoint = $report->getRequest()->getUri()->__toString();

    if ($report->isSuccess()) {
        echo "[v] Message sent successfully for subscription {$endpoint}.";
    } else {
        echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
    }
}

/**
 * send one notification and flush directly
 * @var \Generator<MessageSentReport> $sent
 */
$sent = $webPush->sendNotification(
    $notifications[0]['subscription'],
    $notifications[0]['payload'], // optional (defaults null)
    true // optional (defaults false)
);