<div class="files js-files__list{{ $line->multiple && isset($item) ? ' js-files__list_sort files_sort' : '' }}" data-field="{{ $key }}">
@isset($item)
	@foreach($line->items as $i => $file)
		<div class="files__item" data-index="{{ $i }}">

		@if($file->image)
			<a href="{{ asset($file->src) }}" class="files__link" data-fancybox>
				<img src="{{ asset($file->src) }}?{{ rand(0, 100) }}" alt="" class="files__preview">
			</a>
		@else
			<i class="fa-file far files__ico"></i>
		@endif

		@if($file->mime)
			<div class="files__mime">{{ $file->mime }}</div>
		@endif

		@if($line->multiple && isset($item))
			<div class="files__move">
				<i class="fas fa-ellipsis-h"></i>
			</div>
			<div class="files__move-state files__move-state_disable">Сохранение...</div>
		@endif

			{{-- <div class="files__remove js-files__remove">
				<i class="fa fa-ban"></i>
			</div> --}}
			<div class="files__removing files__removing_disable">
				<div class="files__action-text">Удаляется...</div>
			</div>
		</div>
	@endforeach
@endisset

</div>
