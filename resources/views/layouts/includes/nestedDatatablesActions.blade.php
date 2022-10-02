@if ($extraActions ?? false)
{!! $extraActions !!}
@endif
@if($viewGate ?? false)
    <a class="btn btn-sm btn-primary" href="{{ $viewRoute ?? route($crudRoutePart . '.show', [$prefixRoute, $row->id]) }}" title="Detail"><i class="fa fa-eye"></i></a>
@endif
@if($editGate ?? false)
    <a class="btn btn-sm btn-info" href="{{ $editRoute ?? route($crudRoutePart . '.edit', [$prefixRoute, $row->id]) }}" title="Edit"><i class="fa fa-edit"></i></a>
@endif
@if($deleteGate ?? false)
    <button onclick="deleteData({{ $row->id }})" title="Delete" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
@endif
