<?php

namespace App\Console\Commands;

use App\Repository\Interfaces\DocRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class UpdateDocumentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of documents to "Basic" if not "Registered" or "Reviewed" within the deadline.';
    protected $docRepository;


    public function __construct(DocRepositoryInterface $docRepository)
    {
        parent::__construct();
        $this->docRepository = $docRepository;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->docRepository->updateExpiredDocs();

        $this->info('Document statuses updated successfully.');
    }
}
