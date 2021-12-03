<?php

namespace App\Console\Commands;

use App\Services\DiviClient;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
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
     * API Client for DIVI-Intensivregister
     *
     * @var IntensivRegister
     */
    protected $divi;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DiviClient $divi)
    {
        $this->divi = $divi;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $federalState = $this->divi->getHospitalInfo(DiviClient::STATE_BAVARIA);
        $date         = Carbon::createFromDate($federalState['creationTimestamp'])->locale('de_DE')->isoFormat('lll');
        $message      = view('telegram.hospitals', ['federalState' => $federalState, 'date' => $date])->render();

        Telegram::sendMessage([
            'chat_id'                  => config('telegram.bots.hospital_notifier.chat_id'),
            'text'                     => $message,
            'parse_mode'               => 'HTML',
            'disable_web_page_preview' => true,
        ]);

        return Command::SUCCESS;
    }
}
