<?php 
namespace Dottwatson\Fpdo\Parser;

use Dottwatson\Fpdo\Resource;
use Dottwatson\Fpdo\DataParser;

class ArrayParser extends DataParser{
    protected $type = 'array';

    /**
     * @inheritDoc
     */
    protected function reader()
    {
        $sourceData = Resource::acquire($this->source);

        return $sourceData;
    }


    /**
     * @inheritDoc
     */
    protected function writer()
    {
        return true;
    }


}

?>