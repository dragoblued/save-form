@extends('layouts.admin')
@section('content')

    <table class="list">
        <thead>
            <tr class="list__head">
                <td class="list__id">#</td>
                @include($page->route)

            @if(in_array('edit', $page->func))
                <td class="list__function"></td>
            @endif
            @if(in_array('delete', $page->func))
                <td class="list__function"></td>
            @endif

            </tr>

        </thead>

        @if(count($items) > 0)
            <tfoot>
                <tr class="list__head">
                    <td class="list__id">#</td>
                    @include($page->route)

                @if(in_array('edit', $page->func))
                    <td class="list__function"></td>
                @endif
                @if(in_array('delete', $page->func))
                    <td class="list__function"></td>
                @endif

                </tr>
            </tfoot>

            @foreach($items as $item)
                <tr class="list__item" data-id="{{ $item->id }}">
                    <td class="list__id">{{ $item->id }}</td>
                    @include($page->route)

                @if(in_array('edit', $page->func))
                    <td class="list__function list__function_edit">
                        <a href="{{ route($page->route.'.edit', $item->id) }}" class="function list__function-button">
                            <i class="far fa-edit"></i>
                        </a>
                    </td>
                @endif

                @if(in_array('delete', $page->func))
                    <td class="list__function list__function_delete">
                        <a href="{{ route($page->route.'.destroy', $item->id) }}" class="function list__function-button js-delete">
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </td>
                @endif

                </tr>
            @endforeach
        @endif
    </table>

    @if(count($items) == 0)
        <div class="list-empty">В таблице нет записей</div>
    @endif
@endsection
