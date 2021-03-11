@extends('layouts.publicapp')

@section('title', trans('lang.login').' '.'-')

@section('content')

    <login-form copyright_text="{{ $copyright_text }}"></login-form>

@endsection