<?php

namespace App\Console\Commands;

use App\Models\FederalState;
use Illuminate\Console\Command;

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

        // ToDo: Notify

        return Command::SUCCESS;
    }
}
