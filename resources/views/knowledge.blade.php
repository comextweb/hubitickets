@extends('layouts.auth')
@section('page-title')
    {{ __('Knowledge') }}
@endsection

@section('content')
    <div class="auth-wrapper auth-v1">
        <div class="bg-auth-side bg-primary"></div>
        <div class="auth-content">
            {{-- Navbar --}}
            @include('layouts.navbar')


            <div class="row align-items-center justify-content-center text-start">
                <div class="col-xl-12 text-center">
                    <div class="mx-3 mx-md-5">
                        <h2 class="mb-3 text-primary f-w-600">{{ __('Knowledge') }}</h2>
                    </div>

                    <div class="text-start">
                        @if ($knowledgeBaseCategory->count() > 0)
                            <div class="row">
                                @foreach ($knowledgeBaseCategory as $index => $category)
                                    <div class="col-md-4 mb-3">
                                        <div class="card" style="min-height: 200px;">
                                            <div class="card-header py-3 mb-3" id="heading-{{ $index }}"role="button"
                                                aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                                                <div class="row m-auto">
                                                    <h6 class="mr-3">
                                                        {{$category->title}}
                                                        {{$category->knowledgebase->count()}}
                                                    </h6>
                                                </div>
                                            </div>
                                            <ul class="knowledge_ul">
                                                @foreach ($category->knowledgebase as $key => $knowledgeBase)
                                                        <li style="list-style: none;" class="child">
                                                            <a href="{{ route('knowledgedesc', ['id' => encrypt($knowledgeBase->id)]) }}" target="__blank">
                                                                <i class="far fa-file-alt ms-3"></i>
                                                                {{ isset($knowledgeBase->title) ? $knowledgeBase->title : '-' }}
                                                            </a>
                                                        </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0 text-center">{{ __('No Knowledges found.') }}</h6>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
@endsection
