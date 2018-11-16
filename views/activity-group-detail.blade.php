@extends('hanoivip::layouts.app-test')

@section('title', 'Chi tiết hoạt động trong nhóm')

@section('content')

@if (!empty($activities))
	@foreach ($activities as $role => $roleActivities)
		Nhân vật: {{ !empty($role) ? $role : '(mặc định)' }} </br>
		@foreach ($roleActivities as $aid => $act)
			
			Hoạt đông: {{ $type = $configs[$aid]['type'] }},
			@if ($type=='first_recharge')
				Trạng thái:
				{{ print_r($act[0][0]) }}
				@if ($act[0][0]->canReceived)
                	<form method="post" action="{{ route('activity.reward') }}">
                		{{ csrf_field() }}
                		<input type="hidden" id="group" name="group" value="{{ $group }}"/>
                		<input type="hidden" id="type" name="type" value="{{ $type }}"/>
                		<input type="hidden" id="index" name="index" value="0"/>
                		<button type="submit">Nhận thưởng</button>
                	</form>
                @else
                	<a href="">Chưa nhận được</a>
                @endif
			@endif
			@if ($type=='recharge')
				@foreach ($act[0] as $amount => $index)
					Mốc {{ $amount }} : Trạng thái: {{ print_r($index) }}
					@if ($index->canReceived)
                    	<form method="post" action="{{ route('activity.reward') }}">
                    		{{ csrf_field() }}
                    		<input type="hidden" id="group" name="group" value="{{ $group }}"/>
                    		<input type="hidden" id="type" name="type" value="{{ $type }}"/>
                    		<input type="hidden" id="index" name="index" value="{{ $index->amountOrIndex }}"/>
                    		<button type="submit">Nhận thưởng</button>
                    	</form>
                    @else
                    	<a href="">Chưa nhận được</a>
                    @endif
				@endforeach
			@endif
			<br/><br/>
		@endforeach
		<br/><br/>
	@endforeach
@else
	<p>Không có hoạt động nào trong nhóm</p>
@endif

@endsection
