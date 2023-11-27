@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    {!! Form::open(['route' => ['setting.email.template.store']]) !!}
    <div class="max-width-1200">
        <div class="flexbox-annotated-section">
            <div class="flexbox-annotated-section-annotation">
                <div class="annotated-section-title pd-all-20">
                    <h2>{{ trans('core/setting::setting.email.title') }}</h2>
                </div>
                <div class="annotated-section-description pd-all-20 p-none-t">
                    <p class="color-note">
                        {!! clean(trans('core/setting::setting.email.description')) !!}
                    </p>
                    <div class="available-variable">
                        @foreach(EmailHandler::getVariables('core') as $coreKey => $coreVariable)
                            <p><span class="text-danger">{{ $coreKey }}</span>: {{ $coreVariable }}</p>
                        @endforeach
                        @foreach(EmailHandler::getVariables($pluginData['name']) as $moduleKey => $moduleVariable)
                            <p><span class="text-danger">{{ $moduleKey }}</span>: {{ trans($moduleVariable) }}</p>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flexbox-annotated-section-content">
                <div class="wrapper-content pd-all-20 email-template-edit-wrap">
                    @if ($emailSubject)
                        <div class="form-group mb-3">
                            <label class="text-title-field"
                                   for="email_subject">
                                {{ trans('core/setting::setting.email.subject') }}
                            </label>
                            <input type="hidden" name="email_subject_key"
                                   value="{{ get_setting_email_subject_key($pluginData['type'], $pluginData['name'], $pluginData['template_file'],$template_lang) }}">
                            <input data-counter="300" type="text" class="next-input"
                                   name="email_subject"
                                   id="email_subject"
                                   value="{{ $emailSubject }}">
                        </div>
                    @endif
                    <div class="form-group mb-3">
                        <input type="hidden" name="template_path" value="{{ get_setting_email_template_path($pluginData['name'], $pluginData['template_file'],$template_lang) }}">
                        <label class="text-title-field"
                               for="email_content">{{ trans('core/setting::setting.email.content') }}</label>
                        <textarea id="mail-template-editor" name="email_content" class="form-control mail-template-editor" style="overflow-y:scroll; height: 500px;">{{ $emailContent }}</textarea>
                    </div>
                    </div>
                        <div class="wrapper-content mt-2 pd-all-20">
                        @include('core/setting::email-preview')
                </div>
            </div>

        </div>

        <div class="flexbox-annotated-section" style="border: none">
            <div class="flexbox-annotated-section-annotation">
                &nbsp;
            </div>
            <div class="flexbox-annotated-section-content">
                <a href="{{ route('settings.email') }}" class="btn btn-secondary">{{ trans('core/setting::setting.email.back') }}</a>
                <a class="btn btn-warning btn-trigger-reset-to-default" data-target="{{ route('setting.email.template.reset-to-default') }}">{{ trans('core/setting::setting.email.reset_to_default') }}</a>
                <button class="btn btn-info" type="submit" name="submit">{{ trans('core/setting::setting.save_settings') }}</button>
            </div>
        </div>
    </div>

    <input type="hidden" name="template_lang" value="{{$template_lang}}">
    {!! Form::close() !!}


    {!! Form::modalAction('reset-template-to-default-modal', trans('core/setting::setting.email.confirm_reset'), 'info', trans('core/setting::setting.email.confirm_message'), 'reset-template-to-default-button', trans('core/setting::setting.email.continue')) !!}
@endsection



<?php
//if ( ! defined( 'ABSPATH' ) ) {
//    exit; // Exit if accessed directly
//}

class WebExpert_Skroutz_Egnine {
    private $xml;
    private $filename;
    private $tmp_filename;
    private $options;
    private $webexpert_skroutz_xml_stats_simple_products;
    private $webexpert_skroutz_xml_stats_grouped_products;
    private $webexpert_skroutz_xml_stats_variations;
    private $webexpert_skroutz_xml_stats_percentage;

