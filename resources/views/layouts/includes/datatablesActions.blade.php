@if ($extraActions ?? false)
    {!! $extraActions !!}
@endif
@if ($viewGate ?? false)
    @can($viewGate)
        <a class="btn btn-sm btn-primary" href="{{ $viewRoute ?? route($crudRoutePart . '.show', $row->id) }}"
            title="Detail"><i class="fa fa-eye"></i></a>
    @endcan
@endif
@if ($editGate ?? false)
    @can($editGate)
        <a class="btn btn-sm btn-info" href="{{ $editRoute ?? route($crudRoutePart . '.edit', $row->id) }}" title="Edit"><i
                class="fa fa-edit"></i></a>
    @endcan
@endif
@if ($deleteGate ?? false)
    @can($deleteGate)
        <button onclick="deleteData({{ $row->id }})" title="Delete" class="btn btn-danger btn-sm"><i
                class="fa fa-trash"></i></button>
    @endcan
@endif
