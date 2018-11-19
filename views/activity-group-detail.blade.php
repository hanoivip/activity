@extends('hanoivip::layouts.app-test')

@section('title', 'Chi tiết hoạt động trong nhóm')

@section('content')

@if (!empty($activities))
	@foreach ($activities as $role => $roleActivities)
		Nhân vật: {{ !empty($role) ? $role : '(mặc định)' }} </br>
		
		@foreach ($roleActivities as $aid => $act)
			
			Tiêu đề: {{ $title = $configs[$aid]['title'] }},<br/>
			Mô tả: {{ $title = $configs[$aid]['description'] }},<br/>
			@if ($configs[$aid]['end'] > 0)
			Bắt đầu: {{ $title = $configs[$aid]['start'] }}
			Kết thúc: {{ $title = $configs[$aid]['end'] }}
			@endif

			@foreach ($act[0] as $amount => $index)
				<br/>
				Mốc: {{ $amount }}
				</br>
				Phần thưởng:
				@foreach ($configs[$aid]['params'][$amount] as $reward)
						@if ($reward['type'] == 'Balance')
                    		Xu hoặc vàng
                    	@endif
                    	@if ($reward['type'] == 'Items')
                    		Vật phẩm: {{ $reward['id'] }}                    		
                    	@endif
                    	Số lượng: {{ $reward['count'] }}
				@endforeach
				<br/>
				@if ($index->received)
					<p>Đã nhận!</p>
				@elseif ($index->canReceived)
					
                	<form method="post" action="{{ route('activity.reward') }}">
                		{{ csrf_field() }}
                		<input type="hidden" id="group" name="group" value="{{ $group }}"/>
                		<input type="hidden" id="type" name="type" value="{{ $configs[$aid]['type'] }}"/>
                		<input type="hidden" id="index" name="index" value="{{ $index->amountOrIndex }}"/>
                		<input type="hidden" id="role" name="role" value="{{$role}}"/>
                		<button type="submit">Nhận thưởng</button>
                	</form>
                @else
                	<a href="">Chưa nhận được</a>
                @endif
			@endforeach

			<br/><br/>
		@endforeach
		<br/><br/>
	@endforeach
@else
	<p>Không có hoạt động nào trong nhóm</p>
@endif

<a href="{{route('activity.group')}}">Quay lại</a>

@endsection
