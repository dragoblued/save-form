<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="admin">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#9c7a52">
	<meta name="msapplication-navbutton-color" content="#9c7a52">
	<meta name="apple-mobile-web-app-status-bar-style" content="#9c7a52">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>{{ $page->title }}</title>
	<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

</head>
<body>
	@include('admin.inc._header')
	@include('admin.inc.menu')

	<section class="content">
		@include('admin.inc.content-header')

		@yield('content')
	</section>

	@include('admin.inc._footer')

	<script src="{{ asset('js/libs/jquery.min.js') }}"></script>
	<script src="{{ asset('js/libs/fontawesome.min.js') }}"></script>
	<script src="{{ asset('js/libs/jodit.min.js') }}"></script>
	<script src="{{ asset('js/libs/formstyler.min.js') }}"></script>
	<script src="{{ asset('js/libs/jquery.fancybox.min.js') }}"></script>
	<script src="{{ asset('js/common.js') }}"></script>
	<script src="{{ asset('js/admin.js') }}"></script> 

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>
