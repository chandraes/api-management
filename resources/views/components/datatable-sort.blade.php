<div class="flex items-center">
    {{Str::upper($columnName)}}
    @if ($sortColumn !== $columnName)
    <i class="fa fa-sort pl-3"></i>
    @elseif ($sortDirection === 'ASC')
        <i class="fa fa-sort-down pl-3"></i>
    @else
        <i class="fa fa-sort-up pl-3"></i>

    @endif
</div>
