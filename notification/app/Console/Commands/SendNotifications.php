<?php

namespace App\Console\Commands;

use App\Notification;
use App\SentNotification;
use GuzzleHttp\Client;

use Illuminate\Console\Command;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notification {notification : The ID of the notification}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command used to send notification to users';

    protected $notification;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Notification $notification)
    {
        parent::__construct();
        $this->notification = $notification;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notification = $this->notification->findOrFail($this->argument('notification'));
        $user = $notification->user;

        $subscribers = $this->prepareDids($user->subscribers); 

        // Set various headers on a request
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://android.googleapis.com',
            // You can set any number of default request options.
            'timeout'  => 2.0
        ]);

        $response = $client->request('POST', '/gcm/send', [
            'headers' => [
                'Content-Type'     => 'application/json',
                'Authorization' => 'key=AIzaSyCzvaekWSLHG7FAf-IV3QS3bBJMvdY6k1s'
            ], 
            'body' => json_encode($subscribers)
        ]);
        
        if ($response->getStatusCode() == 200) {
          $attributes = array(
              'user_id' => $user->id,
              'notification_id' => $this->argument('notification'),
              'gcm_response' => json_decode($response->getBody()->getContents()),
              'registration_ids' => $subscribers['registration_ids']
          );
          SentNotification::create($attributes);
        }

        $this->info($response->getStatusCode());
        $this->info($response->getBody()->getContents());
    }

    public function prepareDids($subscribers)
    {
      $registration_ids = [];
      $didsArray = [];
      foreach ($subscribers as $key => $value) {
        $didsArray[] = $value->did;
      }
      if (count($didsArray) !== 0) {
        $registration_ids['registration_ids'] = $didsArray;
      }

      return $registration_ids;
    }


}
