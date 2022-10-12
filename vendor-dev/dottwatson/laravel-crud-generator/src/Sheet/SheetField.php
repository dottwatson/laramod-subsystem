<?php
namespace Dottwatson\CrudGenerator\Sheet;

use Illuminate\Contracts\View\Factory as View;

class SheetField{
    /**
     * field name
     *
     * @var string
     */
    protected $name;
    /**
     * field type
     *
     * @var string
     */
    protected $type;

    /**
     * field options
     *
     * @var array
     */
    protected $options = [];

    /**
     * view factory
     *
     * @var View
     */
    protected $view;

    /**
     * field blade template
     *
     * @var string
     */
    protected $template;

    public function __construct(string $name,string $type,array $options = [],Sheet $sheet)
    {
        $this->name     = $name;
        $this->type     = $type;
        $this->options  = $options;
        $this->sheet    = $sheet;

        $this->view     = app(View::class);
        $this->template = "laravel-crud-generator::sheet.fields.{$type}";
    }

    /**
     * returns the View factory for field
     *
     * @return View
     */
    protected function getView()
    {
        return $this->view;
    }

    /**
     * returns this current sheet
     *
     * @return void
     */
    public function getSheet()
    {
        return $this->sheet;
    }

    /**
     * return s the model of the sheet
     *
     * @return Illuminate\Database\Eloquent\Model|null
     */
    public function getModel()
    {
        return $this->sheet->getModel();
    }

    public function getName()
    {
        return $this->name;
    }


    /**
     * returns the rendered fiel for sheet
     *
     * @return string
     */
    public function render()
    {
        $value = $this->sheet->getModel()->{$this->name};
        return $this->getView()
            ->make($this->template)
            ->with(['name'=>$this->name,'type'=>$this->type,'options'=>$this->options])
            ->with('value',$value)
            ->with('sheet',$this->sheet)
            ->with('model',$this->getModel());
    }
}