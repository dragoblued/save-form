{{-- config('admin._menu') --}}

@isset($link)
	<li class="menu__item">
		<a href="{{ route($link->route) }}" class="menu__link{{ $link->route == "{$page->route}.index" ? ' menu__link_active' : '' }}">

			<span class="menu__ico">
				<i class="fa-{{ $link->ico }}" aria-hidden="true"></i>
			</span>

			{{ $link->title }}
		</a>
	</li>
@else
	<div class="menu">
		<a href="{{ route('admin.index') }}" class="menu__user">
			<span class="menu__user-name">{{ $page->admin->name ?? $page['admin'] }}</span>
		</a>

	@if(@count($page->menu))
		<ul class="menu__links">

		@foreach($page->menu as $link)
            @php

            @endphp
			@include('admin.inc.menu')

		@if(property_exists($link, 'drop'))
			<ul class="menu__drop{{ $link->route == "{$page->route}.index" || array_key_exists(str_replace('admin.', '', $page->route), $link->drop) ? ' menu__drop_open' : '' }}">
			@foreach($link->drop as $drop)
				@include('admin.inc.menu', [ 'link' => $drop ])
			@endforeach
			</ul>
		@endif

		@endforeach

		</ul>
	@endif

	</div>

@endisset


<style type="text/css">
	.menu__link{
		color: #75BFFF!important;
	}
</style>