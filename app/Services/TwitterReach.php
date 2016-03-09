<?php namespace App\Services;

use App\Interfaces\TwitterReachInterface;
use PhpSpec\Exception\Exception;
use Abraham\TwitterOAuth\TwitterOAuth;
use Config;
use Validator;
use App\Reach;
use App\Tweet;
use Log;

class TwitterReach implements TwitterReachInterface
{

    /**
     * @var TwitterOAuth
     */
    private $connection;

    /**
     * TwitterReach constructor.
     */
    function __construct()
    {
        try {
            $this->connection = new TwitterOAuth(
                Config::get('twitter.CONSUMER_KEY'),
                Config::get('twitter.CONSUMER_SECRET'),
                Config::get('twitter.ACCESS_TOKEN'),
                Config::get('twitter.ACCESS_TOKEN_SECRET')
            );
        } catch (\Exception $e) {
            throw new \Exception("Could not establish connection to the Twitter API");
        }
    }

    /**
     * Method that gets the reach of the tweet from the Twitter API
     * @param $tweetId
     * @return int
     * @throws \Exception
     */
    private function getReach($tweetId)
    {
        $count = 0;

        try {
            $response = $this->connection->get('statuses/retweets', array('id' => $tweetId));
            $statusCode = $this->connection->getLastHttpCode();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new \Exception("Unexpected error has occurred. Please try again.");
        }

        if ($statusCode === 200) {

            foreach ($response as $item) {
                if (is_object($item) && property_exists($item, 'user')) {
                    $user = $item->user;

                    if (property_exists($user, 'followers_count')) {
                        $count += (int)$item->user->followers_count;
                    }
                }
            }

        } else if ($statusCode === 404) {
            throw new \Exception("The twitter status does not exist");
        } else {
            throw new \Exception("Unexpected error has occurred");
        }


        return $count;
    }

    /**
     * Method that updates the tweet reach
     * @param $tweet
     * @param $reach
     */
    private function updateTweet($tweet, $reach)
    {
        if (!is_null($tweet)) {
            $tweet->reach->reach = $reach;
            $tweet->reach->touch();//update the timestamps of the model
            $tweet->push();
        }
    }

    /**
     * Method that saves new tweet and its range
     * @param $tweetId
     * @param $url
     * @param $reach
     */
    private function saveTweet($tweetId, $url, $reach)
    {
        $validator = Validator::make(
            ['tweet_id' => $tweetId, 'url' => $url, 'reach' => $reach],
            ['tweet_id' => 'required|unique:tweet', 'url' => 'required|unique:tweet|url', 'reach' => 'required|numeric']
        );

        if (!$validator->fails()) {
            $tweet = Tweet::create(['tweet_id' => $tweetId, 'url' => $url]);
            $tweet->reach()->save(new Reach(['reach' => $reach]));
        }
    }

    /**
     * Method that parses and validate the twitter url
     * @param $url
     * @return mixed|null
     */
    private function parserUrl($url)
    {
        $urlArray = parse_url($url);
        $statusId = null;

        // check if url is valid twitter url status
        if ($urlArray['host'] === 'twitter.com' && preg_match('/\/status\//', $urlArray['path'])) {
            $pathArray = explode('/', $urlArray['path']);
            $statusId = end($pathArray);
        }

        return $statusId;
    }

    /**
     * Method that gets and/or saves the tweet and its reach
     * @param $url
     * @return int
     * @throws \Exception
     */
    public function getTweetReach($url)
    {
        $tweetId = $this->parserUrl($url);

        if (is_null($tweetId)) {
            throw new \Exception('Please enter a valid tweet url');
        }

        $tweet = Tweet::where(['tweet_id' => $tweetId])->first();

        if (!is_null($tweet)) {
            return $tweet->reach->reach;
        }

        $reach = $this->getReach($tweetId, $url);
        $this->saveTweet($tweetId, $url, $reach);

        return $reach;
    }

    /**
     * Method for bulk update of the reach of the saved tweets
     */
    public function updateReachBulk()
    {
        $tweets = Tweet::all();
        $errors = false;

        foreach ($tweets as $tweet) {
            try {

                $reach = $this->getReach($tweet->tweet_id);
                $this->updateTweet($tweet, $reach);

            } catch (\Exception $e) {
                $errors = true;
                Log::error('Coud not update the status of the tweet ' . $tweet->url . ' ' . $e->getMessage());
            }
        }

        return $errors;
    }
}
