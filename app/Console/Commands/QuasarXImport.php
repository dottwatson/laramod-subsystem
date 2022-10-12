<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB as LaravelDB;
use Illuminate\Support\Carbon;
use App\Helpers\QuasarX\FileParser;
use Illuminate\Support\Facades\Mail;

class QuasarXImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quasar-x:import {type} {file} {mode?} {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $mailMessage = '';

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
        
        $filename   = $this->argument('file');
        $now        = Carbon::now();
        
        $parser   = new FileParser($filename);
        $table      = 'app_'.$this->argument('type');

        LaravelDB::table($table)->truncate();
        $displayName = ($this->argument('name'))?$this->argument('name'):$this->argument('file');
        try{
            $this->doMessage("Found file {$displayName} generated at {$parser->date()}");
            $this->doMessage("Quasar query: {$parser->query()}");
            $this->doMessage("Total rows declared: {$parser->count()}");
    
            if($this->inConsole()){
                $bar = $this->output->createProgressBar($parser->count());
            }
    
            $cnt            = 0;
            $skipped        = 0;
            $skippedRows    = [];
            foreach($parser->rows() as $row){
                if(implode('',$row) == ''){
                    $skipped++;
                    $skippedRows[] = $row;
    
                    continue;
                }
    
                $row['id']          = null;
                $row['import_date'] = $now;
    
                LaravelDB::table($table)->insert($row);
    
                if($this->inConsole()){
                    $bar->advance();
                }
    
                $cnt++;
            }
    
            if($this->inConsole()){
                $bar->finish();
                $this->info('');
            }
    
            $this->doMessage("Imported {$cnt} rows, skipped {$skipped} into {$table} with identifier date {$now}");
    
            if($skippedRows){
                $this->doMessage("Skipped rows:");
    
                $this->doMessage($skippedRows);
            }
    
            if(!$this->inConsole()){
                Mail::raw($this->mailMessage, function($message) use ($table,$filename) {
                    $message
                    ->to('mirko.temperini@prismi.net')
                    ->subject("[Site Import] - Import {$this->argument('type')} report");
                });
    
            }
    
            $parser->releaseFile();
    
            return 'OK';
        }
        catch(\Exception $e){
            $text = "Si è verificato un problema durante l\'importazione dei dati\nIl server riporta\n\n{$e->getMessage()}";
            Mail::raw($this->mailMessage, function($message) use ($table,$filename) {
                $message
                ->to('mirko.temperini@prismi.net')
                ->subject("[Site Import] - Import {$this->argument('type')} report");
            });

            $parser->releaseFile();
        }
    }


    protected function inConsole()
    {
        return $this->argument('mode') != 'job';
    }

    public function doMessage($message)
    {
        
        if($this->inConsole()){
            $message = '['.date('Y-m-d H:i:s').'] '.$message;
            $this->info($message);
        }
        else{
            if(!is_string($message)){
                $message = json_encode($message,JSON_PRETTY_PRINT);
            }

            $message = '['.date('Y-m-d H:i:s').'] '.$message;

            $this->mailMessage.=$message."\n\n";
        }
    }

}
