@extends('layouts.admin')
@section('content')

<!-- Ошибки валидации -->
@if($errors->all())
	<div class="errors">

	@foreach($errors->all() as $error)
		<div class="errors__item">
			{!! preg_replace('"The( selected)? (.*?) (field|is|may|must|does|has)(.*)?"', 'The$1 <b>$2</b> $3$4', $error) !!}
		</div>
	@endforeach

	</div>
@endif

<!-- Открытие формы -->
@if(!isset($item))
	{{ Form::model(null, [
		'route' => "{$page->route}.store",
		'files' => true,
		'class' => 'form'
	]) }}
@else
	{{ Form::model($item, [
		'route' => [ "{$page->route}.update", $item->id ],
		'files' => true,
		'class' => 'form'
	]) }}
	{{ method_field('PUT') }}
@endif

@if(isset($page->customForm))
	@include('admin.custom.'.$page->name)
@endif
<!-- Формирование полей -->
<div class="content__row">

@foreach ($form as $key => $line)
	<div class="content__col content__col_{{ $line->class }}">

		{!!
			property_exists($line, 'line')
			? '<div class="form__line">'
			: '<label class="form__line">'
		!!}

			<span class="form__signature{{ $line->required ? ' form__signature_required' : '' }}">
				{{ $line->signature }}
			</span>

			@if($line->type == 'text')
				{{ Form::text($key) }}
			@elseif($line->type == 'password')
				{{ Form::password($key) }}
			@elseif($line->type == 'email')
				{{ Form::email($key) }}
			@elseif($line->type == 'number')
				{{ Form::number(
					$key,
					$item[$key] ?? 0,
					[ 'step' => 'any' ]
				) }}
			@elseif($line->type == 'time')
				{{ Form::text($key, !isset($item) ? '00:00' : $item[$key]) }}
			@elseif($line->type == 'timestamp')
				{{ Form::text($key, !isset($item) ? date('Y-m-d H').':00:00' : $item[$key], [ 'class' => 'timestamp' ]) }}
			@elseif($line->type == 'textarea')
				{{ Form::textarea($key) }}
			@elseif($line->type == 'wysiwyg')
				{{ Form::textarea(
					$key,
					$item[$key] ?? null,
					[
						'class' => 'wysiwyg js-wysiwyg',
						'data-uploader' => route(
							'admin.uploader',
							[
								'folder' => $line->media
							]
						),
						'data-filebrowser' => route(
							'admin.browser',
							[
								'folder' => $line->media
							]
						),
					]
				) }}
			@elseif($line->type == 'select')
				{{ Form::select($key, $line->items, $item[$key] ?? null, [
					'class' => 'js-select',
					'data-search' => 'true'
				]) }}
			@elseif($line->type == 'checkbox')

				@if(!property_exists($line, 'items'))
					{{ Form::checkbox($key) }}
				@else
					@foreach($line->items as $val => $option)
						<label>
							{{ Form::checkbox("{$key}[]", $val) }}
							{{ $option }}
						</label><br>
					@endforeach
				@endif

			@elseif($line->type == 'include')
				@include("{$page->route}.{$key}")
			@elseif($line->type == 'files')
				{{ Form::file($line->multiple ? "{$key}[]" : $key, [
					'accept'   => $line->mimes,
					'class'    => 'js-files__input',
					'multiple' => $line->multiple
				]) }}
				@include('admin._files')
			@endif

		{!!
			property_exists($line, 'line')
			? "</div>"
			: "</label>"
		!!}

	</div>
@endforeach

</div>

<!-- Кнопка отправки формы -->
<div class="form__submit">
	@if(!isset($item))
		{{ Form::submit('Создать', [ 'class' => 'button' ]) }}
	@else
		{{ Form::submit('Сохранить', [ 'class' => 'button' ]) }}
	@endif
</div>

<!-- Закрытие формы -->
{{ Form::close() }}

@endsection
