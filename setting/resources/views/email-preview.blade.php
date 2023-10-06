 @php
      $site_title = setting('admin_title') ?: config('app.name');
      $site_url    = url('');
      $site_logo  =  setting('admin_logo') ? RvMedia::getImageUrl(setting('admin_logo')) : url(config('core.base.general.logo'));
      $date_time  = now()->toDateTimeString();
      $date_year = now()->format('Y');
      $site_admin_email = get_admin_email()->first();
      $header = str_replace(['{{ site_url }}','{{ site_logo }}','{{ site_title }}','{{ date_time }}'],[$site_url,$site_logo,$site_title,$date_time],
      apply_filters(BASE_FILTER_EMAIL_TEMPLATE_HEADER, get_setting_email_template_content('core', 'base', 'header')));
       $footer= str_replace(['{{ site_title }}','{{ date_year }}','{{ date_time }}'],[$site_title,$date_year,$date_time],
apply_filters(BASE_FILTER_EMAIL_TEMPLATE_FOOTER, get_setting_email_template_content('core', 'base', 'footer')));
       $content= str_replace(['{{ header }}','{{ footer }}'],'',$emailContent);
 @endphp
 <div class="col-12 mb-4 p-2 ">
     <footer class="d-flex justify-content-between"> <small class="text-sm">Note: The Following Preview is not 100% accurate because of the CMS style conflicts</small>
 <button type="button" class="btn btn-outline-primary btn-sm preview-email" >Preview</button></footer>
 </div>
 {!! $header !!}
<div id="display-area" >{!! $content !!}</div>
 {!! $footer !!}
