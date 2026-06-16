@php
  $seoTitle = $page->meta_title ?: $page->title . ' | ' . config('app.name');
  $seoDescription = $page->meta_description ?: $page->excerpt;
  $canonical = $page->canonical_url ?: route('about');
  $ogImage = $page->og_image
    ? asset('storage/' . $page->og_image)
    : asset('assets/images/og-default.jpg');
  $indexable = $page->is_indexable;

  $breadcrumbs = [
    ['name' => 'Trang chủ', 'url' => route('home')],
    ['name' => 'Giới thiệu', 'url' => route('about')]
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
              <li class="active">Giới thiệu</li>
            </ul>
            <h1 class="text-uppercase">Giới thiệu</h1>
            <p class="col-lg-10 lead">
              Kiến tạo không gian xanh hướng tới tương lai bền vững!
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="pb-60 bg-white">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="de_tab tab_simple">
              <ul class="de_nav text-center mb-5 justify-content-center">
                <li class="active"><span>Hành trình phát triển</span></li>
                <li><span>Hội đồng khoa học</span></li>
                <li><span>Sơ đồ bộ máy</span></li>
                <li><span>Sứ mệnh và hành động</span></li>
              </ul>

              <div class="de_tab_content">
                <!-- Tab 1: Hành trình phát triển -->
                <div>
                  <div class="row g-4 gx-5 align-items-center">
                    <div class="col-lg-6">
                      <div class="subtitle wow fadeInUp mb-3">
                        {{ setting('journey_subtitle', 'Hành trình phát triển') }}
                      </div>
                      <h2 class="text-uppercase wow fadeInUp" data-wow-delay=".2s">
                        {{ setting('journey_title_1', 'Kiến tạo Giá trị Kinh tế Xanh') }}
                        <span class="id-color-2">{{ setting('journey_title_2', 'từ năm 2019') }}</span>
                      </h2>
                      <div class="wow fadeInUp">
                        {!! $page->content !!}
                      </div>
                      <a class="btn-main btn-line wow fadeInUp" href="{{ route('projects.index') }}"
                        data-wow-delay=".6s">Dự án tiêu biểu</a>
                    </div>

                    <div class="col-lg-6">
                      <div class="row g-4">
                        <div class="col-sm-6">
                          <div class="row g-4">
                            <div class="col-lg-12">
                              <img src="{{ asset('assets/images/misc/3.webp') }}" width="1024" height="1024" class="w-100 rounded-1 wow zoomIn about-journey-img"
                                alt="GREECO project development" loading="lazy" decoding="async" />
                            </div>
                            <div class="col-lg-12">
                              <div class="rounded-1 relative bg-color-2 text-light p-4">
                                <img
                                  src="{{ setting('icon_journey_tree') ? asset('storage/' . setting('icon_journey_tree')) : asset('assets/images/icons/tree.png') }}"
                                  width="512" height="512" class="abs abs-middle w-60px" alt="icon tree" loading="lazy" decoding="async" />
                                <div class="de_count ps-80 wow fadeInUp">
                                  <h2 class="mb-0 fs-32">
                                    <span class="timer" data-to="{{ setting('journey_box2_count', '550') }}" data-speed="3000"></span>+
                                  </h2>
                                  <span class="op-7">{{ setting('about_box1_desc', 'Dự án hoàn thành') }}</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="row g-4">
                            <div class="col-lg-12">
                              <div class="rounded-1 relative bg-color-2 text-light p-4">
                                <img
                                  src="{{ setting('icon_journey_happy') ? asset('storage/' . setting('icon_journey_happy')) : asset('assets/images/icons/happy.png') }}"
                                  width="512" height="512" class="abs abs-middle w-60px" alt="icon partner" loading="lazy" decoding="async" />
                                <div class="de_count ps-80 wow fadeInUp">
                                  <h2 class="mb-0 fs-32">
                                    <span class="timer" data-to="{{ setting('journey_box3_count', '850') }}" data-speed="3000"></span>+
                                  </h2>
                                  <span class="op-7">{{ setting('about_box2_desc', 'Đối tác tin cậy') }}</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-12">
                              <img src="{{ asset('assets/images/misc/4.webp') }}" width="1024" height="1024" class="w-100 rounded-1 wow zoomIn about-journey-img"
                                alt="GREECO partner network" loading="lazy" decoding="async" />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Tab 2: Hội đồng khoa học -->
                <div>
                  <div class="row g-4 mb-3 justify-content-center">
                    <div class="col-lg-6 text-center">
                      <div class="subtitle mb-3">Hội đồng khoa học</div>
                      <h2 class="text-uppercase">
                        Ban lãnh đạo & <span class="id-color-2">Chuyên gia</span>
                      </h2>
                    </div>
                  </div>

                  <div class="row g-4">
                    <div class="col-lg-12">
                      <div class="relative">
                        <div class="owl-custom-nav menu-float" data-target="#project-single-carousel">
                          <a class="btn-next"></a>
                          <a class="btn-prev"></a>

                          <div id="project-single-carousel" class="owl-3-cols owl-carousel owl-theme">
                            @foreach($teamMembers as $member)
                            <!-- team begin -->
                            <div class="item">
                              <div class="relative team-member-card">
                                <div class="abs bottom-0 w-100">
                                  <div
                                    class="d-flex justify-content-between align-items-center rounded-1 m-4 bg-white p-4">
                                    <div>
                                      <h4 class="mb-0">{{ $member->name }}</h4>
                                      <p class="mb-0">{{ $member->role }}</p>
                                    </div>
                                    <div class="text-end">
                                      <a
                                        href="{{ $member->facebook_url ?? setting('facebook_url', 'https://www.facebook.com/greecoofficial?locale=vi_VN') }}"><i
                                          class="fa-brands fa-facebook-f fs-24 id-color bg-light w-40px h-40px pt-2 circle text-center"></i></a>
                                    </div>
                                  </div>
                                </div>
                                <div class="team-member-img-wrap rounded-10px overflow-hidden">
                                  <img src="{{ $member->image ? asset(str_starts_with($member->image, 'assets') ? $member->image : 'storage/' . $member->image) : asset('assets/images/team/1.webp') }}" width="500" height="600" class="w-100 h-100 object-fit-cover"
                                    alt="{{ $member->name }}" loading="lazy" decoding="async" />
                                </div>
                              </div>
                            </div>
                            <!-- team end -->
                            @endforeach
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Tab 3: Sơ đồ bộ máy -->
                <div>
                  <div class="row g-4 mb-5 justify-content-center">
                    <div class="col-lg-6 text-center">
                      <div class="subtitle mb-3">Sơ đồ bộ máy</div>
                      <h2 class="text-uppercase">
                        Cơ cấu <span class="id-color-2">Tổ chức</span>
                      </h2>
                    </div>
                  </div>

                  <div class="org-chart-wrapper">

                    <div class="org-container">
                      <!-- Top row: Left Advisories - Center (Council & Director) - Right Advisories -->
                      <div class="org-top-row">
                        <!-- Left side: Hội đồng khoa học & Bộ phận Pháp chế -->
                        <div class="org-advisory-col">
                          <div class="org-card card-blue w-100">
                            <h5 class="mb-0 fs-14 fw-bold">
                              <i class="org-icon-blue fa fa-flask me-2"></i>Hội đồng Khoa học
                            </h5>
                          </div>
                          <div class="org-card card-blue w-100">
                            <h5 class="mb-0 fs-14 fw-bold">
                              <i class="org-icon-blue fa fa-balance-scale me-2"></i>Bộ phận Pháp chế
                            </h5>
                          </div>
                        </div>

                        <!-- Center: Hội đồng Viện -> Viện Trưởng -->
                        <div class="org-center-col">
                          <!-- Hội đồng Viện -->
                          <div class="org-card org-card-council card-orange">
                            <h4 class="mb-1 text-uppercase fs-16">
                              <i class="org-icon-orange fa-solid fa-sitemap me-2"></i>Hội đồng Viện
                            </h4>
                            <p class="mb-0 fs-12 text-muted fw-bold">
                              (Viện trưởng và các Phó Viện trưởng)
                            </p>
                          </div>

                          <div class="connector-v-line"></div>

                          <!-- Viện Trưởng -->
                          <div class="org-card org-card-director card-orange">
                            <h4 class="org-text-orange-dark mb-1 text-uppercase fs-15">
                              <i class="org-text-orange-dark fa-solid fa-user-tie me-2"></i>Viện Trưởng
                            </h4>
                            <h5 class="mb-0 fs-15 text-dark fw-bold">
                              TS. NGUYỄN THỊ THU HÀ
                            </h5>
                          </div>
                        </div>

                        <!-- Right side: Hội đồng Chuyên gia -->
                        <div class="org-advisory-col">
                          <div class="org-card card-blue w-100">
                            <h5 class="mb-0 fs-14 fw-bold">
                              <i class="org-icon-blue fa fa-users me-2"></i>Hội đồng Chuyên gia
                            </h5>
                          </div>
                          <div class="org-spacer-lg d-none d-lg-block"></div>
                          <!-- Spacer -->
                        </div>
                      </div>

                      <div class="connector-v-line"></div>

                      <!-- Bottom Row: Split into 2 branches under Phó Viện Trưởngs -->
                      <div class="org-bottom-row">
                        <!-- Left Branch (Phó Viện Trưởng 1) -->
                        <div class="org-branch-col">
                          <div class="org-card card-yellow w-100">
                            <h4 class="mb-0 text-uppercase fs-14">
                              <i class="org-icon-amber fa-solid fa-user me-2"></i>Phó Viện Trưởng
                            </h4>
                          </div>

                          <div class="connector-v-line"></div>

                          <div class="org-dept-list">
                            <!-- Văn phòng Viện & Phòng Đào tạo (Green boxes) -->
                            <div class="org-card card-green w-100">
                              <h5 class="mb-0 fs-14 fw-bold">
                                <i class="org-icon-green fa fa-building me-2"></i>Văn phòng Viện
                              </h5>
                            </div>
                            <div class="org-card card-green w-100">
                              <h5 class="mb-0 fs-14 fw-bold">
                                <i class="org-icon-green fa fa-graduation-cap me-2"></i>Phòng Đào tạo
                              </h5>
                            </div>
                            <!-- Phòng NC KH & CGCN (Blue box) -->
                            <div class="org-card org-card-research card-blue w-100">
                              <h5 class="org-research-title mb-0 fs-14 fw-bold">
                                <i class="org-icon-blue-strong fa fa-microscope me-2"></i>Phòng Nghiên cứu Khoa học<br />&
                                Chuyển giao Công nghệ
                              </h5>
                            </div>
                          </div>
                        </div>

                        <!-- Right Branch (Phó Viện Trưởng 2) -->
                        <div class="org-branch-col">
                          <div class="org-card card-yellow w-100">
                            <h4 class="mb-0 text-uppercase fs-14">
                              <i class="org-icon-amber fa-solid fa-user me-2"></i>Phó Viện Trưởng
                            </h4>
                          </div>

                          <div class="connector-v-line"></div>

                          <div class="org-dept-list">
                            <!-- Phòng Dự án (Blue box) -->
                            <div class="org-card card-blue w-100">
                              <h5 class="mb-0 fs-14 fw-bold">
                                <i class="org-icon-blue fa fa-project-diagram me-2"></i>Phòng Dự án
                              </h5>
                            </div>
                            <!-- Phòng Truyền thông (Orange box) -->
                            <div class="org-card org-card-communications card-orange">
                              <h5 class="org-text-orange-dark mb-0 fs-14 fw-bold">
                                <i class="org-icon-orange-strong fa fa-bullhorn me-2"></i>Phòng Truyền thông
                              </h5>
                            </div>
                            <!-- Phòng Đối ngoại & Hợp tác Quốc tế (Orange box) -->
                            <div class="org-card org-card-international card-orange">
                              <h5 class="org-text-orange-dark mb-0 fs-14 fw-bold">
                                <i class="org-icon-orange-dark fa fa-globe me-2"></i>Phòng Đối ngoại và Hợp tác Quốc tế
                              </h5>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Tab 4: Sứ mệnh và hành động -->
                <div>
                  <div class="bg-color-mint p-5 rounded-3 shadow-soft">
                    <div class="row g-4 mb-4 justify-content-center text-center">
                      <div class="col-lg-8">
                        <h2 class="text-uppercase id-color mb-0">Chức năng & Nhiệm vụ</h2>
                      </div>
                    </div>

                    <div class="row g-4">
                      <!-- Left Column: Chức năng -->
                      <div class="col-lg-5">
                        <div class="pe-lg-3">
                          <div class="d-flex align-items-center mb-4">
                            <div
                              class="about-section-icon icon-wrap-mint rounded-circle me-3 d-flex align-items-center justify-content-center">
                              <i class="fa-solid fa-gears fs-20"></i>
                            </div>
                            <h3 class="mb-0 fs-24 fw-600 text-uppercase id-color-2">Chức năng của Viện</h3>
                          </div>

                          <div class="accordion accordion-flush custom-accordion" id="accordionFunctions">
                            <!-- Accordion Item a -->
                            <div class="accordion-item bg-transparent border-0 mb-3">
                              <h2 class="accordion-header" id="headingFuncA">
                                <button
                                  class="about-accordion-button accordion-button collapsed rounded-3 fw-bold id-color-2"
                                  type="button" data-bs-toggle="collapse" data-bs-target="#collapseFuncA"
                                  aria-expanded="false" aria-controls="collapseFuncA">
                                  a) Nghiên cứu và Phát triển
                                </button>
                              </h2>
                              <div id="collapseFuncA" class="accordion-collapse collapse" aria-labelledby="headingFuncA"
                                data-bs-parent="#accordionFunctions">
                                <div class="accordion-body pt-3 pb-2 px-3">
                                  Nghiên cứu cơ bản và ứng dụng, triển khai các đề tài, dự án phát triển công nghệ trong
                                  lĩnh vực Kỹ thuật Hóa học và Kỹ thuật Môi trường.
                                </div>
                              </div>
                            </div>

                            <!-- Accordion Item b -->
                            <div class="accordion-item bg-transparent border-0 mb-3">
                              <h2 class="accordion-header" id="headingFuncB">
                                <button
                                  class="about-accordion-button accordion-button collapsed rounded-3 fw-bold id-color-2"
                                  type="button" data-bs-toggle="collapse" data-bs-target="#collapseFuncB"
                                  aria-expanded="false" aria-controls="collapseFuncB">
                                  b) Sản xuất và Thực nghiệm
                                </button>
                              </h2>
                              <div id="collapseFuncB" class="accordion-collapse collapse" aria-labelledby="headingFuncB"
                                data-bs-parent="#accordionFunctions">
                                <div class="accordion-body pt-3 pb-2 px-3">
                                  Tổ chức sản xuất thử nghiệm, ứng dụng kết quả nghiên cứu vào sản xuất và kinh doanh các
                                  sản phẩm khoa học công nghệ theo lĩnh vực đăng ký.
                                </div>
                              </div>
                            </div>

                            <!-- Accordion Item c -->
                            <div class="accordion-item bg-transparent border-0 mb-3">
                              <h2 class="accordion-header" id="headingFuncC">
                                <button
                                  class="about-accordion-button accordion-button collapsed rounded-3 fw-bold id-color-2"
                                  type="button" data-bs-toggle="collapse" data-bs-target="#collapseFuncC"
                                  aria-expanded="false" aria-controls="collapseFuncC">
                                  c) Tư vấn và Chuyển giao
                                </button>
                              </h2>
                              <div id="collapseFuncC" class="accordion-collapse collapse" aria-labelledby="headingFuncC"
                                data-bs-parent="#accordionFunctions">
                                <div class="accordion-body pt-3 pb-2 px-3">
                                  Thực hiện các dịch vụ tư vấn, phản biện, đánh giá và xúc tiến chuyển giao công nghệ
                                  xanh; môi giới và kết nối cung - cầu công nghệ.
                                </div>
                              </div>
                            </div>

                            <!-- Accordion Item d -->
                            <div class="accordion-item bg-transparent border-0 mb-3">
                              <h2 class="accordion-header" id="headingFuncD">
                                <button
                                  class="about-accordion-button accordion-button collapsed rounded-3 fw-bold id-color-2"
                                  type="button" data-bs-toggle="collapse" data-bs-target="#collapseFuncD"
                                  aria-expanded="false" aria-controls="collapseFuncD">
                                  d) Đào tạo và Thông tin
                                </button>
                              </h2>
                              <div id="collapseFuncD" class="accordion-collapse collapse" aria-labelledby="headingFuncD"
                                data-bs-parent="#accordionFunctions">
                                <div class="accordion-body pt-3 pb-2 px-3">
                                  Tổ chức đào tạo bồi dưỡng chuyên môn; thực hiện hoạt động thông tin, truyền thông khoa
                                  học và công nghệ; tổ chức các diễn đàn hội nghị, hội thảo chuyên ngành.
                                </div>
                              </div>
                            </div>

                            <!-- Accordion Item đ -->
                            <div class="accordion-item bg-transparent border-0 mb-3">
                              <h2 class="accordion-header" id="headingFuncE">
                                <button
                                  class="about-accordion-button accordion-button collapsed rounded-3 fw-bold id-color-2"
                                  type="button" data-bs-toggle="collapse" data-bs-target="#collapseFuncE"
                                  aria-expanded="false" aria-controls="collapseFuncE">
                                  đ) Hợp tác và Hội nhập
                                </button>
                              </h2>
                              <div id="collapseFuncE" class="accordion-collapse collapse" aria-labelledby="headingFuncE"
                                data-bs-parent="#accordionFunctions">
                                <div class="accordion-body pt-3 pb-2 px-3">
                                  Thực hiện các hoạt động hợp tác trong nước và quốc tế về nghiên cứu, đào tạo và chuyển
                                  giao tri thức theo quy định của pháp luật.
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Right Column: Nhiệm vụ -->
                      <div class="col-lg-7">
                        <div class="d-flex align-items-center mb-4">
                          <div
                            class="about-section-icon icon-wrap-mint rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-list-check fs-20"></i>
                          </div>
                          <h3 class="mb-0 fs-24 fw-600 text-uppercase id-color-2">Nhiệm vụ trọng tâm</h3>
                        </div>

                        <div class="accordion accordion-flush custom-accordion" id="accordionTasks">
                          <!-- Accordion Item a -->
                          <div class="accordion-item bg-transparent border-0 mb-3">
                            <h2 class="accordion-header" id="headingA">
                              <button
                                class="about-accordion-button accordion-button collapsed rounded-3 fw-bold id-color-2"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseA" aria-expanded="false"
                                aria-controls="collapseA">
                                a) Về nghiên cứu và phát triển công nghệ
                              </button>
                            </h2>
                            <div id="collapseA" class="accordion-collapse collapse" aria-labelledby="headingA"
                              data-bs-parent="#accordionTasks">
                              <div class="accordion-body pt-3 pb-2 px-3">
                                <ul class="custom-check-list mb-0">
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Nghiên cứu các quy trình kỹ thuật hóa học xanh, sản xuất hóa dược, hóa dầu và hóa
                                      chất công nghiệp không độc hại nhằm thay thế các quy trình truyền thống gây ô nhiễm.
                                    </div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Nghiên cứu phát triển các loại vật liệu mới thân thiện với hệ sinh thái, năng
                                      lượng sạch và các giải pháp tối ưu hóa kỹ thuật quá trình hóa học.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Nghiên cứu cơ bản, nghiên cứu ứng dụng và triển khai các giải pháp kỹ thuật trong
                                      lĩnh vực địa kỹ thuật, kỹ thuật dầu khí và năng lượng không phải dầu khí nhằm phát
                                      triển nguồn năng lượng tái tạo.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Nghiên cứu, khảo sát và ứng dụng công nghệ viễn thám (Remote Sensing) trong giám
                                      sát tài nguyên, biến đổi khí hậu và hỗ trợ kỹ thuật môi trường bờ biển.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Nghiên cứu các quy trình kỹ thuật khai thác mỏ hiện đại và xử lý khoáng chất tiên
                                      tiến theo hướng giảm thiểu tác động tiêu cực đến môi trường và hệ sinh thái.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Nghiên cứu thực tiễn và cung cấp luận cứ khoa học hỗ trợ hoạch định các mô hình
                                      kinh tế tuần hoàn và phát triển bền vững tại địa phương.</div>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>

                          <!-- Accordion Item b -->
                          <div class="accordion-item bg-transparent border-0 mb-3">
                            <h2 class="accordion-header" id="headingB">
                              <button
                                class="about-accordion-button accordion-button collapsed rounded-3 fw-bold id-color-2"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseB" aria-expanded="false"
                                aria-controls="collapseB">
                                b) Về sản xuất thử nghiệm và kinh doanh
                              </button>
                            </h2>
                            <div id="collapseB" class="accordion-collapse collapse" aria-labelledby="headingB"
                              data-bs-parent="#accordionTasks">
                              <div class="accordion-body pt-3 pb-2 px-3">
                                <ul class="custom-check-list mb-0">
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Tổ chức sản xuất thử nghiệm (Pilot) các thiết bị, vật liệu hóa học và sản phẩm kỹ
                                      thuật từ kết quả nghiên cứu trong phòng thí nghiệm của Viện.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Sản xuất và kinh doanh các sản phẩm thuộc lĩnh vực công nghệ sạch, vật liệu thân
                                      thiện môi trường và các giải pháp kỹ thuật tiết kiệm năng lượng.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Thương mại hóa các phần mềm, hệ thống dữ liệu, bản đồ số và các sản phẩm trí tuệ
                                      hình thành từ quá trình nghiên cứu viễn thám và kỹ thuật địa chất.</div>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>

                          <!-- Accordion Item c -->
                          <div class="accordion-item bg-transparent border-0 mb-3">
                            <h2 class="accordion-header" id="headingC">
                              <button
                                class="about-accordion-button accordion-button collapsed rounded-3 fw-bold id-color-2"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseC" aria-expanded="false"
                                aria-controls="collapseC">
                                c) Đào tạo, bồi dưỡng và chuyển giao tri thức
                              </button>
                            </h2>
                            <div id="collapseC" class="accordion-collapse collapse" aria-labelledby="headingC"
                              data-bs-parent="#accordionTasks">
                              <div class="accordion-body pt-3 pb-2 px-3">
                                <ul class="custom-check-list mb-0">
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Xây dựng chương trình và tổ chức các khóa đào tạo, tập huấn ngắn hạn về kỹ năng
                                      lập báo cáo ESG, kiểm kê khí nhà kính và quản trị xanh cho doanh nghiệp.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Bồi dưỡng chuyên môn, nghiệp vụ chuyên sâu trong lĩnh vực kỹ thuật hóa học, kỹ
                                      thuật môi trường và ứng dụng viễn thám cho các tổ chức, cá nhân.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Tổ chức các chương trình tuyên truyền, phổ biến kiến thức và kỹ năng về sản xuất
                                      sạch hơn và công nghệ xanh cho cộng đồng.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Liên kết với các trường đại học, viện nghiên cứu trong và ngoài nước để triển
                                      khai các chương trình hợp tác đào tạo chuyên gia và thực tập sinh chuyên ngành.
                                    </div>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>

                          <!-- Accordion Item d -->
                          <div class="accordion-item bg-transparent border-0 mb-3">
                            <h2 class="accordion-header" id="headingD">
                              <button
                                class="about-accordion-button accordion-button collapsed rounded-3 fw-bold id-color-2"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseD" aria-expanded="false"
                                aria-controls="collapseD">
                                d) Dịch vụ khoa học và công nghệ
                              </button>
                            </h2>
                            <div id="collapseD" class="accordion-collapse collapse" aria-labelledby="headingD"
                              data-bs-parent="#accordionTasks">
                              <div class="accordion-body pt-3 pb-2 px-3">
                                <ul class="custom-check-list mb-0">
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Tư vấn chiến lược phát triển bền vững; thực hiện đo lường, kiểm kê khí nhà kính
                                      (Carbon Footprint) và tư vấn lộ trình Net Zero cho doanh nghiệp.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Cung cấp dịch vụ tư vấn lập báo cáo ESG, xếp hạng tín nhiệm xanh và thẩm định các
                                      quy trình sản xuất sạch hơn.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Thiết kế, thẩm định và chuyển giao mô hình kinh tế tuần hoàn, các giải pháp kỹ
                                      thuật năng lượng tái tạo và quản lý chất thải bền vững.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Tổ chức hội thảo, hội nghị khoa học chuyên đề; cung cấp dịch vụ thông tin, cơ sở
                                      dữ liệu chuyên ngành và môi giới, xúc tiến chuyển giao công nghệ.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Thực hiện tư vấn phản biện, giám định độc lập các dự án đầu tư liên quan đến kỹ
                                      thuật hóa học, khai thác khoáng sản và kỹ thuật môi trường.</div>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>

                          <!-- Accordion Item đ -->
                          <div class="accordion-item bg-transparent border-0 mb-3">
                            <h2 class="accordion-header" id="headingE">
                              <button
                                class="about-accordion-button accordion-button collapsed rounded-3 fw-bold id-color-2"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseE" aria-expanded="false"
                                aria-controls="collapseE">
                                đ) Về hợp tác trong và ngoài nước
                              </button>
                            </h2>
                            <div id="collapseE" class="accordion-collapse collapse" aria-labelledby="headingE"
                              data-bs-parent="#accordionTasks">
                              <div class="accordion-body pt-3 pb-2 px-3">
                                <ul class="custom-check-list mb-0">
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Chủ trì và tham gia các dự án hợp tác nghiên cứu khoa học, phát triển công nghệ
                                      xanh với các đối tác trong và ngoài theo quy định của pháp luật.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Thiết lập mạng lưới liên kết chiến lược, ký kết các bản ghi nhớ với các tổ chức
                                      quốc tế để tiếp nhận và nội địa hóa công nghệ tiên tiến.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Tham gia các tổ chức khoa học quốc tế và diễn đàn toàn cầu về kinh tế xanh nhằm
                                      thúc đẩy hội nhập và nâng cao năng lực chuyên môn của Viện.</div>
                                  </li>
                                  <li>
                                    <i class="fa-solid fa-check fs-14 me-3 mt-1"></i>
                                    <div>Cử cán bộ tham gia các chương trình đào tạo, khảo sát học thuật quốc tế để trao
                                      đổi kinh nghiệm và tiếp cận các xu hướng công nghệ mới.</div>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      jQuery(document).ready(function () {
        jQuery('.de_tab .de_nav li').on("click", function () {
          setTimeout(function () {
            jQuery(window).trigger('resize');
            jQuery(window).trigger('scroll');
            jQuery('.owl-carousel').trigger('refresh.owl.carousel');
            if (typeof wow !== 'undefined' && typeof wow.sync === 'function') {
              wow.sync();
            }
          }, 150);
        });
      });
    });
  </script>
@endpush
