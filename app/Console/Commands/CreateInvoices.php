<?php

namespace App\Console\Commands;

use App\Http\Businesses\V1\Agency\RequestServiceBusiness;
use App\Http\Services\V1\Agency\CustomerServiceRequestService;
use App\Jobs\CreateInvoiceJob;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class CreateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Recurring Invoices.';

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
    public function handle()
    {
        $request = new Request();
        $request->status = "active";
        $serviceRequests = CustomerServiceRequestService::getRecurringServiceRequests($request);

        if ($serviceRequests->isNotEmpty()) {
            $chunks = $serviceRequests->chunk(1000);
            foreach ($chunks as $serviceRequests) {
                dispatch(new CreateInvoiceJob($serviceRequests))->onQueue('create_invoices');
            }
            $this->info('Recurring invoices created successfully!');
        } else {
            $this->info('No invoices found!');
        }
    }
}
