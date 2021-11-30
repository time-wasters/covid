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
            'chat_id'                  => config('telegram.bots.hospital_notifier.chat_id'),
            'text'                     => $this->getTelegramMessage(FederalState::getHospitalInfo(FederalState::STATE_BAVARIA)),
            'parse_mode'               => 'Markdown',
            'disable_web_page_preview' => true
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

        $federalStateName     = Str::of($federalState['bundesland'])->title();
        $percentageBedsInUse  = round($federalState['bettenBelegtToBettenGesamtPercent']) . '%';
        $intenseCareAvailable = $federalState['intensivBettenFrei'];
        $covidAvailable       = $federalState['covidKapazitaetFrei'];
        $intenseCareInUse     = $federalState['intensivBettenBelegt'];
        $intenseCareBeds      = $federalState['intensivBettenGesamt'];
        $percentageAspirated  = round($federalState['faelleCovidAktuellBeatmetToCovidAktuellPercent']) . '%';
        $creationTimestamp    = Carbon::createFromDate($federalState['creationTimestamp'])->locale('de_DE')->isoFormat('lll');

        // Percent COVID from JSON is "covid cases / all intense care beds" but we want percent covid of used beds
        $percentCovidBedsInUse = round($federalState['faelleCovidAktuell'] / $intenseCareInUse * 100) . '%';

        /*
         * Now create the message
         */

        $text = $this->l('Krankenhausauslastung *' . $federalStateName . '*: ' . $percentageBedsInUse);
        $text .= $this->l();
        $text .= $this->l('Intensivbetten frei: ' . $intenseCareAvailable . ', für COVID: ' . $covidAvailable);
        $text .= $this->l('Intensivbetten belegt: ' . $intenseCareInUse . ' von ' . $intenseCareBeds);
        $text .= $this->l('... davon COVID: ' . $percentCovidBedsInUse);
        $text .= $this->l('... davon beatmet: ' . $percentageAspirated);
        $text .= $this->footer($creationTimestamp);

        return $text;
    }

    /**
     * Get one line of text
     *
     * @param string $text
     * @return string
     */
    protected function l(string $text = null): string{
        return $text . "\n";
    }

    /**
     * Get footer as text
     *
     * @param string $creationTimestamp
     * @return string
     */
    protected function footer(string $creationTimestamp): string{
        return $this->l() . $this->l('--')
               . $this->l('_Daten abgerufen am ' . $creationTimestamp . '_')
               . 'Quelle: [intensivregister.de](https://www.intensivregister.de/#/aktuelle-lage/laendertabelle)';
    }
}
