@php
    $seoTitle = 'Thư viện Ảnh hoạt động - Viện GREECO';
    $seoDescription = 'Hình ảnh các sự kiện hội thảo, hoạt động nghiên cứu thực địa và các khóa đào tạo chuyên sâu của Viện GREECO.';
    $canonical = route('gallery');
    
    $breadcrumbs = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Thư viện ảnh', 'url' => route('gallery')]
    ];
@endphp

@extends('layouts.app')

@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />

    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <section id="subheader" class="relative">
            <div class="container relative z-index-1000">
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="crumb">
                            <li><a href="{{ route('home') }}">Trang chủ</a></li>
                            <li class="active">Gallery</li>
                        </ul>
                        <h1 class="text-uppercase">Gallery</h1>
                        <p class="col-lg-10 lead">Hình ảnh hoạt động nghiên cứu khoa học, chuyển giao công nghệ và hội thảo của Viện GREECO.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative">
            <div class="container">
                <div class="row g-4">
                    @foreach($galleries as $gallery)
                        <div class="col-lg-4 text-center">
                            <a href="{{ Storage::url($gallery->image) }}" class="image-popup d-block hover">
                                <div class="relative overflow-hidden rounded-10">
                                    <div class="absolute start-0 w-100 abs-middle fs-36 text-white text-center z-2">
                                        <h4 class="mb-0 hover-scale-in-3">View</h4>
                                    </div> 
                                    <div class="absolute w-100 h-100 top-0 bg-dark hover-op-05"></div>
                                    <img src="{{ Storage::url($gallery->image) }}" width="1024" height="1024" class="img-fluid hover-scale-1-2" alt="{{ $gallery->title ?? 'Thư viện ảnh Greeco' }}" loading="lazy" decoding="async">
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection
