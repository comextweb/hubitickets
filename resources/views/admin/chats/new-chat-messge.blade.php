@push('css-page')
<link rel="stylesheet" href="{{ asset('css/main-style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
<link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush
<div class="chat-top-content">
    <div class="chat-container">
        {{-- Chat First Message --}}
        <div class="chat-container-wrp">
            @if ($ticket->type != 'Instagram' && $ticket->type != 'Facebook')
            {{-- customer login --}}
            @if(moduleIsActive('CustomerLogin') && \Auth::user()->type == 'customer')
            <div class="msg right-msg">
                <div class="msg-box">
                    <div class="msg-box-content">
                        <div class="msg-box-inner">
                            <p>{!! $ticket->description !!}</p>
                            @php $attachments = json_decode($ticket->attachments); @endphp
                            @if (isset($attachments) && !empty($attachments))
                                <div class="attachments-wrp">
                                    <h6>{{ __('Ticket Attachments') }} :</h6>
                                    <ul class="attachments-list">
                                        @foreach ($attachments as $index => $attachment)
                                        <li>
                                            <span> {{ basename($attachment) }} </span>
                                            <a download=""
                                                href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('uploads/default-images/image_not_available.png') }}"
                                                class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i
                                                    class="fa fa-download ms-2"></i></a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif      
                        </div>
                        <span>{{ \Carbon\Carbon::parse($ticket->created_at)->format('l h:ia') }}</span>
                    </div>
                    <div class="msg-user-info" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $ticket->name }}">
                        <div class="msg-img">
                            <img alt="{{ $ticket->name }}" class="img-fluid" avatar="{{ $ticket->name }}">
                        </div>
                    </div>
                </div>
                @else
                <div class="msg left-msg">
                    <div class="msg-box">
                        <div class="msg-user-info" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ $ticket->name }}">
                            <div class="msg-img">
                                <img alt="{{ $ticket->name }}" class="img-fluid" avatar="{{ $ticket->name }}">
                            </div>
                        </div>
                        <div class="msg-box-content">
                            <div class="msg-box-inner">
                                <p>{!! $ticket->description !!}</p>
                                @php $attachments = json_decode($ticket->attachments);@endphp
                                @if (isset($attachments) && !empty($attachments))
                                <div class="attachments-wrp">
                                    <h6>{{ __('Ticket Attachments') }} :</h6>
                                    <ul class="attachments-list">
                                        @foreach ($attachments as $index => $attachment)
                                        <li>
                                            <span> {{ basename($attachment) }} </span>
                                            <a download=""
                                                href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('uploads/default-images/image_not_available.png') }}"
                                                class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i
                                                    class="fa fa-download ms-2"></i></a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                            <span>{{ \Carbon\Carbon::parse($ticket->created_at)->format('l h:ia') }}</span>
                        </div>
                    </div>
                </div>
                @endif
                @endif
            </div>

            <div class="messages-container" id="msg">
                @foreach ($ticket->conversions as $conversion)
                     @if (moduleIsActive('CustomerLogin') && \Auth::user()->type == 'customer')
                         @if ($conversion->sender == 'user')
                             <div class="msg right-msg" data-id="{{ $conversion->id }}">
                                 <div class="msg-box {{ isset($isSaveChat, $conversion->is_bookmark) && $isSaveChat && $conversion->is_bookmark ? 'bookmark-active' : '' }}"
                                      data-conversion-id="{{ $conversion->id }}">
                                     <div class="msg-box-content">
                                         <div class="msg-box-inner">
                                             <p>{!! $conversion->description !!}</p>
                                             @php $attachments = json_decode($conversion->attachments); @endphp
                                             @if (isset($attachments))
                                                 <div class="attachments-wrp">
                                                     <h6>{{ __('Attachments') }} :</h6>
                                                     <ul class="attachments-list">
                                                         @foreach ($attachments as $index => $attachment)
                                                             <li>
                                                                 <span>{{ basename($attachment) }}</span>
                                                                 <a download=""
                                                                    href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('Uploads/default-images/image_not_available.png') }}"
                                                                    class="edit-icon py-1 ml-2"
                                                                    title="{{ __('Download') }}">
                                                                     <i class="fa fa-download ms-2"></i>
                                                                 </a>
                                                             </li>
                                                         @endforeach
                                                     </ul>
                                                 </div>
                                             @endif
                                         </div>
                                         <span>{{ \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia') }}</span>
                                         {{-- save chat addon --}}
                                         @stack('save-chat')
                                         {{-- end --}}
                                     </div>
                                     <div class="msg-user-info"
                                          data-bs-toggle="tooltip"
                                          data-bs-placement="top"
                                          title="{{ $conversion->replyBy()->name ?? '' }}">
                                         <div class="msg-img">
                                             <img alt="{{ $conversion->replyBy()->name ?? '' }}"
                                                  class="img-fluid"
                                                  avatar="{{ $conversion->replyBy()->name ?? '' }}">
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         @else
                             <div class="msg left-msg" data-id="{{ $conversion->id }}">
                                 <div class="msg-box {{ isset($isSaveChat, $conversion->is_bookmark) && $isSaveChat && $conversion->is_bookmark ? 'bookmark-active' : '' }}"
                                      data-conversion-id="{{ $conversion->id }}">
                                     <div class="msg-user-info"
                                          data-bs-toggle="tooltip"
                                          data-bs-placement="top"
                                          title="{{ $conversion->replyBy()->name }}">
                                         <div class="msg-img">
                                             @if ($ticket->type == 'Instagram' && !empty($isInstagramChat))
                                                 @include('instagram-chat::instagram.profile')
                                             @elseif ($ticket->type == 'Facebook' && !empty($isFacebookChat))
                                                 @include('facebook-chat::facebook.profile')
                                             @else
                                                 <img alt="{{ $conversion->replyBy()->name }}"
                                                      class="img-fluid"
                                                      avatar="{{ $conversion->replyBy()->name }}">
                                             @endif
                                         </div>
                                     </div>
                                     <div class="msg-box-content">
                                         <div class="msg-box-inner">
                                             <p>{!! $conversion->description !!}</p>
                                             @php $attachments = json_decode($conversion->attachments); @endphp
                                             @if (isset($attachments))
                                                 <div class="attachments-wrp">
                                                     <h6>{{ __('Attachments') }} :</h6>
                                                     <ul class="attachments-list">
                                                         @foreach ($attachments as $index => $attachment)
                                                             <li>
                                                                 <span>{{ basename($attachment) ?? '' }}</span>
                                                                 <a download=""
                                                                    href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('Uploads/default-images/image_not_available.png') }}"
                                                                    class="edit-icon py-1 ml-2"
                                                                    title="{{ __('Download') }}">
                                                                     <i class="fa fa-download ms-2"></i>
                                                                 </a>
                                                             </li>
                                                         @endforeach
                                                     </ul>
                                                 </div>
                                             @endif
                                         </div>
                                         <span>{{ \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia') }}</span>
                                          {{-- save chat addon --}}
                                          @stack('save-chat')
                                          {{-- end --}}
                                     </div>
                                 </div>
                             </div>
                         @endif
                     @else
                         @if ($conversion->sender == 'user')
                             <div class="msg left-msg" data-id="{{ $conversion->id }}">
                                 <div class="msg-box {{ isset($isSaveChat, $conversion->is_bookmark) && $isSaveChat && $conversion->is_bookmark ? 'bookmark-active' : '' }}"
                                      data-conversion-id="{{ $conversion->id }}">
                                     <div class="msg-user-info"
                                          data-bs-toggle="tooltip"
                                          data-bs-placement="top"
                                          title="{{ $conversion->replyBy()->name }}">
                                         <div class="msg-img">
                                             @if ($ticket->type == 'Instagram' && !empty($isInstagramChat))
                                                 @include('instagram-chat::instagram.profile')
                                             @elseif ($ticket->type == 'Facebook' && !empty($isFacebookChat))
                                                 @include('facebook-chat::facebook.profile')
                                             @else
                                                 <img alt="{{ $conversion->replyBy()->name }}"
                                                      class="img-fluid"
                                                      avatar="{{ $conversion->replyBy()->name }}">
                                             @endif
                                         </div>
                                     </div>
                                     <div class="msg-box-content">
                                         <div class="msg-box-inner">
                                             <p>{!! $conversion->description !!}</p>
                                             @php $attachments = json_decode($conversion->attachments); @endphp
                                             @if (isset($attachments))
                                                 <div class="attachments-wrp">
                                                     <h6>{{ __('Attachments') }} :</h6>
                                                     <ul class="attachments-list">
                                                         @foreach ($attachments as $index => $attachment)
                                                             <li>
                                                                 <span>{{ basename($attachment) ?? '' }}</span>
                                                                 <a download=""
                                                                    href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('Uploads/default-images/image_not_available.png') }}"
                                                                    class="edit-icon py-1 ml-2"
                                                                    title="{{ __('Download') }}">
                                                                     <i class="fa fa-download ms-2"></i>
                                                                 </a>
                                                             </li>
                                                         @endforeach
                                                     </ul>
                                                 </div>
                                             @endif
                                         </div>
                                         <span>{{ \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia') }}</span>
                                         {{-- savechat addon --}}
                                         @stack('save-chat')
                                         {{-- end --}}
                                     </div>
                                 </div>
                             </div>
                         @else
                             <div class="msg right-msg" data-id="{{ $conversion->id }}">
                                 <div class="msg-box {{ isset($isSaveChat, $conversion->is_bookmark) && $isSaveChat && $conversion->is_bookmark ? 'bookmark-active' : '' }}"
                                      data-conversion-id="{{ $conversion->id }}">
                                     <div class="msg-box-content">
                                         <div class="msg-box-inner">
                                             <p>{!! $conversion->description !!}</p>
                                             @php $attachments = json_decode($conversion->attachments); @endphp
                                             @if (isset($attachments))
                                                 <div class="attachments-wrp">
                                                     <h6>{{ __('Attachments') }} :</h6>
                                                     <ul class="attachments-list">
                                                         @foreach ($attachments as $index => $attachment)
                                                             <li>
                                                                 <span>{{ basename($attachment) }}</span>
                                                                 <a download=""
                                                                    href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('Uploads/default-images/image_not_available.png') }}"
                                                                    class="edit-icon py-1 ml-2"
                                                                    title="{{ __('Download') }}">
                                                                     <i class="fa fa-download ms-2"></i>
                                                                 </a>
                                                             </li>
                                                         @endforeach
                                                     </ul>
                                                 </div>
                                             @endif
                                         </div>
                                         <span>{{ \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia') }}</span>
                                         {{-- save chat addon --}}
                                         @stack('save-chat')
                                         {{-- end --}}
                                     </div>
                                     <div class="msg-user-info"
                                          data-bs-toggle="tooltip"
                                          data-bs-placement="top"
                                          title="{{ $conversion->replyBy()->name ?? '' }}">
                                         <div class="msg-img">
                                             <img alt="{{ $conversion->replyBy()->name ?? '' }}"
                                                  class="img-fluid"
                                                  avatar="{{ $conversion->replyBy()->name ?? '' }}">
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         @endif
                     @endif
               @endforeach
            </div>
        </div>
    </div>
    @if ($ticket->status != 'Closed')
        <div class="chat-footer">
            <div class="reply-title mb-3">
                <span class="f-w-600 fs-5">Reply</span>
            </div>
            <form method="POST" action="{{ route('admin.reply.store', $ticket->id) }}" enctype="multipart/form-data"
                class="needs-validation" novalidate id="your-form-id">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <textarea name="reply_description" id="reply_description"
                            class="form-control summernote-simple grammer_textarea @error('name') is-invalid @enderror"
                            required>
                                    </textarea>
                        @error('reply_description')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="chat-footer-btn-wrp d-flex gap-3 flex-wrap align-items-center justify-content-between">
                        <ul class="d-flex gap-3 flex-wrap align-items-center position-relative">
                            <li>
                                <div class="choose-file form-group choose-file-col mb-0 footer-btn d-flex">
                                    <label for="file" class="form-label mb-0 w-auto">
                                        <div class="choose-file-wrp btn-submit">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_903_1011)">
                                                    <path d="M22.5552 3.4767C21.6221 2.54353 20.3814 2.02961 19.0617 2.02961C17.7424 2.02961 16.5021 2.5432 15.5691 3.47581C15.5688 3.47609 15.5684 3.47638 15.5681 3.47666L8.55613 10.4887L5.06055 13.9843C3.96027 15.0846 3.96027 16.8749 5.06055 17.9752C5.59355 18.5082 6.30218 18.8017 7.05597 18.8017C7.80975 18.8017 8.51839 18.5082 9.05139 17.9752L18.8724 8.15414C19.147 7.87949 19.147 7.43421 18.8724 7.15955C18.5977 6.88489 18.1524 6.88489 17.8778 7.15955L8.05679 16.9805C7.78945 17.2479 7.43401 17.3951 7.05597 17.3951C6.67792 17.3951 6.32248 17.2479 6.05514 16.9805C5.78779 16.7132 5.64057 16.3577 5.64057 15.9797C5.64057 15.6017 5.78779 15.2462 6.05514 14.9789L9.55072 11.4832L16.5621 4.47191C16.5623 4.47167 16.5625 4.47148 16.5628 4.47125C17.2302 3.80378 18.1177 3.43615 19.0617 3.43615C20.0057 3.43615 20.8932 3.80373 21.5607 4.47125C22.9384 5.84899 22.9385 8.09071 21.5611 9.46869C21.5609 9.46883 21.5608 9.46897 21.5606 9.46911L11.053 19.9767C8.84903 22.1807 5.26286 22.1807 3.05889 19.9767C0.854919 17.7728 0.854919 14.1866 3.05889 11.9826L12.8799 2.16165C13.1546 1.88699 13.1546 1.44171 12.8799 1.16705C12.6053 0.892395 12.16 0.892395 11.8853 1.16705L2.0643 10.9881C-0.688099 13.7405 -0.688099 18.219 2.0643 20.9714C3.39764 22.3047 5.17035 23.039 7.05592 23.039C8.94153 23.039 10.7142 22.3047 12.0476 20.9714L22.5545 10.4644C22.5547 10.4642 22.555 10.464 22.5552 10.4637C24.4816 8.53739 24.4816 5.40302 22.5552 3.4767Z" fill="#555555" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_903_1011">
                                                        <rect width="24" height="24" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>

                                            <input type="file" name="reply_attachments[]" id="file"
                                                class="form-control mb-2 {{ $errors->has('reply_attachments') ? ' is-invalid' : '' }}"
                                                multiple="" data-filename="multiple_reply_file_selection">
                                        </div>

                                        @error('reply_description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </label>
                                </div>
                            </li>
                            @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
                            <li>
                                <a href="javascript:void;" data-size="md" class="footer-btn"
                                    data-ajax-popup-over="true" id="grammarCheck"
                                    data-url="{{ route('grammar', ['grammar']) }}" data-bs-placement="top"
                                    data-title="{{ __('Grammar check with AI') }}">
                                    <span><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M17.5579 8.83959L18.9725 6.04805C19.1218 5.75348 19.0649 5.39906 18.831 5.16614C18.598 4.9342 18.2451 4.87912 17.9528 5.02884L15.1806 6.4492L12.9801 4.23661C12.748 4.00326 12.3951 3.94645 12.1019 4.09528C11.8077 4.24458 11.6445 4.56412 11.696 4.89047L12.1833 7.98469L9.40852 9.40631C9.11569 9.55636 8.95378 9.87586 9.00553 10.2014C9.05723 10.5264 9.30998 10.7795 9.63441 10.8312L10.9734 11.0445L0.412172 21.6002C0.146391 21.8658 0 22.2189 0 22.5946C0 22.9702 0.146391 23.3234 0.412172 23.589C0.686391 23.8631 1.04648 24 1.40672 24C1.76686 24 2.12709 23.863 2.40127 23.589L7.97011 18.0235C8.15325 17.8405 8.15334 17.5437 7.97034 17.3606C7.78734 17.1774 7.49058 17.1773 7.30744 17.3604L1.73859 22.9259C1.55564 23.1088 1.25784 23.1087 1.07489 22.9259C0.986297 22.8373 0.9375 22.7197 0.9375 22.5945C0.9375 22.4694 0.986297 22.3518 1.07489 22.2633L12.1174 11.2266L12.7088 11.3208L12.7961 11.875L9.95995 14.7095C9.77681 14.8925 9.77672 15.1893 9.95972 15.3724C10.0513 15.464 10.1713 15.5098 10.2913 15.5098C10.4112 15.5098 10.5311 15.4641 10.6226 15.3726L12.9765 13.0201L13.1961 14.4139C13.2474 14.7401 13.5006 14.9938 13.8262 15.0451C13.866 15.0514 13.9057 15.0545 13.9451 15.0545C14.2268 15.0545 14.4882 14.8972 14.619 14.6392L16.0323 11.85L19.1056 12.3395C19.4298 12.3908 19.7483 12.2291 19.8982 11.9364C20.0484 11.6427 19.9932 11.2883 19.7607 11.0546L17.5579 8.83959ZM16.0492 10.9035C15.7238 10.8517 15.4047 11.0147 15.2554 11.3093L14.0354 13.717L13.6147 11.0467C13.5634 10.7211 13.3107 10.4675 12.9856 10.4157L10.3225 9.99159L12.7262 8.76014C13.0186 8.61033 13.1806 8.2913 13.1295 7.96626L12.7084 5.29298L14.6091 7.20412C14.8421 7.43845 15.196 7.49466 15.49 7.34414L17.8871 6.11601L16.6636 8.53041C16.5151 8.82342 16.5709 9.17667 16.8024 9.40955L18.7096 11.3272L16.0492 10.9035Z" fill="#555555" />
                                            <path d="M22.0948 1.88629C21.9113 1.70376 21.6145 1.70446 21.4319 1.88807L19.8005 3.52837C19.6179 3.71193 19.6187 4.0087 19.8023 4.19132C19.8937 4.28221 20.0133 4.32768 20.1328 4.32768C20.2532 4.32768 20.3736 4.2816 20.4652 4.18949L22.0966 2.5492C22.2791 2.36568 22.2784 2.06887 22.0948 1.88629Z" fill="#555555" />
                                            <path d="M16.1191 2.30313C16.1437 2.30702 16.1683 2.3089 16.1926 2.3089C16.4192 2.3089 16.6186 2.14404 16.655 1.91304L16.8354 0.767462C16.8757 0.511759 16.7011 0.271806 16.4454 0.231493C16.1901 0.191181 15.9497 0.365837 15.9094 0.621634L15.7289 1.76721C15.6887 2.02292 15.8633 2.26287 16.1191 2.30313Z" fill="#555555" />
                                            <path d="M10.8447 2.74772C10.9273 2.91075 11.0921 3.00469 11.2632 3.00469C11.3345 3.00469 11.4068 2.98837 11.4748 2.95397C11.7057 2.83697 11.798 2.55487 11.681 2.32392L10.6335 0.256967C10.5165 0.0260137 10.2345 -0.0662832 10.0035 0.0507168C9.77258 0.167717 9.68028 0.449811 9.79733 0.680764L10.8447 2.74772Z" fill="#555555" />
                                            <path d="M7.45485 5.57104L8.48268 6.09763C8.55111 6.13265 8.6241 6.14929 8.69605 6.14929C8.86635 6.14929 9.03065 6.05619 9.11361 5.89419C9.23165 5.6638 9.14052 5.38129 8.91013 5.26326L7.8823 4.73666C7.65191 4.61877 7.36944 4.70966 7.25136 4.94015C7.13333 5.17049 7.22441 5.45296 7.45485 5.57104Z" fill="#555555" />
                                            <path d="M18.7395 15.1464C18.6224 14.9154 18.3405 14.8231 18.1095 14.9401C17.8785 15.0571 17.7862 15.3392 17.9033 15.5702L18.427 16.6037C18.5097 16.7667 18.6744 16.8607 18.8455 16.8607C18.9168 16.8607 18.9891 16.8443 19.0571 16.8099C19.288 16.6929 19.3803 16.4108 19.2633 16.1799L18.7395 15.1464Z" fill="#555555" />
                                            <path d="M23.745 13.4569L21.6894 12.4038C21.4591 12.2859 21.1766 12.3768 21.0585 12.6073C20.9405 12.8377 21.0316 13.1202 21.262 13.2382L23.3176 14.2914C23.386 14.3264 23.459 14.343 23.531 14.343C23.7013 14.343 23.8655 14.2499 23.9485 14.0879C24.0665 13.8575 23.9754 13.575 23.745 13.4569Z" fill="#555555" />
                                            <path d="M21.6778 7.8952C21.7145 8.12587 21.9137 8.29031 22.1401 8.29031C22.1646 8.29031 22.1895 8.28834 22.2144 8.2844L23.3538 8.10295C23.6094 8.06221 23.7837 7.82193 23.743 7.56623C23.7023 7.31057 23.4617 7.1369 23.2063 7.17703L22.067 7.35848C21.8113 7.39926 21.6371 7.63954 21.6778 7.8952Z" fill="#555555" />
                                            <path d="M9.42557 16.3806C9.4354 16.122 9.23372 15.9043 8.9751 15.8945C8.71647 15.8846 8.49884 16.0863 8.489 16.345C8.47917 16.6036 8.68085 16.8212 8.93947 16.831C9.1981 16.8409 9.41573 16.6392 9.42557 16.3806Z" fill="#555555" />
                                        </svg>
                                    </span>
                                </a>

                            </li>
                            <li>
                                <a href="javascript:void;" data-size="md" class="footer-btn"
                                    data-ajax-popup-over="true" data-size="md"
                                    data-title="{{ __('Generate content with AI') }}"
                                    data-url="{{ route('generate', ['reply']) }}"
                                    data-toggle="tooltip" title="{{ __('Generate') }}">
                                    <span>
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21.75 8.4375H20.0625V7.5C20.0625 3.8811 17.1182 0.9375 13.5 0.9375H10.5C6.88184 0.9375 3.9375 3.8811 3.9375 7.5V8.4375H2.25C1.11328 8.4375 0.1875 9.36255 0.1875 10.5V15C0.1875 16.1375 1.11328 17.0625 2.25 17.0625H3.99435C4.27835 19.5865 6.40063 21.5625 9 21.5625H9.27539C9.52222 22.4255 10.3095 23.0625 11.25 23.0625H12.75C13.8867 23.0625 14.8125 22.1375 14.8125 21C14.8125 19.8625 13.8867 18.9375 12.75 18.9375H11.25C10.3095 18.9375 9.52222 19.5745 9.27539 20.4375H9C6.8291 20.4375 5.0625 18.6709 5.0625 16.5V7.5C5.0625 4.50146 7.50146 2.0625 10.5 2.0625H13.5C16.4985 2.0625 18.9375 4.50146 18.9375 7.5V16.5C18.9375 16.8105 19.1895 17.0625 19.5 17.0625H21.75C22.8867 17.0625 23.8125 16.1375 23.8125 15V10.5C23.8125 9.36255 22.8867 8.4375 21.75 8.4375ZM11.25 20.0625H12.75C13.2671 20.0625 13.6875 20.4829 13.6875 21C13.6875 21.5171 13.2671 21.9375 12.75 21.9375H11.25C10.7329 21.9375 10.3125 21.5171 10.3125 21C10.3125 20.4829 10.7329 20.0625 11.25 20.0625ZM1.3125 15V10.5C1.3125 9.98291 1.73291 9.5625 2.25 9.5625H3.9375V15.9375H2.25C1.73291 15.9375 1.3125 15.5171 1.3125 15ZM22.6875 15C22.6875 15.5171 22.2671 15.9375 21.75 15.9375H20.0625V9.5625H21.75C22.2671 9.5625 22.6875 9.98291 22.6875 10.5V15Z" fill="#555555" />
                                            <path d="M11.2502 7.6875H10.5002C10.2541 7.6875 10.0373 7.84717 9.96257 8.08228L8.08757 14.0823C7.99529 14.3789 8.16081 14.6946 8.45671 14.7869C8.75993 14.8813 9.06902 14.7144 9.16277 14.4177L9.50801 13.3125H12.2423L12.5876 14.4177C12.6637 14.6587 12.8849 14.8125 13.1252 14.8125C13.1808 14.8125 13.2365 14.8044 13.2936 14.7869C13.5895 14.6946 13.7551 14.3789 13.6628 14.0823L11.7878 8.08228C11.7131 7.84717 11.4963 7.6875 11.2502 7.6875ZM9.85939 12.1875L10.8752 8.93555L11.8909 12.1875H9.85939Z" fill="#555555" />
                                            <path d="M15.375 7.6875C15.0645 7.6875 14.8125 7.93945 14.8125 8.25V14.25C14.8125 14.5605 15.0645 14.8125 15.375 14.8125C15.6855 14.8125 15.9375 14.5605 15.9375 14.25V8.25C15.9375 7.93945 15.6855 7.6875 15.375 7.6875Z" fill="#555555" />
                                        </svg>
                                    </span>
                                </a>
                            </li>
                            @endif
                                                    
                        {{-- save reply addon --}}
                            @if (!Auth::user()->hasRole('customer'))
                            @stack('save-reply')
                            @endif
                            {{-- end --}}
                        </ul>


                        <div class="chat-footer-btn d-flex align-items-center gap-3">
                            <button class="btn btn-primary d-flex gap-2 f-w-600 align-items-center justify-content-center btn-submit" type="button"
                                id="reply_submit">
                                <!-- {{ __('Send') }} -->
                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.0839 8.14303L2.59622 1.34086C2.47163 1.28236 2.33481 1.25195 2.19617 1.25195C1.69223 1.25195 1.28369 1.64517 1.28369 2.13022V2.15556C1.28369 2.27341 1.2987 2.39082 1.3284 2.50515L2.71812 7.85554C2.75607 8.0017 2.88445 8.10966 3.03999 8.12631L9.14823 8.77955C9.36006 8.80218 9.5203 8.9745 9.5203 9.17969C9.5203 9.38487 9.36006 9.55719 9.14823 9.57982L3.03999 10.2331C2.88445 10.2497 2.75607 10.3577 2.71812 10.5038L1.3284 15.8542C1.2987 15.9686 1.28369 16.086 1.28369 16.2038V16.2292C1.28369 16.7142 1.69223 17.1074 2.19617 17.1074C2.33481 17.1074 2.47163 17.077 2.59622 17.0185L17.0839 10.2163C17.4957 10.0231 17.7569 9.62068 17.7569 9.17969C17.7569 8.73869 17.4957 8.33632 17.0839 8.14303Z" fill="white" />
                                </svg>
                            </button>
                            @if (!Auth::user()->hasRole('customer'))
                                @permission('send-close-ticket-reply manage')
                                    @stack('send-close')
                                @endpermission
                            @endif
                            {{-- Add Button For Close Existing Chat Ticket In Whatsapp --}}
                            @if (!Auth::user()->hasRole('customer'))
                                @permission('WhatsAppChatBotAndChat manage')
                                    @stack('whatsapp-close-ticket')
                                @endpermission
                            @endif
                        </div>
                    </div>
                        <p class="multiple_reply_file_selection"></p>
                    </div>
            </form>
        </div>
    @endif
</div>
<div class="msg-card-wrp">
    <div class="msg-card ticket-info-card">
        <div class="msg-card-top d-flex align-items-center justify-content-between gap-2">
            <div class="info-name-wrp d-flex align-items-center gap-2 flex-warp flex-1">
                <div class="avatar-image">
                    <img class="avatar-sm rounded-circle mr-3" alt="{{ $ticket->name }}" avatar="{{ $ticket->name }}">
                </div>
                <div class="info-name">
                    <h6 class="mb-0">{{ $ticket->name }}</h6>
                </div>
            </div>
            <button type="button" class="btn btn-primary close-icon">
                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.301791 13.2446C-0.0997985 13.6463 -0.0997366 14.2973 0.301914 14.6988C0.703565 15.1004 1.3547 15.1004 1.75628 14.6987L7.50013 8.95434L13.2444 14.6982C13.646 15.0998 14.2972 15.0998 14.6988 14.6982C15.1004 14.2967 15.1004 13.6455 14.6988 13.244L8.95439 7.49998L14.6984 1.75539C15.0999 1.35377 15.0999 0.70268 14.6982 0.301131C14.2966 -0.100428 13.6454 -0.100367 13.2438 0.301254L7.49992 6.04573L1.75558 0.301748C1.35397 -0.0998319 0.702825 -0.0998319 0.301215 0.301748C-0.100405 0.703338 -0.100405 1.35443 0.301215 1.75602L6.04577 7.50009L0.301791 13.2446Z" fill="white" />
                </svg>
            </button>
        </div>
        <div class="msg-card-bottom">
            <ul class="card-bottom-info">
                <li class="d-flex align-items-center justify-content-between gap-2">
                    <div class="input-wrp flex-1">
                        <label>{{ __('Name') }} :</label>
                        <input type="text" name="name" id="ticket-user-name" value="{{ $ticket->name }}">
                    </div>
                    <div class="" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ __('Save') }}">
                        <a href="#" id="save-name">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_1107_6572)">
                                    <path d="M18.7782 3.22182C16.7006 1.14421 13.9382 0 11 0C8.06184 0 5.29942 1.14421 3.22182 3.22182C1.14421 5.29942 0 8.06184 0 11C0 13.9382 1.14421 16.7006 3.22182 18.7782C5.29942 20.8558 8.06184 22 11 22C13.9382 22 16.7006 20.8558 18.7782 18.7782C20.8558 16.7006 22 13.9382 22 11C22 8.06184 20.8558 5.29942 18.7782 3.22182ZM4.77406 18.4464C5.13728 15.321 7.82434 12.9081 11 12.9081C12.6741 12.9081 14.2483 13.5603 15.4325 14.7443C16.4329 15.7449 17.0638 17.0512 17.2261 18.4462C15.5392 19.8589 13.3673 20.7109 11 20.7109C8.63269 20.7109 6.46092 19.8591 4.77406 18.4464ZM11 11.5804C9.15788 11.5804 7.65901 10.0815 7.65901 8.23943C7.65901 6.39714 9.15788 4.89844 11 4.89844C12.8421 4.89844 14.341 6.39714 14.341 8.23943C14.341 10.0815 12.8421 11.5804 11 11.5804ZM18.3356 17.3565C18.0071 16.0322 17.3221 14.8111 16.3439 13.8329C15.5517 13.0407 14.6144 12.4463 13.5922 12.0739C14.821 11.2405 15.6301 9.83263 15.6301 8.23943C15.6301 5.68648 13.5529 3.60938 11 3.60938C8.44705 3.60938 6.36995 5.68648 6.36995 8.23943C6.36995 9.83347 7.17964 11.2419 8.40945 12.0751C7.46901 12.4178 6.59872 12.9477 5.84996 13.6453C4.76567 14.655 4.01271 15.9426 3.66359 17.3555C2.18503 15.651 1.28906 13.4282 1.28906 11C1.28906 5.64536 5.64536 1.28906 11 1.28906C16.3546 1.28906 20.7109 5.64536 20.7109 11C20.7109 13.4287 19.8146 15.652 18.3356 17.3565Z" fill="#888888" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_1107_6572">
                                        <rect width="22" height="22" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </a>
                    </div>
                </li>
                <li class="d-flex align-items-center justify-content-between gap-2">
                    <div class="input-wrp flex-1">
                        <label>{{ __('Email') }} :</label>
                        <input type="text" name="email" id="ticket-email" value="{{ $ticket->email }}">
                    </div>
                    <div class="" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ __('Save') }}">
                        <a href="#" id="save-email">
                            <svg width="22" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.0664 0.265625H1.93359C0.865262 0.265625 0 1.13626 0 2.19922V13.8008C0 14.8701 0.871621 15.7344 1.93359 15.7344H20.0664C21.1258 15.7344 22 14.8737 22 13.8008V2.19922C22 1.13815 21.1382 0.265625 20.0664 0.265625ZM19.7956 1.55469C19.4006 1.94764 12.602 8.7104 12.3673 8.9439C12.002 9.30913 11.5165 9.51022 11 9.51022C10.4835 9.51022 9.99797 9.30909 9.63153 8.94269C9.47366 8.78564 2.75013 2.09755 2.20438 1.55469H19.7956ZM1.28906 13.5384V2.4624L6.85945 8.00344L1.28906 13.5384ZM2.2052 14.4453L7.77339 8.91253L8.72124 9.85539C9.32993 10.4641 10.1392 10.7993 11 10.7993C11.8608 10.7993 12.6701 10.4641 13.2776 9.85659L14.2266 8.91253L19.7948 14.4453H2.2052ZM20.7109 13.5384L15.1406 8.00344L20.7109 2.4624V13.5384Z" fill="#888888" />
                            </svg>

                        </a>
                    </div>
                </li>
                <li class="d-flex align-items-center justify-content-between gap-2">
                    <div class="input-wrp flex-1">
                        <label>{{ __('Subject') }} :</label>
                        <input type="text" name="subject" id="ticket-subject" value="{{ $ticket->subject }}">
                    </div>
                    <div class="" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ __('Save') }}">
                        <a href="#" id="save-subject">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.4425 2.88241H15.4522V1.99201C15.4522 1.70394 15.1379 1.57301 14.8498 1.57301H13.3047C12.9381 0.525466 12.0215 0.00169566 10.974 0.00169566C9.93796 -0.0372525 8.99564 0.597998 8.6432 1.57301H7.12427C6.8362 1.57301 6.54812 1.70394 6.54812 1.99201V2.88241H4.55775C3.37828 2.89499 2.41335 3.82543 2.35791 5.00365V20.0096C2.35791 21.1619 3.40545 21.9999 4.55775 21.9999H17.4425C18.5948 21.9999 19.6423 21.1619 19.6423 20.0096V5.0037C19.5869 3.82543 18.622 2.89499 17.4425 2.88241ZM7.59561 2.62055H9.03599C9.28742 2.58987 9.48938 2.39859 9.5336 2.14916C9.68869 1.47376 10.2812 0.988934 10.974 0.970688C11.6603 0.99149 12.2439 1.47782 12.3881 2.14916C12.4351 2.40721 12.6504 2.60095 12.9119 2.62055H14.4047V4.71562H7.59561V2.62055ZM18.5948 20.0097C18.5948 20.5858 18.0186 20.9524 17.4425 20.9524H4.55775C3.9816 20.9524 3.40545 20.5858 3.40545 20.0097V5.0037C3.45888 4.40399 3.95574 3.94097 4.55775 3.92999H6.54807V5.26561C6.57574 5.55905 6.82988 5.77855 7.12422 5.76321H14.8498C15.1495 5.77961 15.4117 5.56311 15.4521 5.26561V3.92994H17.4424C18.0444 3.94097 18.5413 4.40394 18.5947 5.00365V20.0097H18.5948Z" fill="#888888" />
                                <path d="M8.98383 11.708C8.78739 11.5009 8.46127 11.4892 8.25053 11.6818L6.57446 13.2793L5.86738 12.546C5.67093 12.3389 5.34481 12.3273 5.13408 12.5198C4.93122 12.7324 4.93122 13.0667 5.13408 13.2793L6.20779 14.3792C6.30072 14.4833 6.43496 14.5408 6.57441 14.5363C6.71256 14.5344 6.84434 14.4779 6.94103 14.3792L8.98373 12.4413C9.18624 12.2555 9.19978 11.9407 9.01396 11.7382C9.00443 11.7277 8.99436 11.7176 8.98383 11.708Z" fill="#888888" />
                                <path d="M16.4997 12.834H10.4764C10.1871 12.834 9.95264 13.0685 9.95264 13.3578C9.95264 13.647 10.1871 13.8815 10.4764 13.8815H16.4997C16.789 13.8815 17.0235 13.647 17.0235 13.3578C17.0235 13.0685 16.789 12.834 16.4997 12.834Z" fill="#888888" />
                                <path d="M8.98383 7.51778C8.78739 7.31071 8.46127 7.29903 8.25053 7.49162L6.57446 9.08909L5.86738 8.3558C5.67093 8.14872 5.34481 8.13704 5.13408 8.32963C4.93122 8.54216 4.93122 8.87656 5.13408 9.08909L6.20779 10.189C6.30072 10.2931 6.43496 10.3506 6.57441 10.3462C6.71256 10.3442 6.84434 10.2877 6.94103 10.189L8.98373 8.25108C9.18624 8.06531 9.19978 7.75052 9.01396 7.54806C9.00443 7.53748 8.99436 7.52741 8.98383 7.51778Z" fill="#888888" />
                                <path d="M16.4997 8.64392H10.4764C10.1871 8.64392 9.95264 8.87841 9.95264 9.16769C9.95264 9.45697 10.1871 9.69146 10.4764 9.69146H16.4997C16.789 9.69146 17.0235 9.45697 17.0235 9.16769C17.0235 8.87841 16.789 8.64392 16.4997 8.64392Z" fill="#888888" />
                                <path d="M8.98383 15.898C8.78739 15.6909 8.46127 15.6793 8.25053 15.8718L6.57446 17.4693L5.86738 16.736C5.67093 16.529 5.34481 16.5173 5.13408 16.7099C4.93122 16.9224 4.93122 17.2568 5.13408 17.4693L6.20779 18.5692C6.30072 18.6733 6.43496 18.7309 6.57441 18.7264C6.71256 18.7244 6.84434 18.6679 6.94103 18.5692L8.98373 16.6313C9.18624 16.4455 9.19978 16.1307 9.01396 15.9283C9.00443 15.9178 8.99436 15.9077 8.98383 15.898Z" fill="#888888" />
                                <path d="M16.4997 17.0242H10.4764C10.1871 17.0242 9.95264 17.2587 9.95264 17.5479C9.95264 17.8372 10.1871 18.0717 10.4764 18.0717H16.4997C16.789 18.0717 17.0235 17.8372 17.0235 17.5479C17.0235 17.2587 16.789 17.0242 16.4997 17.0242Z" fill="#888888" />
                            </svg>
                        </a>
                    </div>
                </li>
            </ul>
            <ul class="card-category-info">
                <li>
                    <label>{{ __('Priority') }} :</label>

                        <div class="badge-wrp d-flex align-items-center gap-1 admin-edit-select " id="priority-select">
                            <select id="priority" class="form-select" name="priority"
                                data-url="{{ route('admin.ticket.priority.change', ['id' => isset($ticket) ? $ticket->id : '0']) }}"
                                required>
                                <option selected disabled>{{__('Select Priority')}}</option>

                                @foreach ($priorities as $priority)
                                <option value="{{ $priority->id }}" @if ($ticket->priority == $priority->id) selected
                                    @endif>{{ $priority->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                </li>
                <li>
                    <label>{{ __('Category') }} :</label>

                        <div class="badge-wrp d-flex align-items-center gap-1 admin-edit-select " id="category-select">
                            <select id="category" class="form-select" name="category"
                                data-url="{{ route('admin.ticket.category.change', ['id' => isset($ticket) ? $ticket->id : '0']) }}"
                                required>
                                <option selected disabled>{{__('Select Category')}}</option>

                                @foreach ($categoryTree as $category)
                                <option value="{{ $category['id'] }}" {{ $ticket->category_id == $category['id'] ? 'selected' : '' }}>
                                    {!! $category['name'] !!}
                                </option>
                                @endforeach
                            </select>
                        </div>

                </li>
                <li>
                    <label>{{ __('Assign Agent') }} :</label>
                    <div class="badge-wrp assign-select-wrp d-flex align-items-center gap-1">
                        @if (moduleIsActive('OutOfOffice'))
                        @stack('is_available_edit')
                        @else
                        <select id="agents" class="form-select" name="agent_id"
                            data-url="{{ route('admin.ticket.assign.change', ['id' => isset($ticket) ? $ticket->id : '0']) }}"
                            required>
                            <option selected disabled value="">{{ __('Select Agent') }}</option>
                            @foreach ($users as $agent)
                            <option value="{{ $agent->id }}" {{ $ticket->is_assign == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }}
                            </option>
                            @endforeach
                        </select>
                        @endif
                    </div>
                </li>
            </ul>
            <ul class="tag-wrp mb-0">
                @if (count($customFields) > 0)
                    <li class="d-flex align-items-center gap-2 justify-content-between">
                        <span> {{ __('Custom Field') }} </span>
                        <a href="#" title="{{ __('View Custom Field') }}"
                            data-ajax-popup="true"
                            data-title="{{ __('Custom Field') }}"
                            data-url="{{ route('admin.ticketcustomfield.show', $ticket->id) }}" data-size="lg">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_1107_6629)">
                                    <path d="M8 16C7.7928 16 7.59409 15.9177 7.44757 15.7712C7.30106 15.6247 7.21875 15.426 7.21875 15.2188V0.78125C7.21875 0.57405 7.30106 0.375336 7.44757 0.228823C7.59409 0.08231 7.7928 0 8 0C8.2072 0 8.40591 0.08231 8.55243 0.228823C8.69894 0.375336 8.78125 0.57405 8.78125 0.78125V15.2188C8.78125 15.426 8.69894 15.6247 8.55243 15.7712C8.40591 15.9177 8.2072 16 8 16Z" fill="#0CAF60" />
                                    <path d="M15.2188 8.78125H0.78125C0.57405 8.78125 0.375336 8.69894 0.228823 8.55243C0.08231 8.40591 0 8.2072 0 8C0 7.7928 0.08231 7.59409 0.228823 7.44757C0.375336 7.30106 0.57405 7.21875 0.78125 7.21875H15.2188C15.426 7.21875 15.6247 7.30106 15.7712 7.44757C15.9177 7.59409 16 7.7928 16 8C16 8.2072 15.9177 8.40591 15.7712 8.55243C15.6247 8.69894 15.426 8.78125 15.2188 8.78125Z" fill="#0CAF60" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_1107_6629">
                                        <rect width="16" height="16" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </a>
                    </li>  
                @endif
                {{-- tags addon --}}
                @stack('tags-popup')
                {{-- end --}}
                <li class="d-flex align-items-center gap-2 justify-content-between">
                    <span> {{ __('Private Note : ') }} </span>
                    <a href="#" title="{{ __('Private Note') }}"
                    data-ajax-popup="true"
                    data-title="{{ __('Private Note') }}"
                    data-url="{{ route('admin.ticket.note',['ticketId' => $ticket->id]) }}" data-size="lg">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_1107_6629)">
                                <path
                                    d="M8 16C7.7928 16 7.59409 15.9177 7.44757 15.7712C7.30106 15.6247 7.21875 15.426 7.21875 15.2188V0.78125C7.21875 0.57405 7.30106 0.375336 7.44757 0.228823C7.59409 0.08231 7.7928 0 8 0C8.2072 0 8.40591 0.08231 8.55243 0.228823C8.69894 0.375336 8.78125 0.57405 8.78125 0.78125V15.2188C8.78125 15.426 8.69894 15.6247 8.55243 15.7712C8.40591 15.9177 8.2072 16 8 16Z"
                                    fill="#0CAF60" />
                                <path
                                    d="M15.2188 8.78125H0.78125C0.57405 8.78125 0.375336 8.69894 0.228823 8.55243C0.08231 8.40591 0 8.2072 0 8C0 7.7928 0.08231 7.59409 0.228823 7.44757C0.375336 7.30106 0.57405 7.21875 0.78125 7.21875H15.2188C15.426 7.21875 15.6247 7.30106 15.7712 7.44757C15.9177 7.59409 16 7.7928 16 8C16 8.2072 15.9177 8.40591 15.7712 8.55243C15.6247 8.69894 15.426 8.78125 15.2188 8.78125Z"
                                    fill="#0CAF60" />
                            </g>
                            <defs>
                                <clipPath id="clip0_1107_6629">
                                    <rect width="16" height="16" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>



<script>
    function setDescription(description) {
        $('#reply_description').summernote('code', description);
    }
</script>
