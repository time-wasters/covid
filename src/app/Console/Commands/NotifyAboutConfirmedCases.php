<?php

namespace App\Console\Commands;

use App\Services\ArcGisClient;
use Illuminate\Console\Command;

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
        $counties = $this->arcGis->getConfirmedCases();
        
        // ToDo: Implement

        return Command::SUCCESS;
    }
}
