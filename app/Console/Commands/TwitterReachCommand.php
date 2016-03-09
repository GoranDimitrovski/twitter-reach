<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Interfaces\TwitterReachInterface;
use Log;

class TwitterReachCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'twitter-reach:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk update of the reach of the saved tweets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire(TwitterReachInterface $twitterReach)
    {
        $errors = false;
        try {
            $errors = $twitterReach->updateReachBulk();
        } catch (\Exception $e) {
            Log::error($e);
            $this->error('There followin error has occured while updating the tweet range:');
            $this->error($e->getMessage());

        }
        if (!$errors) {
            $this->info('The update has been successfull');
        } else {
            $this->info('Some errors occured during the update');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
        ];
    }

}
