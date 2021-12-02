<?php

namespace App\Console\Commands;

use App\Services\ArcGisClient;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class NotifyAboutConfirmedCases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:about-confirmed-cases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifies about the number of confirmed COVID-cases';

    protected $arcGis;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ArcGisClient $arcGis)
    {
        $this->arcGis = $arcGis;

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
            'text'                     => $this->getTelegramMessage($this->arcGis->getConfirmedCases()),
            'parse_mode'               => 'HTML',
            'disable_web_page_preview' => true,
        ]);

        return Command::SUCCESS;
    }

    /**
     * Human readable message formatted to be used in Telegram.
     *
     * @param array $counties
     * @return void
     */
    protected function getTelegramMessage(array $counties){
        $html = '';

        // ToDo: Fill placeholders

        foreach($counties as $county){
            $html .= $this->l('<ins>' . $county['GEN'] . ' (' . $county['BEZ'] . ')' . '</ins>');
            $html .= $this->l('Fälle (gesamt):            <em>10549</em> [+108]');
            $html .= $this->l('7Tage/100K EW:         <strong>547</strong> [-21]');
            $html .= $this->l('Fälle letzte 7 Tage:     <strong>547</strong> [-21]');
            $html .= $this->l('Todesfälle (gesamt):  <em>125</em> [-21]');
            $html .= $this->l();
        }

        $html .= $this->footer('now');

        return $html;

    }

    /**
     * Create a line of text using a linebreak at the end
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
        return $this->l()
               . $this->l('Dec 2, 2021')
               . $this->l('<i>Quelle:</i> <a href="https://experience.arcgis.com/experience/478220a4c454480e823b17327b2bf1d4" title="https://experience.arcgis.com/experience/478220a4c454480e823b17327b2bf1d4" target="_blank" rel="noopener noreferrer" class="text-entity-link" dir="auto">RKI</a>');
    }
}
