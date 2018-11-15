@extends('hanoivip::layouts.app')

@section('title', 'Nhận thưởng từ hoạt động')

@section('content')

@if (empty($error))
<p>Lỗi: {{ $error }}</p>
@endif

@if ($result)
<p>{{ __('activity.reward.success') }}</p>
@else
<p>{{ __('activity.reward.fail') }}</p>
@endif

@endsection