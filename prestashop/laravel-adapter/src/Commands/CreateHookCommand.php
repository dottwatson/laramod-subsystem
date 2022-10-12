<?php
namespace PrestashopLaravelAdpter\Commands;

use Illuminate\Console\Command;

class CreateHookCommand extends Command{
    protected $signature = 'prestashop:hook {hookClassName}';

    protected $description = 'Create an Hook';


    public function handle()
    {
        $className  = $this->argument('hookClassName');
        $stubPath   = realpath(__DIR__.'/../../stubs/hook.stub');
        $pathTo     = app_path('Hooks');

        if(is_file("{$pathTo}/{$className}.php")){
            $this->error('Hook '.$className.' already exists');
            return false;
        }

        if(!is_dir($pathTo)){
            mkdir($pathTo);
        }

        $modelContent = file_get_contents($stubPath);

        $source = str_replace('__hookClassName__',$className,$modelContent);

        file_put_contents("{$pathTo}/{$className}.php",$source);

        $this->info("Hook created in {$pathTo}/{$className}.php");
    }

}