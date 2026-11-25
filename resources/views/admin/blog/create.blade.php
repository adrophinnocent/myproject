@extends('admin.layouts.app')

@section('title', 'New Story')
@section('page-title', 'New Story')

@section('content')
@include('admin.blog.partials.editor', [
    'editorTitle' => 'New Story',
    'editorSubtitle' => 'Create and publish a story for the Twina Safaris Journal.',
    'editorAction' => route('admin.blog.store'),
])
@endsection
