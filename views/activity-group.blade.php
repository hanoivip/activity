@extends('hanoivip::layouts.app')

@section('title', 'Nhóm hoạt động')

@section('content')

@if (!empty($groups))
	@foreach ($groups as $group)
		<a href="{{ route('activity.detail', ['group' => $group]) }}">Nhóm hoạt động {{$group}}</a>
		</br>
	@endforeach
@else
	<p>Hệ thống không có bất cứ hoạt động nào</p>
@endif

@endsection
