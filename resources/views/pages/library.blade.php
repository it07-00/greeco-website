@php
    $seoTitle = __('Văn bản pháp luật Môi trường & Khí hậu - GREECO');
    $seoDescription = __('Hệ thống văn bản pháp luật về môi trường, biến đổi khí hậu và kiểm kê khí nhà kính tại Việt Nam.');
    $canonical = website_route('library');

    $breadcrumbs = [
        ['name' => __('Trang chủ'), 'url' => website_route('home')],
        ['name' => __('Văn bản pháp luật'), 'url' => website_route('library')]
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
                            <li><a href="{{ website_route('home') }}">{{ __('Trang chủ') }}</a></li>
                            <li class="active">{{ __('Văn bản pháp luật') }}</li>
                        </ul>
                        <h1 class="text-uppercase">{{ __('Văn bản pháp luật') }}</h1>
                        <p class="col-lg-10 lead">{{ __('Hệ thống cơ sở dữ liệu pháp luật về môi trường, biến đổi khí hậu và kiểm kê khí nhà kính.') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-light pb80">
            <div class="container">
                <div class="row g-4 mb-4 justify-content-center">
                    <div class="col-lg-8 text-center">
                        <div class="subtitle wow fadeInUp mb-3">{{ __('Cơ sở pháp lý') }}</div>
                        <h2 class="text-uppercase wow fadeInUp" data-wow-delay=".2s">{{ __('Văn bản Pháp luật') }} <span
                                class="id-color-2">{{ __('Môi trường & Khí hậu') }}</span></h2>
                        <p class="wow fadeInUp">{{ __('Hệ thống hóa các văn bản quy phạm pháp luật, quyết định và thông tư quan trọng của Nhà nước điều chỉnh các hoạt động bảo vệ môi trường, biến đổi khí hậu và kiểm kê khí nhà kính tại Việt Nam.') }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive bg-white p-4 rounded-1 shadow-sm">
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="table-col-20">{{ __('Số hiệu / Ký hiệu') }}</th>
                                        <th scope="col" class="table-col-60">{{ __('Trích yếu nội dung') }}</th>
                                        <th scope="col" class="table-col-20 text-center">{{ __('Tải về / Tra cứu') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $doc)
                                        @php
                                            $badgeClass = match($doc->type) {
                                                __('Luật Quốc hội') => 'bg-success text-white',
                                                __('Nghị định Chính phủ') => 'bg-primary text-white',
                                                __('Quyết định Thủ tướng') => 'bg-warning text-dark',
                                                __('Thông tư Bộ TN&MT') => 'bg-secondary text-white',
                                                __('Khung Tiêu chuẩn Quốc tế') => 'bg-info text-dark',
                                                default => 'bg-dark text-white'
                                            };

                                            $link = trim($doc->link ?? '');
                                            $linkUrl = $link === '' ? null : (
                                                str_starts_with($link, 'http') || str_starts_with($link, '/')
                                                    ? $link
                                                    : url($link)
                                            );
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $doc->number }}</strong></td>
                                            <td>
                                                <span class="badge {{ $badgeClass }} mb-2">{{ $doc->type }}</span>
                                                <h5 class="mb-1 text-dark">{{ $doc->title }}</h5>
                                                @if($doc->description)
                                                    <p class="mb-0 text-muted fs-14">{{ $doc->description }}</p>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($linkUrl !== null)
                                                    <a href="{{ $linkUrl }}" target="_blank"
                                                        class="btn-main btn-line py-2 px-3 fs-13">
                                                        @if(str_contains($linkUrl, 'lien-he') || str_contains($linkUrl, 'dich-vu'))
                                                            <i class="fa-solid fa-circle-info me-1"></i>{{ __('Liên hệ Tư vấn') }}
                                                        @else
                                                            <i class="fa fa-external-link me-1"></i>{{ __('Xem chi tiết') }}
                                                        @endif
                                                    </a>
                                                @else
                                                    <span class="text-muted fs-13">{{ __('Chưa có liên kết') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
