<div class="mt-3">
    <table id="{{$id}}" class="datatable table table-striped table-bordered table-hover table-sm  dt-responsive nowrap" style="width:100%">
        <thead>
            <tr class="bg-primary">
                @foreach ($table->getColumns() as $column)
                    <th>{!! ($column->toHTMLTable()['label']) !!}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
