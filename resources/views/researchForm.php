@extends('layouts.default')

@section('title', 'LoL Trend Research')

@section('menuItem1', 'When buy')
@section('menuItem2', 'When killed')
@section('menuItem3', 'Where lane')
@section('menuItem4', 'How many CS')

@section('content')
    <h1>Write a New Article</h1>

    <hr/>

    {!! Form::open() !!}
        <div class="form-group">
            {!! Form::label('title', 'Title:') !!}
            {!! Form::text('title', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('body', 'Body:') !!}
            {!! Form::textarea('body', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('published_at', 'Publish On:') !!}
            {!! Form::input('date', 'published_at', date('Y-m-d'), ['class' => 'form-control']) !!}
        </div>    
        <div class="form-group">
            {!! Form::submit('Add Article', ['class' => 'btn btn-primary form-control']) !!}
        </div>
    {!! Form::close() !!}
@endsection