    public function __construct() {
        global $blog_id;
        if (defined('WP_ALLOW_MULTISITE') && isset($blog_id)) {
            $this->filename = "skroutz_$blog_id.xml";
            $this->tmp_filename = "skroutz_".current_time('timestamp')."$blog_id.xml";
        }else {
            $this->filename = "skroutz.xml";
            $this->tmp_filename="skroutz_".current_time('timestamp').".xml";
        }

        $this->options = array(
            'manufacturer'       => get_option('we_skroutz_xml_manufacturer',[]),
            'colour'             => get_option('we_skroutz_xml_colour',[]),
            'size'               => get_option('we_skroutz_xml_size',[]),
            'xml_availability'   => esc_attr(get_option('we_skroutz_xml_availability')),
            'xml_preavailability' => esc_attr(get_option('we_skroutz_xml_preavailability')),
            'xml_noavailability' => esc_attr(get_option('we_skroutz_xml_noavailability')),
            'flat_rate'          => esc_attr(get_option('we_skroutz_xml_flat_rate',0)),
            'flat_rate_free'     => esc_attr( get_option('we_skroutz_xml_flat_rate_free',0)),
            'flat_rate_cost_per_kg'     => esc_attr( get_option('we_skroutz_xml_flat_rate_cost_per_kg',0)),
            'ean' => get_option('we_skroutz_xml_custom_ean','_barcode'),
            'description' => get_option('we_skroutz_xml_desc_field','short'),
            'custom_id' => get_option('we_skroutz_xml_custom_id',null),
            'variation_title'=>get_option('we_skroutz_xml_variation_title','colour_only'),
            'custom_weight'=>get_option('we_skroutz_xml_custom_weight'),
            'max_page_size'=>get_option('we_skroutz_xml_max_page_size',100),
        );
    }

