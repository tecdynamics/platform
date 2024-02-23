<x-core-setting::section
    :title="trans($data['name'])"
    :description="trans($data['description'])"
>
    <div class="table-wrap">
        <table class="table product-list ws-nm">
            <thead>
                <tr>
                    <th class="border-none-b">{{ trans('core/setting::setting.template') }}</th>
                    <th class="border-none-b"> {{ trans('core/setting::setting.description') }} </th>
                    @if ($type !== 'core')
                        <th class="border-none-b text-center"> {{ trans('core/setting::setting.enable') }}</th>
                    @else
                        <th>&nbsp;</th>
                    @endif
                </tr>
            </thead>
            <tbody>
            <?php
            $activeLanguages = \Language::getSupportedLocales();
            $defaultLang = \Language::getDefaultLocale();
              ?>
            @foreach ($activeLanguages as $lang => $lang_data)
                <tr>
                    <td colspan="3" class="bg-light cursor-pointer lang_name_{{md5($data['name'])}}_{{$lang_data['lang_code']}}"
                        onclick="(function (){
                              let clicked ='lang_name_{{md5($data['name'])}}_{{$lang_data['lang_code']}}';
                              let selected ='lang_section_{{md5($data['name'])}}_{{$lang_data['lang_code']}}';
                              let selectedarrow ='arrow-item_{{md5($data['name'])}}_{{$lang_data['lang_code']}}';
                              jQuery('tr[class^=\'lang_section_\']').hide();
                              jQuery('i[class^=\'arrow-item_\']').removeClass('fa-chevron-up').addClass('fa-chevron-down')
                              if(!jQuery('.'+clicked).hasClass('selected')){
                              jQuery('.'+selected).slideDown('slow')
                              jQuery('.'+clicked).addClass('selected');
                              jQuery('.'+selectedarrow).addClass('fa-chevron-up').removeClass('fa-chevron-down');
                              }else{
                                  jQuery('.'+clicked).removeClass('selected')
                              }
                          })();return false;  ">
                        <div class="w-100 d-flex justify-content-between p-2">
                            <span>{{ $lang_data['lang_name'] }}</span>
                            <span><i class="arrow-item_{{md5($data['name'])}}_{{$lang_data['lang_code']}} fa fa-chevron-down"></i></span>
                        </div>
                    </td>
                </tr>
                @foreach ($data['templates'] as $key => $template)
                    <tr class="lang_section_{{md5($data['name'])}}_{{$lang_data['lang_code']}}" style="display: none;">
                        <td>
                            <a class="hover-underline a-detail-template"
                                href="{{ route('setting.email.template.edit', [$type, $module, $key,$lang]) }}"
                            >{{ trans($template['title']) }} </a>
                        </td>
                        <td>{{ trans($template['description']) }}</td>
                        <td class="text-center template-setting-on-off">
                            @if ($type !== 'core' && Arr::get($template, 'can_off', false))
                                <div class="form-group mb-3">
                                    {!! Form::onOff(
                                        get_setting_email_status_key($type, $module, $key),
                                        get_setting_email_status($type, $module, $key) == 1,
                                        ['id'=>'email-config-status-btn'.$lang_data['lang_code'].rand(100,10000),
                                        'data-key' => 'email-config-status-btn',
                                        'data-change-url' => route('setting.email.status.change')],
                                    ) !!}
                                </div>
                            @elseif ($type !== 'core')
                                <div class="form-group mb-3">
                                    {!! Form::onOff(get_setting_email_status_key($type, $module, $key), 1, ['id'=>'email-config-status-btn'.$lang_data['lang_code'].rand(100,10000),
                               'disabled' => true, 'readonly' => true]) !!}
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach

            @endforeach
            </tbody>
        </table>
    </div>
</x-core-setting::section>
