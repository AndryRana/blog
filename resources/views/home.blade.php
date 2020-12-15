@extends('layouts.app')

@section('content')
<div>
    @if (Auth::check())
        <mainpage-component :user="{{ Auth::user() }}"></mainpage-component>
    @else
        <mainpage-component :user="false"></mainpage-component>
    @endif
</div>
@endsection