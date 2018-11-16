@extends('hanoivip::layouts.app-test')

@section('title', 'Chi tiết hoạt động trong nhóm')

@section('content')

@if (!empty($activities))
	@foreach ($activities as $role => $roleActivities)
		Nhân vật: {{ !empty($role) ? $role : '(mặc định)' }} </br>
		@foreach ($roleActivities as $type => $act)
		
			Hoạt đông: {{ $type }}, 
			Trạng thái: {{ print_r($act) }}
			<br/>
		@endforeach
	@endforeach
@else
	<p>Không có hoạt động nào trong nhóm</p>
@endif

@endsection
