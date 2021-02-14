<?php

namespace App\Console\Commands;

use App\Models\Price;
use App\Src\Import\PriceObject;
use Illuminate\Console\Command;
use Illuminate\Support\LazyCollection;

class ImportPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import product prices';

    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var int
     */
    private $validCount;

    /**
     * @var int
     */
    private $invalidCount;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->filePath = resource_path('import/import.csv');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->loadCollection();

        $this->output->progressStart(count($this->collection));

        $this->processCollection();

        $this->output->progressFinish();

        $this->displayResume();
    }

    /**
     * Display resume
     */
    private function displayResume() {
        $this->info("Valid prices: " . $this->validCount);
        $this->warn("Invalid prices: " . $this->invalidCount);
    }


    /**
     * Create the CSV lazy collection
     */
    private function loadCollection()
    {
        $this->collection = LazyCollection::make(function () {
            $handle = fopen($this->filePath, 'r');
            while ($line = fgetcsv($handle)) {
                yield $line;
            }
        });
    }

    /**
     * Process the lazy collection
     */
    private function processCollection()
    {
        $this->collection->chunk(1000)
            ->each(function ($lines) {
                Price::insert($this->getInsertList($lines));
            });
    }

    /**
     * @param array $lines
     * @return array
     */
    private function getInsertList($lines)
    {
        $list = [];
        foreach ($lines as $line) {
            $priceObject = new PriceObject($line);

            if($priceObject->isValid()) {
                $list[] = $priceObject->toArray();
                $this->validCount++;
            } else {
                $this->invalidCount++;
            }

            $this->output->progressAdvance();
        }

        return array_filter($list);
    }
}