    public function prepare_args() {
        $args = array('post_type' => 'product', 'posts_per_page' => -1, 'post_status' => 'publish','fields'=>'ids');

        $excluded_terms_categories = get_option('we_skroutz_xml_categories_not_to_list');
        $excluded_terms_tags = get_option('we_skroutz_xml_tags_not_to_list');
        $we_skroutz_xml_attributes = get_option('we_skroutz_xml_attributes_not_to_list');
        if ((is_array($excluded_terms_categories) && sizeof($excluded_terms_categories) > 0) || (is_array($excluded_terms_tags) && sizeof($excluded_terms_tags) > 0) || is_array($we_skroutz_xml_attributes) && sizeof($we_skroutz_xml_attributes) > 0) {
            $args['tax_query'] = array("relation" => "AND");
        }

        if ($excluded_terms_categories > 0) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $excluded_terms_categories,
                'operator' => (get_option('we_skroutz_xml_categories_not_to_list_invert',0) ? 'IN' : 'NOT IN')
            );
        }

        if ($excluded_terms_tags > 0) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_tag',
                'field'    => 'term_id',
                'terms'    => $excluded_terms_tags,
                'operator' => (get_option('we_skroutz_xml_tags_not_to_list_invert',0) ? 'IN' : 'NOT IN')
            );
        }

        if ($we_skroutz_xml_attributes) {
            $temp_attributes=[];
            if (sizeof($we_skroutz_xml_attributes)>1) {
                $temp_attributes['relation']=(get_option('we_skroutz_xml_attributes_not_to_list_invert',0) ? 'OR' : 'AND');
            }
            foreach ($we_skroutz_xml_attributes as $attribute_filter) {
                $expl = explode("__", $attribute_filter);
                $temp_attributes[] = array(
                    'taxonomy' => $expl[1],
                    'field'    => 'term_id',
                    'terms'    => $expl[0],
                    'operator' => (get_option('we_skroutz_xml_attributes_not_to_list_invert',0) ? 'IN' : 'NOT IN')
                );
            }
            $args['tax_query'][]=$temp_attributes;
        }

        if (!empty(apply_filters('webexpert_skroutz_xml_hide_certain_product_ids',[]))) {
            $args['post__not_in'][]=apply_filters('webexpert_skroutz_xml_hide_certain_product_ids',[]);
        }

        return apply_filters( 'webexpert_skroutz_xml_custom_args', $args);
    }

    public function run() {
        global $blog_id;
        $upload_dir = wp_upload_dir();
        $skroutz_xml_dir = $upload_dir['basedir'] . '/webexpert-skroutz-xml';
        $skroutz_xml_url = $upload_dir['baseurl'] . '/webexpert-skroutz-xml';
        wp_mkdir_p($skroutz_xml_dir);

        $this->xml = new XMLWriter();
        $this->xml->openMemory();
        $this->xml->startDocument('1.0', 'UTF-8');
        $this->xml->startElement('mywebstore');
        $this->xml->writeElement('created_at',date_i18n("Y-m-d H:i:s"));
        $this->xml->startElement('products');
        $loop = new WP_Query($this->prepare_args());
        if ($loop->last_error) :
            print_r($loop->last_error);
            exit;
        endif;

        $count=0;
        if (file_exists($skroutz_xml_dir.'/'.$this->filename)) {
            unlink($skroutz_xml_dir.'/'.$this->filename);
        }
        update_option('webexpert_skroutz_xml_stats_simple_products','0');
        update_option('webexpert_skroutz_xml_stats_grouped_products','0');
        update_option('webexpert_skroutz_xml_stats_variations','0');

        $this->webexpert_skroutz_xml_stats_simple_products=0;
        $this->webexpert_skroutz_xml_stats_grouped_products=0;
        $this->webexpert_skroutz_xml_stats_variations=0;
        $this->webexpert_skroutz_xml_stats_percentage=0;
        while ($loop->have_posts()) : $loop->the_post();
            $this->generate_data(get_the_ID());
            if (0 == $count % $this->options['max_page_size']) {
                file_put_contents($skroutz_xml_dir.'/'.$this->tmp_filename, $this->xml->flush(true), FILE_APPEND);
                update_option('webexpert_skroutz_xml_stats_simple_products',$this->webexpert_skroutz_xml_stats_simple_products);
                update_option('webexpert_skroutz_xml_stats_grouped_products',$this->webexpert_skroutz_xml_stats_grouped_products);
                update_option('webexpert_skroutz_xml_stats_variations',$this->webexpert_skroutz_xml_stats_variations);
                update_option('webexpert_skroutz_xml_stats_percentage',round($this->options['max_page_size'] / $loop->post_count,2) * 100);
            }
            $count++;
        endwhile;
        $this->xml->endElement();
        $this->xml->endElement();

        file_put_contents($skroutz_xml_dir.'/'.$this->tmp_filename, $this->xml->flush(true), FILE_APPEND);

        $this->webexpert_skroutz_xml_stats_percentage=100;
        update_option('webexpert_skroutz_xml_stats_simple_products',$this->webexpert_skroutz_xml_stats_simple_products);
        update_option('webexpert_skroutz_xml_stats_grouped_products',$this->webexpert_skroutz_xml_stats_grouped_products);
        update_option('webexpert_skroutz_xml_stats_variations',$this->webexpert_skroutz_xml_stats_variations);
        update_option('webexpert_skroutz_xml_stats_percentage',$this->webexpert_skroutz_xml_stats_percentage);

        $doc = new DOMDocument;
        if (@$doc->load($skroutz_xml_dir.'/'.$this->tmp_filename) === false) {
            set_transient('webexpert-skroutz-xml-errors', __('XML could not be generated or copied.', 'webexpert-skroutz-xml-feed') . '. <a target="_blank" href="' . $skroutz_xml_url.'/'.$this->tmp_filename . '">' . __('View XML', 'webexpert-skroutz-xml-feed') . '</a>');
        }else {
            copy($skroutz_xml_dir.'/'.$this->tmp_filename,$skroutz_xml_dir.'/'.$this->filename);
            set_transient('webexpert-skroutz-xml-success', __('XML Data Feed was generated successfully.', 'webexpert-skroutz-xml-feed') . '. <a target="_blank" href="' . $skroutz_xml_url.'/'.$this->filename . '">' . __('View XML', 'webexpert-skroutz-xml-feed') . '</a>');
            unlink($skroutz_xml_dir.'/'.$this->tmp_filename);
        }

        $filename="skroutz.xml";
        if (defined('WP_ALLOW_MULTISITE') && isset($blog_id))
            $filename="skroutz_$blog_id.xml";
        $old_skroutz_xml_path=WE_XML_SKROUTZ_PLUGIN_PATH.'/'.$filename;

        if (!is_link($old_skroutz_xml_path)) {
            unlink($old_skroutz_xml_path);
            if (function_exists('symlink')) {
                symlink($skroutz_xml_dir.'/'.$this->filename, $old_skroutz_xml_path);
            }
        }

        update_option('we_skroutz_xml_lastrun', date_i18n('d-m-Y H:i:s'));
        set_transient('webexpert-skroutz-xml-success', __('XML Data Feed was generated successfully.', 'webexpert-skroutz-xml-feed') . '. <a target="_blank" href="' . $skroutz_xml_url.'/'.$this->filename . '">' . __('View XML', 'webexpert-skroutz-xml-feed') . '</a>');

        wp_reset_query();
    }

    public function generate_data($product_id) {
        $product=wc_get_product($product_id);

        if(get_post_meta($product_id,'_lang',true) !='EL'){
            return '';
        }

        if ($product && (float)$product->get_price() > 0) {
            if ($product->is_type('simple')) {
                $exported = $this->generate_product($product);
                if ($exported)
                    $this->webexpert_skroutz_xml_stats_simple_products++;
            } elseif ($product->is_type('variable')) {
                $variable_variations = $product->get_variation_attributes();
                if (isset($this->options['colour']) && !empty($this->options['colour'])) {
                    $color_variables=[];
                    foreach ($this->options['colour'] as $t_color) {
                        if (isset($variable_variations["pa_" . $t_color]) && sizeof($variable_variations["pa_" . $t_color]) > 0) {
                            array_push($color_variables,$t_color);
                        }
                    }
                    $sizes=[];
                    if (sizeof($color_variables)>0) {
                        $available_variations = $product->get_available_variations('objects');
                        $prePopulate = array();
                        $combinedColorIsPrinted = array();
                        $quantities=[];
                        foreach ($available_variations as $variation) {
                            if ($this->check_visibility($variation)==false) {
                                continue;
                            }

                            $grouping = [];
                            foreach ($color_variables as $color) {
                                $term = get_term_by('name', $variation->get_attribute($color), 'pa_' . $color);
                                if (!is_wp_error($term) && $term !== false && !in_array($term->term_id, $grouping)) {
                                    array_push($grouping, $term->term_id);

                                    if (!array_key_exists($variation->get_attribute($color),$quantities)) {
                                        $quantities[$variation->get_attribute($color)]=0;
                                    }
                                    $quantities[$variation->get_attribute($color)] += $variation->get_stock_quantity();
                                }
                            }

                            $unique_group_key = implode('-', $grouping);
                            if (!array_key_exists($unique_group_key, $prePopulate)) {
                                $prePopulate[$unique_group_key] = array();
                            }
                            if (isset($this->options['size']) && is_array($this->options['size'])) {
                                foreach ($this->options['size'] as $size) {
                                    if ($variation->get_attribute($size)) {
                                        $clean_size = apply_filters('webexpert_custom_size_replace',$variation->get_attribute($size),$variation);
                                        if (!in_array($clean_size, $prePopulate[$unique_group_key])) {
                                            array_push($prePopulate[$unique_group_key], $clean_size);
                                        }
                                    }
                                }
                            }
                            $sizes = $prePopulate;
                        }

                        foreach ($available_variations as $variation) {
                            if ($this->check_visibility($variation)==false) {
                                continue;
                            }

                            $grouping=[];
                            $ignoreGrouping=false;
                            foreach ($color_variables as $color) {
                                $term = get_term_by('name', $variation->get_attribute($color), 'pa_' . $color);
                                if (!is_wp_error($term) && $term !== false && !in_array($term->term_id,$grouping)) {
                                    array_push($grouping, $term->term_id);
                                    if (get_option("we_skroutz_xml_disable_grouping-".wc_attribute_taxonomy_id_by_name($term->taxonomy),0)=="1") {
                                        $ignoreGrouping=true;
                                    }
                                }
                            }

                            $unique_group_key=implode('-',$grouping);

                            if ($ignoreGrouping==true || !in_array($unique_group_key,$combinedColorIsPrinted)) {
                                $exported = $this->generate_product($variation,['id'=>$variation->get_parent_id() ."-".(implode("-",$grouping)),'size'=>$sizes,'quantity'=>$quantities]);
                                if ($exported)
                                    $this->webexpert_skroutz_xml_stats_grouped_products++;

                                array_push($combinedColorIsPrinted,$unique_group_key);
                            }
                        }
                    } else {
                        if (isset($this->options['size']) && !empty($this->options['size'])) {
                            $size_exists=false;
                            $size_counter=0;
                            foreach ($this->options['size'] as $size) {
                                if (isset($variable_variations["pa_" . $size]) && sizeof($variable_variations["pa_" . $size]) > 0) {
                                    $size_exists=true;
                                    $size_counter++;
                                    break;
                                }
                            }

                            if (get_option('we_skroutz_xml_do_not_group_single_sized_variations',"no")=="yes" && $size_counter==1) {
                                $size_exists=false;
                            }

                            if ($size_exists) {
                                $exported = $this->generate_product($product);
                                if ($exported)
                                    $this->webexpert_skroutz_xml_stats_grouped_products++;
                            }else {
                                $variations = $product->get_available_variations('objects');
                                foreach ($variations as $variation) {
                                    $exported = $this->generate_product($variation);
                                    if ($exported)
                                        $this->webexpert_skroutz_xml_stats_variations++;
                                }
                            }
                        }else{
                            $variations = $product->get_available_variations('objects');
                            foreach ($variations as $variation) {
                                $exported = $this->generate_product($variation);
                                if ($exported)
                                    $this->webexpert_skroutz_xml_stats_variations++;
                            }
                        }
                    }
                }else {
                    if (isset($this->options['size']) && !empty($this->options['size'])) {
                        $size_exists = false;
                        foreach ($this->options['size'] as $size) {
                            if (isset($variable_variations["pa_" . $size]) && sizeof($variable_variations["pa_" . $size]) > 0) {
                                $size_exists = true;
                                break;
                            }
                        }

                        if ($size_exists) {
                            $exported = $this->generate_product($product);
                            if ($exported)
                                $this->webexpert_skroutz_xml_stats_grouped_products++;
                        } else {
                            $variations = $product->get_available_variations('objects');
                            foreach ($variations as $variation) {
                                $exported = $this->generate_product($variation);
                                if ($exported)
                                    $this->webexpert_skroutz_xml_stats_variations++;
                            }
                        }
                    }else {
                        $variations = $product->get_available_variations('objects');
                        foreach ($variations as $variation) {
                            $exported = $this->generate_product($variation);
                            if ($exported)
                                $this->webexpert_skroutz_xml_stats_variations++;
                        }
                    }
                }
            }
        }
    }

    public function is_on_backorder($product) {
        if ($product->get_manage_stock()==false) {
            return $product->get_stock_status()!=='instock' && ($product->is_on_backorder() || $product->backorders_allowed() || ($product->is_type('variable') && $product->child_is_on_backorder()));
        }else {
            return $product->get_stock_quantity()<=0 && ($product->is_on_backorder() || $product->backorders_allowed() || ($product->is_type('variable') && $product->child_is_on_backorder()));
        }
    }

    public function generate_product($product,$overrides=[]) {
        if ($this->check_visibility($product)==false) {
            return false;
        }

        $this->xml->startElement('product');
        $this->get_ids($product,$overrides);
        $this->get_name($product);
        $this->get_link($product);
        $this->get_image($product);
        $this->get_gallery($product);
        $this->get_categories($product);
        $this->get_prices($product);
        $this->get_vat_rates($product);
        $this->get_manufacturer($product);
        $this->get_ean($product);
        $this->get_mpn($product);
        $this->get_stock($product,$overrides);
        $this->get_availability($product);
        $this->get_shipping_costs($product);
        $this->get_colours($product);
        $this->get_sizes($product,$overrides);
        $this->get_description($product);
        $this->get_weight($product);

        do_action('webexpert_skroutz_xml_additional_fields',$product, $this->xml, $this->options);

        $this->xml->endElement();

        return true;
    }

    public function get_weight($product) {
        $weight = apply_filters('webexpert_skroutz_xml_custom_weight', !empty($product->get_meta($this->options['custom_weight'])) ? wc_get_weight(floatval($product->get_meta($this->options['custom_weight'])), 'g') : wc_get_weight($product->get_weight(), 'g'), $product);
        if ($weight) {
            $this->xml->writeElement('weight', $weight);
        }
    }

    public function get_ids($product,$overrides=[]) {
        if (!empty($overrides['id'])) {
            $product_id = $overrides['id'];
        }else {
            $product_id=$product->get_id();
            if ($this->options['custom_id'] && !empty($this->options['custom_id'])) {
                $product_id = !empty($product->get_meta($this->options['custom_id'])) ? $product->get_meta($this->options['custom_id']) : $product->get_id();
            }
        }
        $this->xml->writeElement('id',apply_filters('webexpert_skroutz_xml_custom_id',$product_id,$product));
    }

    public function get_sizes($product,$overrides=[]) {
        if (!empty($overrides['size'])) {
            $grouping=[];
            foreach ($this->options['colour'] as $color) {
                $term = get_term_by('name', $product->get_attribute($color), 'pa_' . $color);
                if (!is_wp_error($term) && $term !== false) {
                    array_push($grouping, $term->term_id);
                }
            }
            $unique_group_key=implode('-',$grouping);
            $size = implode(",", $overrides['size'][$unique_group_key]);
            if (!empty($size)) {
                $this->xml->startElement('size');
                $this->xml->writeCData($size);
                $this->xml->endElement();
            }
        }else {
            if ($product->is_type('simple')) {
                $size = apply_filters('webexpert_skroutz_xml_custom_size',$this->get_tax_or_attribute($product,'size'),$product);
                if (!empty($size)) {
                    $this->xml->startElement('size');
                    $this->xml->writeCData($size);
                    $this->xml->endElement();
                }
            }elseif ($product->is_type('variable')) {
                foreach ($this->options['size'] as $size_attribute) {
                    $available_variations = $product->get_available_variations('objects');
                    foreach ($available_variations as $variation) {
                        if ($this->check_visibility($variation) !== false) {
                            $variation_attributes = $variation->get_variation_attributes();
                            if (array_key_exists("attribute_pa_$size_attribute", $variation_attributes)) {
                                $attr_slug = $variation_attributes["attribute_pa_$size_attribute"];
                                $term_obj = get_term_by('slug', $attr_slug, "pa_" . $size_attribute);
                                if ($term_obj && !is_wp_error($term_obj))
                                    $attr_name[] = $term_obj->name;
                            }
                        }
                    }
                }
                if (!empty($attr_name)) {
                    $this->xml->startElement('size');
                    $this->xml->writeCData(implode(', ', array_unique($attr_name)));
                    $this->xml->endElement();
                }
            }elseif ($product->is_type('variation')) {
                $sizes = array();
                foreach ($this->options['size'] as $size) {
                    array_push($sizes,$product->get_attribute($size));
                }
                if (!empty($sizes)) {
                    $this->xml->startElement('size');
                    $this->xml->writeCData(implode(",", array_unique($sizes)));
                    $this->xml->endElement();
                }
            }
        }
    }

    public function get_description($product) {
        if ($product->is_type('variation')) {
            $_parent_product = wc_get_product($product->get_parent_id());
            $description=wp_filter_nohtml_kses($this->options['description']=='short' ? $_parent_product->get_short_description() : $_parent_product->get_description());
        }else {
            $description=wp_filter_nohtml_kses($this->options['description']=='short' ? $product->get_short_description() : $product->get_description());
        }

        if (apply_filters('webexpert_skroutz_xml_custom_description',$description,$product)) {
            $this->xml->startElement('description');
            $this->xml->writeCData(esc_html(apply_filters('webexpert_skroutz_xml_custom_description',$description,$product)));
            $this->xml->endElement();
        }
    }

    public function get_colours($product) {
        if ($product->is_type('variation')) {
            $colours = array();
            foreach ($this->options['colour'] as $colour) {
                array_push($colours,$product->get_attribute($colour));
            }
            if (!empty($colours)) {
                $this->xml->startElement('color');
                $this->xml->writeCData(implode(",", array_unique($colours)));
                $this->xml->endElement();
            }
        }else {
            $color = apply_filters('webexpert_skroutz_xml_custom_color',$this->get_tax_or_attribute($product,'colour'),$product);
            if (!empty($color)) {
                $this->xml->startElement('color');
                $this->xml->writeCData($color);
                $this->xml->endElement();
            }
        }
    }

    public function get_shipping_costs($product) {
        $total = $product->get_price();
        $weight= wc_get_weight($product->get_weight(),'kg') ?? 0;

        if (empty($this->options['flat_rate']) || empty($this->options['flat_rate_cost_per_kg']) || empty($this->options['flat_rate_free']))
            return;

        if ($weight<=2) {
            $cost = $this->options['flat_rate'];
            if ($total>$this->options['flat_rate_free']) {
                $cost=0;
            }
        }else {
            $cost = $this->options['flat_rate'] + ($this->options['flat_rate_cost_per_kg'] * ceil($weight-2));
            if ($total>$this->options['flat_rate_free']) {
                $cost-=$this->options['flat_rate'];
            }
        }

        $shipping = apply_filters('webexpert_skroutz_xml_custom_shipping',$cost,$product);
        if (!empty($shipping)) {
            $this->xml->startElement('shipping');
            $this->xml->writeCData(esc_html(apply_filters('webexpert_skroutz_xml_custom_shipping',$shipping,$product)));
            $this->xml->endElement();
        }
    }

    public function get_stock($product,$overrides=[]) {
        $is_on_backorder = $this->is_on_backorder($product);
        if ($product->is_in_stock()) {
            $instock='Y';
        }else {
            if ($is_on_backorder) {
                $instock='Y';
            }else {
                $instock='N';
            }
        }
        $this->xml->writeElement('instock', apply_filters('webexpert_skroutz_xml_custom_instock',$instock,$product));
        if (!empty($overrides['quantity'])) {
            $quantity=0;
            foreach ($this->options['colour'] as $color) {
                if (isset($overrides['quantity'][$product->get_attribute($color)])) {
                    $quantity += $overrides['quantity'][$product->get_attribute($color)] ?? 0;
                }
            }
            $this->xml->writeElement('quantity', apply_filters('webexpert_skroutz_xml_custom_quantity',$quantity,$product));
        }else {
            if ($product->is_type('simple')) {
                $quantity = $product->get_stock_quantity();
            } elseif ($product->is_type('variable')) {
                $quantity=0;
                $variations = $product->get_available_variations('objects');
                foreach($variations as $variation){
                    if ($this->check_visibility($variation) !== false) {
                        $quantity += $variation->get_stock_quantity();
                    }
                }
            }else {
                $quantity = $product->get_stock_quantity();
            }
            $quantity = apply_filters('webexpert_skroutz_xml_custom_quantity',$quantity,$product);
            if ($quantity>0) {
                $this->xml->writeElement('quantity', apply_filters('webexpert_skroutz_xml_custom_quantity',$quantity,$product));
            }
        }
    }

    public function get_ean($product) {
        $ean = $product->get_meta('we_skroutzxml_ean_barcode') ?? null;
        if (!empty($this->options['ean'])) {
            $custom_value=$product->get_meta($this->options['ean']);
            if ($product->is_type('variation') && empty($custom_value)) {
                $custom_value=get_post_meta($product->get_parent_id(),$this->options['ean'],true);
            }
        }
        $ean =apply_filters('webexpert_skroutz_xml_custom_ean', $ean, $product);
        if (!empty($ean))
            $this->xml->writeElement('ean', ($custom_value ?? $ean));
    }

    public function get_mpn($product) {
        //  $_mpn = esc_html(apply_filters('webexpert_skroutz_xml_custom_sku', $product->get_sku(),$product));
        $_mpn = $product->get_meta('we_skroutzxml_ean_barcode') ?? null;
        $mpn =  $product->get_meta('Skroutz_Code') ?? $_mpn;

        if (!empty($mpn)){
            $this->xml->writeElement('mpn', $mpn);
        }
        elseif(!empty($_mpn)){
            $this->xml->writeElement('mpn', $_mpn);
        }

    }

    public function get_manufacturer($product) {
        $manufacturer = esc_html(apply_filters('webexpert_skroutz_xml_custom_manufacturer',$this->get_tax_or_attribute($product, 'manufacturer'),$product));
        if (!empty($manufacturer)) {
            $this->xml->startElement('manufacturer');
            $this->xml->writeCData($manufacturer);
            $this->xml->endElement();
        }
    }

    public function get_tax_or_attribute($product,$attribute) {
        $attributes = array();
        if (isset($this->options[$attribute]) && is_array($this->options[$attribute])) {
            foreach ($this->options[$attribute] as $attr) {
                $pa_terms = get_the_terms(($product->is_type('variation') ? $product->get_parent_id() : $product->get_id()), taxonomy_exists($attr) ? $attr : 'pa_' . $attr);
                if ($pa_terms && !is_wp_error($pa_terms)) {
                    $array_terms = array_map(function ($e) {
                        return is_object($e) ? $e->name : $e['name'];
                    }, $pa_terms);
                    $attributes = array_merge($attributes, $array_terms);
                }
            }
        }
        return implode(",", array_unique($attributes));
    }

    public function get_vat_rates($product) {
        $rate = 24;
        if (wc_tax_enabled()) {
            $tax_rates = WC_Tax::get_rates( $product->get_tax_class() );
            if ($tax_rates && is_array($tax_rates)) {
                $rate = end($tax_rates)['rate'];
            }
        }
        $this->xml->writeElement('vat', apply_filters('webexpert_skroutz_xml_custom_vat',$rate,$product));
    }

    public function get_prices($product) {
        if ($product->is_type('simple')) {
            $price = wc_get_price_including_tax($product,['qty'=>1,'price'=>$product->get_price()]);
        } elseif ($product->is_type('variable')) {
            $min_var_reg_price = $product->get_variation_regular_price('min', true);
            $min_var_sale_price = $product->get_variation_sale_price('min', true);
            if ($min_var_sale_price != $min_var_reg_price) {
                $price = $min_var_sale_price;
            } else {
                $price = $min_var_reg_price;
            }
        }else {
            $price = wc_get_price_including_tax($product,['qty'=>1,'price'=>$product->get_price()]);
        }

        $this->xml->startElement('price_with_vat');
        $this->xml->writeCData(esc_html(apply_filters('webexpert_skroutz_xml_custom_pricing',$price,$product)));
        $this->xml->endElement();
    }

    public function get_gallery($product) {
        $attachment_ids = apply_filters('webexpert_skroutz_xml_custom_gallery', $product->get_gallery_image_ids(), $product);
        if (sizeof($attachment_ids)>0) {
            foreach ($attachment_ids as $attachment_id) {
                $this->xml->startElement('additional_imageurl');
                $this->xml->writeCData(esc_html(apply_filters('webexpert_skroutz_xml_custom_image',wp_get_attachment_url($product->get_image_id()),$product)));
                $this->xml->endElement();
            }
        }
    }

    public function get_image($product) {
        $pImage= wp_get_attachment_url($product->get_image_id());
        if (get_post_meta($product->get_id(), '_knawatfibu_url', true)) {
            $k_image = get_post_meta($product->get_id(), '_knawatfibu_url', true);
            $pImage= $k_image['img_url']??'';
        }
        $this->xml->startElement('image');
        $this->xml->writeCData(esc_html(apply_filters('webexpert_skroutz_xml_custom_image', $pImage)));
        $this->xml->endElement();
    }

    public function get_link($product) {
        $this->xml->startElement('link');
        $this->xml->writeCData(esc_html(apply_filters('webexpert_skroutz_xml_custom_links',trim($product->get_permalink()),$product)));
        $this->xml->endElement();
    }

    public function get_name($product) {
        $product_title=$product->get_title();
        if ($product->is_type('variation')) {
            if ($this->options['variation_title']=='colour_only') {
                if ($product->is_type('variation')) {
                    $colours = [];
                    foreach ($this->options['colour'] as $color) {
                        $term = get_term_by('name', $product->get_attribute($color), 'pa_' . $color);
                        if (!is_wp_error($term) && $term !== false) {
                            array_push($colours, $term->name);
                        }
                    }
                    $product_title .= (!empty($colours) ? ' '.implode(",",$colours)  : '');

                }else {
                    $product_title .= (!empty($this->get_tax_or_attribute($product,'colour')) ? ' '.$this->get_tax_or_attribute($product,'colour')  : '');
                }

            }elseif($this->options['variation_title']=="all_values") {
                $product_title.=" ".wc_get_formatted_variation($product,true,false,false);
            }elseif($this->options['variation_title']=="all_values_and_labels"){
                $product_title.=" ".wc_get_formatted_variation($product,true,true,false);
            }
        }
        $this->xml->startElement('name');
        $this->xml->writeCData(esc_html(apply_filters('webexpert_skroutz_xml_custom_product_title', $product_title, $product)));
        $this->xml->endElement();
    }

    public function get_categories($product) {
        $categories_list = get_the_terms(($product->is_type('variation') || $product->is_type('variable') ? $product->get_parent_id() : $product->get_id()), 'product_cat');
        if ($categories_list) {
            $last_category = end($categories_list);
            if ( class_exists( 'WPSEO_Primary_Term' ) ) {
                if ($product->is_type('variation')) {
                    $primary_term_object = new WPSEO_Primary_Term('product_cat', $product->get_parent_id());
                }else {
                    $primary_term_object = new WPSEO_Primary_Term('product_cat', $product->get_id());
                }
                $last_category_id=$primary_term_object->get_primary_term();
                if ($last_category_id)
                    $last_category = get_term( $last_category_id, 'product_cat' );
            }
            $rank_math_primary_product_cat=$product->is_type('variation') ? get_post_meta($product->get_parent_id(),'rank_math_primary_product_cat',true) : $product->get_meta('rank_math_primary_product_cat');
            if (!empty($rank_math_primary_product_cat)) {
                $last_category_id=$rank_math_primary_product_cat;
                if ($last_category_id)
                    $last_category = get_term( $last_category_id, 'product_cat' );
            }
            $categories_list = array();
            $ancestors = get_ancestors($last_category->term_id, 'product_cat', 'taxonomy');
            $ancestors=array_reverse($ancestors);
            foreach ($ancestors as $parent) {
                $term = get_term_by('id', $parent, 'product_cat');
                array_push($categories_list, $term->name);
            }
            array_push($categories_list, $last_category->name);
            $categories = implode(' > ', apply_filters('webexpert_skroutz_xml_custom_categories',$categories_list));
            $this->xml->startElement('category');
            $this->xml->writeCData(esc_html($categories));
            $this->xml->endElement();
        }
    }

    public function get_availability($product) {
        $is_on_backorder = $this->is_on_backorder($product);
        if ($product->is_in_stock() && !$is_on_backorder) {
            $availability = $this->get_xml_availability($product);
        } else {
            if ($is_on_backorder) {
                $availability = $this->get_xml_availability($product,'pre');
            }else {
                $availability = $this->get_xml_availability($product,'no');
            }
        }
        $this->xml->writeElement('availability',$availability );
    }

    public function get_xml_availability($product,$type='') {
        $availability=$product->get_meta(apply_filters("webexpert_skroutz_xml_{$type}availability","we_skroutzxml_custom_{$type}availability"));
        // empty array patch
        if (is_array($availability)) {
            foreach ($availability as $key => $value) {
                if (empty($value)) {
                    unset($availability[$key]);
                }
            }
        }
        if (empty($availability) && $product->is_type('variation')) {
            $availability = get_post_meta($product->get_parent_id(), apply_filters("webexpert_skroutz_xml_{$type}availability","we_skroutzxml_custom_{$type}availability"), true);
        }
        return (!empty($availability) ? $availability : $this->options["xml_{$type}availability"]);
    }

    public function check_visibility($product) {
        if (apply_filters('webexpert_skroutz_xml_product_visibility_control',true,$product)===false) {
            return false;
        }

        $is_on_backorder = $this->is_on_backorder($product);
        if ($product->is_in_stock() && !$is_on_backorder) {
            if ($this->get_xml_availability($product) == "Απόκρυψη από το XML") {
                return false;
            }
        } else {
            if ($is_on_backorder) {
                if ($this->get_xml_availability($product,'pre') == "Απόκρυψη από το XML") {
                    return false;
                }
            }else {
                if ($this->get_xml_availability($product,'no') == "Απόκρυψη από το XML") {
                    return false;
                }
            }
        }

        return true;
    }
}

function run_webexpert_skroutz_engine() {

    $plugin = new WebExpert_Skroutz_Egnine();
    $plugin->run();

}
run_webexpert_skroutz_engine();

if(php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
    return true;
}else {
    wp_redirect(admin_url('admin.php?page=webexpert-skroutz-xml-feed'));
}
die();
