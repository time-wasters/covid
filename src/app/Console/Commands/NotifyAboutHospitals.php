<?php

namespace App\Console\Commands;

use App\Models\FederalState;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class NotifyAboutHospitals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:about-hospitals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifies about the state of hospitals';

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
     * @return int
     */
    public function handle()
    {
        $federalState = FederalState::getHospitalInfo(FederalState::STATE_BAVARIA);

        $response = Telegram::sendMessage([
            'chat_id' => config('telegram.bots.time_waster.chat_id'),
            'text' => 'Hello World'
        ]);

        return Command::SUCCESS;
    }
}
