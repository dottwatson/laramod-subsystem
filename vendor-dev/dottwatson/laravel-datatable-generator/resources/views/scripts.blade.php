
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    if(!('pageTables' in window)){
        window.pageTables = {};
    }
    
    $(function () {
        
        var table = $('#{!!$id!!}').DataTable({
    @foreach($table->getOptions() as $optionName => $optionValue)
            "{!!$optionName!!}":{!! Dottwatson\DatatableGenerator\Table::jsonEncode($optionValue) !!},
    @endforeach
            language: {!!json_encode(__('datatables'))!!},
            processing: true,
            serverSide: true,
            ajax: {
                url:"{!! $table->getEndPoint() !!}",
                type:"post",
                data: function(d){
                    return $.extend( {}, d, {
                        "_token": "{{ csrf_token() }}"
                    } );
                }
            },
            dom: 'lBfrtip',
            buttons:  [
                @foreach($table->getButtons() as $button)
                    {!! $button->render() !!},
                @endforeach
            ],
            select: true,
            columns: {!! json_encode($table->getScriptColumns(),JSON_PRETTY_PRINT) !!},
            @php
            if($table->getOption('searchBuilder')){
                $searchableColumns = [];
                foreach($table->getScriptColumns() as $k=>$column){
                    if($column['searchable'] == true){
                        $searchableColumns[]=$k;
                    }
                }
                echo "searchBuilder:".json_encode(['columns'=>$searchableColumns]).',';
            }
            @endphp
            
        });

        table.buttons().container().appendTo( '#{!!$id!!}_wrapper .col-md-6:eq(0)' );

        @if($table->getOption('searchBuilder'))
        table.searchBuilder.container().prependTo(table.table().container());
        @endif
        if('{!!$id!!}' in window.pageTables){
            window.pageTables['{!!$id!!}'].push(table);
        }
        else{
            window.pageTables['{!!$id!!}'] = [table];
        }
    });
});
</script>