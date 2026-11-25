@extends('admin.layouts.app')

@section('title', 'Edit Story')
@section('page-title', 'Edit Story: ' . $blog->title)

@section('content')
@include('admin.blog.partials.editor', [
    'editorTitle' => 'Edit Story',
    'editorSubtitle' => 'Update story content, cover photo, SEO or publication status.',
    'editorAction' => route('admin.blog.update', $blog),
])
@endsection
