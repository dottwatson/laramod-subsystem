<?php
namespace Dottwatson\DatatableGenerator;


class TableHelper{

    public static function actions(array $buttons = [])
    {
        $wrapper = '<div class="table-action">';

        foreach($buttons as $v){
            if(is_array($v)){
                $v = call_user_func_array((static::class).'::button',$v);
            }

            $wrapper.="\n".$v;
        }

        return $wrapper."\n</div>";
    }


    public static function button(string $style,string $label,string $target,array $attributes = [])
    {
        $tagAttributes  = [];
        $size           = $attributes['size'] ?? 'xs';
        foreach($attributes as $key=>$value){
            if(!in_array($key,['id','class','description'])){
                $tagAttributes[] = $key.'="'.$value.'"';
            }
        }


        $btn ='<a href="'.$target.'"'. 
            'id="'.($attributes['id'] ?? '').'" '.
            'class="btn btn-'.$style.' btn-'.$size.' '.($attributes['class'] ?? '').'" '.
            'alt="'.($attributes['description'] ?? '').'" '.
            'title="'.($attributes['description'] ?? '').'" '.
            implode(' ',$tagAttributes).'>'.
            ( isset($attributes['icon'])?'<i class="'.$attributes['icon'].'"></i> ':'').
            $label.
            '</a>';

        return $btn;
    }

    public static function buttonInfo(string $style,string $label,string $target,array $attributes = [])
    {
        $style = "outline-{$style}";
        $attributes['icon']='fa fa-info-circle';
        $attributes['description']='Informazioni';

        return self::button($style,$label,$target,$attributes);
    }

    public static function buttonEdit(string $style,string $label,string $target,array $attributes = [])
    {
        $attributes['icon']='fa fa-edit';
        $attributes['description']='Modifica';

        return self::button($style,$label,$target,$attributes);
    }

    public static function buttonDelete(string $style,string $label,string $target,array $attributes = [])
    {
        $attributes['icon']='fa fa-trash';

        return self::button($style,$label,$target,$attributes);
    }

    public static function buttonUser(string $style,string $label,string $target,array $attributes = [])
    {
        $attributes['icon']='fa fa-user';

        return self::button($style,$label,$target,$attributes);
    }

    public static function buttonAdd(string $style,string $label,string $target,array $attributes = [])
    {
        $attributes['icon']='fa fa-plus';

        return self::button($style,$label,$target,$attributes);
    }

    public static function jsSidebarLoad($path)
    {
        return "javascript:app.sidebar.load('".$path."')";
    }

}