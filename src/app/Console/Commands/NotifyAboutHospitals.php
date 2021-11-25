<?php

namespace App\Console\Commands;

use App\Models\FederalState;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
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
        Telegram::sendMessage([
            'chat_id'    => config('telegram.bots.time_waster.chat_id'),
            'text'       => $this->getTelegramMessage(FederalState::getHospitalInfo(FederalState::STATE_BAVARIA)),
            'parse_mode' => 'Markdown'
        ]);

        return Command::SUCCESS;
    }

    /**
     * Human readable message formatted to be used in Telegram.
     *
     * @param array $federalState
     * @return string
     */
    protected function getTelegramMessage(array $federalState)
    {
        /*
         * Setup variables for easier-to-read code below
         */

        $federalStateName    = Str::of($federalState['bundesland'])->title();
        $percentageBedsInUse = round($federalState['bettenBelegtToBettenGesamtPercent']) . '%';
        $intenseCareInUse    = $federalState['intensivBettenBelegt'];
        $intenseCareBeds     = $federalState['intensivBettenGesamt'];
        $percentageAspirated = round($federalState['faelleCovidAktuellBeatmetToCovidAktuellPercent']) . '%';
        $creationTimestamp   = Carbon::createFromDate($federalState['creationTimestamp'])->locale('de_DE')->isoFormat('lll');

        // Percent COVID from JSON is "covid cases / all intense care beds" but we want percent covid of used beds
        $percentCovidBedsInUse = round($federalState['faelleCovidAktuell'] / $intenseCareInUse * 100) . '%';

        /*
         * Now create the message
         */

        $text = 'Krankenhausauslastung *' . $federalStateName . ': ' . $percentageBedsInUse . "\n";
        $text .= 'Intensivbetten belegt: ' . $intenseCareInUse . ' von ' . $intenseCareBeds . "\n";
        $text .= '... davon COVID: ' . $percentCovidBedsInUse . "\n";
        $text .= '... davon beatmet: ' . $percentageAspirated . "\n";
        $text .= "\n--\n";
        $text .= '_Daten abgerufen am ' . $creationTimestamp . '_' . "\n";
        $text .= 'Quelle: [intensivregister.de](https://www.intensivregister.de/#/aktuelle-lage/laendertabelle)';

        return $text;
    }
}
