@if(is_object($page))
    <h1 class="content__header">
        {{ property_exists($page, 'h1') ? $page->h1 : $page->title }}
        @if(property_exists($page, 'func'))
            @if(in_array('create', $page->func))
                <a href="{{ route("{$page->route}.create") }}" class="content__add function">
                    <i class="fa-plus-square far"></i>
                </a>
            @endif
        @endif
    </h1>
    @if(Session::has('alert'))
        <div class="alert">
            {!! Session::get('alert') !!}
        </div>
    @endif
@else

    <h1 class="content__header">
        <div class="">{{$page['h1']}}</div>

    </h1>
    @if(Session::has('alert'))
        <div class="alert">
            {!! Session::get('alert') !!}
        </div>
    @endif

@endif