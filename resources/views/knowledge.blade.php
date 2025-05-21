@extends('layouts.auth')
@section('page-title')
    {{ __('Knowledge') }}
@endsection

@section('content')
    <div class="auth-wrapper knowledge-sec">
        <div class="auth-content">
            {{-- Navbar --}}
            @include('layouts.navbar')


            <div class="knowledge-content">
                <h2 class="mb-3  f-w-600 text-center">{{ __('Knowledge') }}</h2>

                @if ($knowledgeBaseCategory->count() > 0)
                    <div class="row">
                        @foreach ($knowledgeBaseCategory as $index => $category)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 mb-0">
                                    <div class="card-header p-3" id="heading-{{ $index }}" role="button"
                                        aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                                        <h4 class="mb-0">
                                            {{$category->title}} ({{$category->knowledgebase->count()}})
                                        </h4>
                                    </div>
                                    <div class="card-body p-3">
                                        <ul class="knowledge_ul">
                                            @foreach ($category->knowledgebase as $key => $knowledgeBase)
                                                <li style="list-style: none;" class="child">
                                                    <a href="{{ route('knowledgedesc', ['id' => encrypt($knowledgeBase->id)]) }}"
                                                        target="__blank">
                                                        <i class="far fa-file-alt me-1"></i>
                                                        {{ isset($knowledgeBase->title) ? $knowledgeBase->title : '-' }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
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
            @include('layouts.footer')
        </div>
    </div>
@endsection