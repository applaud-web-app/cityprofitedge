@php $breadCrumb = getContent('bread_crumb.content', true); @endphp

<!-- inner hero section start -->
<section class="inner-hero bg_img" style="background-image: url('{{ getImage('assets/images/frontend/bread_crumb/' .@$breadCrumb->data_values->image, '1920x510') }}');">
    <div class="container">
        <div class="row justify-content-center">
            @if($user)
                <div class="col-lg-12 col-12">
                    <h5 class="text-white text-end mb-0">Welcome to <span class="text--base">{{$user->firstname.' '.$user->lastname}}</span> </h5>
                </div>
            @endif
            <div class="col-lg-6 text-center">
                <h2 class="title text-white">{{ __($pageTitle) }}</h2>
                <ul class="page-breadcrumb justify-content-center">
                    <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                    <li>{{ __($pageTitle) }}</li>
                </ul>
            </div>
          
        </div>
    </div>
</section>
<!-- inner hero section end -->