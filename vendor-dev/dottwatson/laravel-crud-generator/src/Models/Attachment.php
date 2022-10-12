<?php

namespace Dottwatson\CrudGenerator\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Attachment extends Model
{

    protected $table    = 'attachments';
    protected $guarded  = ['id'];
    protected $casts    = ['extrafields'=>'array'];

    /**
     * aletr collection and gives to models the correct model
     *
     * @param array $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        foreach($models as &$model){
            $modelClass = $model->model;
            if($modelClass !=  static::class){
                $model = $modelClass::find($model->getKey());
            }
        }

        return new Collection($models);
    }

    /**
     * add event on delete, where deletes file is attachment is deleted
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleted(function ($model){
            $storage =  Storage::disk($model->disk);
            $storage->delete($model->fullPath);

            //recursively delete empty folders to keep storage clean
            $pathBlocks = explode('/',$model->path);
            while(count($pathBlocks) > 0){
                $path = implode('/',$pathBlocks);
                Log::info('CHecking '.$path);
                $dirs = $storage->directories($path);
                $files =$storage->files($path);

                if(!$dirs && !$files){
                    Log::info('is empty');
                    $storage->deleteDirectory($path);
                    array_pop($pathBlocks);
                }
                else{
                    break;
                }
            }
        });
    }


    /**
     * get the stream of the model, for example a video
     *
     * @return resource
     */
    public function getStream()
    {
        $fullPath = ($this->path)
            ?"{$this->path}/{$this->name}"
            :$this->name;

        return Storage::disk($this->disk)->readStream($fullPath);
    }

    /**
     * returns the full path and name of file
     *
     * @return string
     */
    public function getFullPathAttribute()
    {
        $fullPath = ($this->path)
        ?"{$this->path}/{$this->name}"
        :$this->name;

        return $fullPath;
    }


    /**
     * create a download response, with headers
     *
     * @param string|null $name The optional filename in header response
     * @return void
     */
    public function download(string $name=null)
    {
        $disk       = Storage::disk($this->disk);
        $stream     = $disk->readStream($this->fullPath);
        $size       = $disk->size($this->fullPath);

        if(is_null($name)){
            $date = Carbon::now()->format('Ymd_His');
            $name = ($this->title == '')
                ?'attachment_'.$this->id
                :Str::slug($this->title);

            $name = "{$date}_{$name}.{$this->original_extension}";
        }

        if (ob_get_level()) ob_end_clean();

        $callback = function () use ($stream) {
            fpassthru($stream);
        };

        $headers = [
            'Content-Description'       => 'File Transfer',
            'Content-Transfer-Encoding' => 'binary',
            'Connection'                => 'Keep-Alive',
            'Expires'                   => 0,
            'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
            'Pragma'                    => 'public',
            'Content-Length'            => $size,
            'Content-Type'              => $this->mime_type,
            'Content-disposition'       => 'attachment; filename="'.$name.'"',
        ];

        return response()->stream($callback,200,$headers);
    }

    /**
     * returns the file content response, with headers
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function view()
    {
        $disk       = Storage::disk($this->disk);
        $stream     = $disk->readStream($this->fullPath);
        $size       = $disk->size($this->fullPath);

        if (ob_get_level()) ob_end_clean();

        $callback = function () use ($stream) {
            fpassthru($stream);
        };

        $headers = [
            'Content-Type'              => $this->mime_type,
            'Content-Transfer-Encoding' => 'binary',
            'Connection'                => 'Keep-Alive',
            'Expires'                   => 0,
            'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
            'Pragma'                    => 'public',
            'Content-Length'            => $size,
        ];

        return response()->stream($callback,200,$headers);
    }

    /**
     * Converts a long string of bytes into a readable format e.g KB, MB, GB, TB, YB, relative to the file size
     * 
     * @return string
     */
    function getReadableSizeAttribute(){
        $bytes = $this->size;
        $i = floor(log($bytes) / log(1024));

        $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

        $sizeLabel = isset($sizes[$i]) ? $sizes[$i]:'';

        return sprintf('%.02F', $bytes / pow(1024, $i)) * 1 . ' ' . $sizeLabel;
    }
    

}
