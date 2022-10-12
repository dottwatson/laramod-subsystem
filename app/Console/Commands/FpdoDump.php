<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FpdoDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fpdo:dump {database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump a database';

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
        $exportOptions = [ 
            ['option'=>'schema'     ,'message'=>'Create table'],
            ['option'=>'not_exists' ,'message'=>'If create table, do that only if not existst'],
            ['option'=>'force'      ,'message'=>'If create table, drop the previouse'],
            ['option'=>'data'       ,'message'=>'Exporta data as insert statement'],
            ['option'=>'truncate'   ,'message'=>'Empty previouse table if existst']
        ];

        $database = $this->argument('database');
        
        $availableTables = array_keys(config("database.connections.{$database}.tables",[]));
        if(!$availableTables){
            $this->error("There are not tables in {$database} or database does not exists");
            return 0;
        }

        $this->info('exporting tables from '.$database);
        $exportAllTables = $this->confirm('Export all tables?', true);

        if(!$exportAllTables){
            $chosenTables = [];
            while(count($chosenTables) == 0){
                $chosenTables = $this->choice(
                    'Select tables you want to export',
                    $availableTables,
                    null,null,true
                );
            }

            // dd($chosenTables);
            foreach($chosenTables as $selectedTable){
                $selectedTableIndex = array_search($selectedTable,$availableTables);
                $message =  "Select export options for table  {$selectedTable}";
                $choises = ['-1'=>'All options'] + array_column($exportOptions,'message');

                $exportTableOptions = $this->choice(
                    $message,
                    $choises,
                    -1,null,true
                );

                dump($exportTableOptions);
            }           
        }


        // dd($exportAllTables);

        //         return 0;
    }
}
