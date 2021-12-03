<?php

namespace App\Console\Commands;

use App\Services\ArcGisClient;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
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

    /**
     * ArcGIS client to fetch necessary data
     *
     * @var ArcGisClient
     */
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
        $counties = $this->arcGis->getConfirmedCases();
        $date     = Carbon::createFromFormat('d.m.Y, H:i \U\h\r', Arr::get($counties->first(), 'last_update'));
        $message  = view('telegram.confirmed-cases', ['counties' => $counties, 'date' => $date->format('M j, Y')])->render();

        Telegram::sendMessage(
            [
                'chat_id'                  => config('telegram.bots.hospital_notifier.chat_id'),
                'text'                     => $message,
                'parse_mode'               => 'HTML',
                'disable_web_page_preview' => true,
            ]
        );

        return Command::SUCCESS;
    }
}
