@extends('layouts.email')
@section('content')
<h2>Обратная связь</h2>
<table cellspacing="0" cellpadding="5" border="1">
	<tr>
		<td>Имя</td>
		<td>{{ $feedback->name }}</td>
	</tr>
	<tr>
		<td>Телефон</td>
		<td><b>{{ $feedback->phone }}</b></td>
	</tr>
	<tr>
		<td>E-mail</td>
		<td>{{ $feedback->email }}</td>
	</tr>
	<tr>
		<td>Message</td>
		<td>{{ $feedback->message }}</td>
	</tr>
</table>
@endsection