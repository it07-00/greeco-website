@php
    $seoTitle = 'Dự án & Nghiên cứu Khoa học - Viện GREECO';
    $seoDescription = 'Các dự án ứng dụng và chuyển giao công nghệ xanh tiêu biểu của Viện GREECO.';
    $canonical = route('projects.index');
    
    $breadcrumbs = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Dự án & Nghiên cứu', 'url' => route('projects.index')]
    ];
@endphp

@extends('layouts.app')

@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />

    <div class="no-bottom no-top" id="content">
        <div id="top"></div>
        
        <section class="p-0 relative">
            <div class="container-fluid relative z-2">
                <div class="row g-0">
                    <div class="col-lg-12">
                        <div class="owl-custom-nav menu-float" data-target="#gallery-carousel">
                            <a class="btn-next"></a>
                            <a class="btn-prev"></a>                                

                            <div id="gallery-carousel" class="owl-2-cols-no-margin owl-carousel owl-theme">
                                @if($projects->isNotEmpty())
                                    @foreach($projects as $project)
                                        @php
                                            $projectImg = $project->thumbnail ? asset('storage/' . $project->thumbnail) : asset('assets/images/projects-square/' . (($loop->index % 5) + 1) . '.jpg');
                                        @endphp
                                        <!-- project item begin -->
                                        <div class="relative">
                                            <div class="hover project-card-custom overflow-hidden text-light">
                                                <a href="{{ route('projects.show', $project) }}" class="abs w-100 h-100 z-5"></a>
                                                <img src="{{ $projectImg }}" class="hover-scale-1-1 w-100" alt="{{ $project->title }}">
                                                <div class="abs bottom-0 mb-4 w-100 text-center px-4 hover-op-1 z-4 hover-mt-40">
                                                    <div class="mb-3">{{ Str::limit($project->excerpt, 120) }}</div>
                                                </div>
                                                <div class="abs de-gradient-edge-bottom dark z-2 bottom-0 hover-op-1"></div>
                                                <div class="abs abs-centered text-center z-2 hover-op-0">
                                                    <div class="p-4">
                                                        <h4 class="mb-1">{{ $project->title }}</h4>
                                                        <span>{{ $project->location ?: 'GREECO' }}</span>
                                                    </div>
                                                </div>
                                                <div class="gradient-trans-dark-top abs w-100 h-50 top-0 op-6"></div>
                                                <div class="w-40px abs end-0 bottom-0 m-4">
                                                    <img src="{{ asset('assets/images/misc/right-arrow.webp') }}" class="w-100" alt="Right arrow">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- project item end -->
                                    @endforeach
                                @else
                                    <div class="relative py-5 text-center bg-dark text-white">
                                        <p class="mb-0 py-5">Đang cập nhật danh sách dự án...</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    </div>
@endsection
