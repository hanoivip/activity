@extends('hanoivip::layouts.web')

@section('title', 'Chi tiết hoạt động trong nhóm')

@section('content')

@if (!empty($activities))
	@foreach ($activities as $role => $roleActivities)
		Nhân vật: {{ !empty($role) ? $role : '(mặc định)' }} </br>
		@foreach ($roleActivities as $id => $act)
			Cấu hình: {{ print_r($configs[$id]) }}</br>
			Trạng thái: {{ print_r($act) }}
		@endforeach
	@endforeach
@else
	<p>Không có hoạt động nào trong nhóm</p>
@endif

@endsection
