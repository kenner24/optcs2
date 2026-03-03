@extends('superAdmin.layout.adminLayout')
@section('title', 'Website Page Management')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Website Page Management</h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills nav-fill" id="company-reports-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="aboutUs-tab" data-bs-toggle="tab"
                                        data-bs-target="#aboutUs" type="button" role="tab" aria-controls="aboutUs"
                                        aria-selected="true">About Us</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="term-of-use-tab" data-bs-toggle="tab"
                                        data-bs-target="#term-of-use" type="button" role="tab"
                                        aria-controls="term-of-use" aria-selected="true">Terms Of Use</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="privacy-policy-tab" data-bs-toggle="tab"
                                        data-bs-target="#privacy-policy" type="button" role="tab"
                                        aria-controls="privacy-policy" aria-selected="true">Privacy Policy</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq"
                                        type="button" role="tab" aria-controls="faq" aria-selected="true">FAQ</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('admin.website-management.update') }}"
                                enctype="multipart/form-data">
                                @csrf()
                                <div class="tab-content" id="myTabContent">
                                    @foreach ($data as $key => $value)
                                        @if (\App\Enums\WebsitePageTypeEnum::ABOUT_US->value === $value->page_name)
                                            <div class="tab-pane fade show active" id="aboutUs" role="tabpanel"
                                                aria-labelledby="aboutUs-tab">
                                                <textarea class="summernote-editor" name="{{ $value->page_name }}" id="about-us-editor">
                                                    {!! $value->content !!}
                                                </textarea>
                                            </div>
                                        @elseif(\App\Enums\WebsitePageTypeEnum::PRIVACY_POLICY->value === $value->page_name)
                                            <div class="tab-pane fade" id="privacy-policy" role="tabpanel"
                                                aria-labelledby="privacy-policy-tab">
                                                <textarea class="summernote-editor" name="{{ $value->page_name }}" id="privacy-policy-editor">
                                                {!! $value->content !!}
                                                </textarea>
                                            </div>
                                        @elseif(\App\Enums\WebsitePageTypeEnum::TERMS_OF_USE->value === $value->page_name)
                                            <div class="tab-pane fade" id="term-of-use" role="tabpanel"
                                                aria-labelledby="term-of-use-tab">
                                                <textarea name="{{ $value->page_name }}" class="summernote-editor" id="term-of-use-editor">
                                                {!! $value->content !!}
                                                </textarea>
                                            </div>
                                        @endif
                                    @endforeach
                                    <div class="tab-pane fade" id="faq" role="tabpanel" aria-labelledby="faq-tab">
                                        <!-- <div>
                                            <button type="button" class="btn btn-outline-primary">Add FAQ</button>
                                        </div><br /> -->
                                        <div id="faq-questions">
                                            @foreach ($data as $key => $value)
                                                @if (\App\Enums\WebsitePageTypeEnum::FAQ->value === $value->page_name)
                                                    @include('superAdmin.websitePages.faq.index', [
                                                        'data' => $value,
                                                    ])
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <button type="submit" class="btn btn-success">Save All</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $('.summernote-editor').summernote({
            height: 400, // set editor height
            airMode: true
        });
    </script>
@endpush
