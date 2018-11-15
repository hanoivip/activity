@extends('hanoivip::layouts.web')

@section('title', 'Chi tiết hoạt động trong nhóm')

@section('content')

@if (!empty($activities))
	@foreach ($activities as $role => $roleActivities)
		Nhân vật: {{ !empty($role) ? $role : '(mặc định)' }} </br>
		@foreach ($roleActivities as $id => $act)
			Cấu hình: {{ print_r($configs[$id]) }}</br>
			Trạng thái: {{ print_r($act) }}
			@if ($act->canReceived)
				<form method="post" action="{{route('activity.reward')}}">
					{{ csrf_token() }}
					<input type="hidden" id="group" name="group" value="" />
					<input type="hidden" id="type" name="group" value="{{$configs[$id]['type']}}" />
					<input type="hidden" id="index" name="group" value="{{$act->amountOrIndex}}" />
					<button type="submit">Nhận thưởng</button>
				</form>
			@else
				<a href="">Nhận thưởng</a>
			@endif
		@endforeach
	@endforeach
@else
	<p>Không có hoạt động nào trong nhóm</p>
@endif

@endsection
