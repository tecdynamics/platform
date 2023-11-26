<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ page_title()->getTitle() }}</title>

    <meta name="robots" content="noindex,follow"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (setting('admin_logo') || config('core.base.general.logo'))
        <meta property="og:image" content="{{ setting('admin_logo') ? RvMedia::getImageUrl(setting('admin_logo')) : url(config('core.base.general.logo')) }}">
    @endif
    <meta name="description" content="{{ strip_tags(trans('core/base::layouts.copyright', ['year' => now()->format('Y'), 'company' => setting('admin_title', config('core.base.general.base_name')), 'version' => get_cms_version()])) }}">
    <meta property="og:description" content="{{ strip_tags(trans('core/base::layouts.copyright', ['year' => now()->format('Y'), 'company' => setting('admin_title', config('core.base.general.base_name')), 'version' => get_cms_version()])) }}">

    @if (setting('admin_favicon') || config('core.base.general.favicon'))
        <link rel="icon shortcut" href="{{ setting('admin_favicon') ? RvMedia::getImageUrl(setting('admin_favicon'), 'thumb') : url(config('core.base.general.favicon')) }}">
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    {!! Assets::renderHeader(['core']) !!}

    @if (BaseHelper::adminLanguageDirection() == 'rtl')
        <link rel="stylesheet" href="{{ asset('vendor/core/core/base/css/rtl.css') }}">

    @endif
    @if (env('APP_ENV')=='local')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.js" crossorigin="anonymous"
                referrerpolicy="no-referrer"></script>
    @else
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.js" crossorigin="anonymous"
                referrerpolicy="no-referrer"></script>
{{--        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.min.js" crossorigin="anonymous"--}}
{{--                referrerpolicy="no-referrer"></script>--}}
    @endif
    @yield('head')

    @stack('header')
    <style>
        .ck-editor__editable_inline {
            min-height: 150px;
          max-height: 160em!important;
        }
    </style>
</head>
<body @if (BaseHelper::adminLanguageDirection() == 'rtl') dir="rtl" @endif class="@yield('body-class', 'page-sidebar-closed-hide-logo page-content-white page-container-bg-solid') {{ session()->get('sidebar-menu-toggle') ? 'page-sidebar-closed' : '' }}" style="@yield('body-style')">
    {!! apply_filters(BASE_FILTER_HEADER_LAYOUT_TEMPLATE, null) !!}
    @yield('page')

    @include('core/base::elements.common')

    {!! Assets::renderFooter() !!}

    @yield('javascript')

    <div id="stack-footer">
        @stack('footer')
    </div>

    {!! apply_filters(BASE_FILTER_FOOTER_LAYOUT_TEMPLATE, null) !!}
<script>
    jQuery.fn.cleanWhitespace = function() {
        console.log(this.contents())
        this.contents().filter(
            function() { return (this.nodeType == 3 && !/\S/.test(this.nodeValue)); })
            .remove();
        return this;
    }
    $(document).ready(() => {
        jQuery('body').on('click', function (e) {
              jQuery(e.currentTarget).find('textarea.raw-html-embed__source').css({height: '10em'})
              jQuery(e.currentTarget).find('div.ck-widget_selected')
                  .find('textarea.raw-html-embed__source').css({height: '40em'}).cleanWhitespace();
          })
    });
</script>
</body>
</html>
