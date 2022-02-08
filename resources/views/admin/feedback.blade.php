@if(!isset($item))
	<td>Site</td>
	<td>Name</td>
	<td>Telephone</td>
	<td>Email</td>
	<td>Message</td>
	<td>Date</td>
@else
	<td>{{ $item->site }}</td>
	<td>{{ $item->name }}</td>
	<td>{{ $item->phone }}</td>
	<td>{{ $item->email }}</td>
	<td>{{ $item->message }}</td>
	<td>{{ $item->created_at->format('d.m.Y H:i:s') }}</td>
@endif
