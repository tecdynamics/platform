<?php

namespace Tec\Base\Forms;

use Assets;
use Tec\Base\Forms\Fields\AutocompleteField;
use Tec\Base\Forms\Fields\ColorField;
use Tec\Base\Forms\Fields\CustomRadioField;
use Tec\Base\Forms\Fields\CustomSelectField;
use Tec\Base\Forms\Fields\DateField;
use Tec\Base\Forms\Fields\EditorField;
use Tec\Base\Forms\Fields\HtmlField;
use Tec\Base\Forms\Fields\MediaFileField;
use Tec\Base\Forms\Fields\MediaImageField;
use Tec\Base\Forms\Fields\MediaImagesField;
use Tec\Base\Forms\Fields\OnOffField;
use Tec\Base\Forms\Fields\RepeaterField;
use Tec\Base\Forms\Fields\TimeField;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use JsValidator;
use Kris\LaravelFormBuilder\Fields\FormField;
use Kris\LaravelFormBuilder\Form;
use Throwable;


abstract class FormAbstract extends Form {
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $validatorClass = '';

    /**
     * @var array
     */
    protected $metaBoxes = [];

    /**
     * @var string
     */
    protected $actionButtons = '';

    /**
     * @var string
     */
    protected $breakFieldPoint = '';

    /**
     * @var bool
     */
    protected $useInlineJs = false;

    /**
     * @var string
     */
    protected $wrapperClass = 'form-body';

    /**
     * @var string
     */
    protected $template = 'core/base::forms.form';

    /**
     * FormAbstract constructor.
     */
    public function __construct() {
        $this->setMethod('POST');
        $this->setFormOption('template', $this->template);
        $this->setFormOption('id', strtolower(Str::slug(Str::snake(get_class($this)))));
    }

    /**
     * @return array
     */
    public function getOptions(): array {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): self {
        $this->options = $options;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self {
        $this->title = $title;
        return $this;
    }

    /**
     * @return array
     */
    public function getMetaBoxes(): array {
        uasort($this->metaBoxes, function ($before, $after) {
            if (Arr::get($before, 'priority', 0) > Arr::get($after, 'priority', 0)) {
                return 1;
            } elseif (Arr::get($before, 'priority', 0) < Arr::get($after, 'priority', 0)) {
                return -1;
            }

            return 0;
        });

        return $this->metaBoxes;
    }


    /**
     * @param string $name
     * @return string
     * @throws Throwable
     */
    public function getMetaBox(string $name): string {
        if (!Arr::get($this->metaBoxes, $name)) {
            return '';
        }

        $metaBox = $this->metaBoxes[$name];

        return view('core/base::forms.partials.meta-box', compact('metaBox'))->render();
    }

    /**
     * @param array|string $boxes
     * @return $this
     */
    public function addMetaBoxes($boxes): self {
        if (!is_array($boxes)) {
            $boxes = [$boxes];
        }
        $this->metaBoxes = array_merge($this->metaBoxes, $boxes);

        return $this;
    }

    /**
     * @param string $name
     * @return \Tec\Base\Forms\FormAbstract
     */
    public function removeMetaBox(string $name): self {
        Arr::forget($this->metaBoxes, $name);
        return $this;
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function getActionButtons(): string {
        if ($this->actionButtons === '') {
            return view('core/base::forms.partials.form-actions')->render();
        }

        return $this->actionButtons;
    }

    /**
     * @param string $actionButtons
     * @return $this
     */
    public function setActionButtons(string $actionButtons): self {
        $this->actionButtons = $actionButtons;

        return $this;
    }

    /**
     * @return $this
     */
    public function removeActionButtons(): self {
        $this->actionButtons = '';

        return $this;
    }

    /**
     * @return string
     */
    public function getBreakFieldPoint(): string {
        return $this->breakFieldPoint;
    }

    /**
     * @param string $breakFieldPoint
     * @return $this
     */
    public function setBreakFieldPoint(string $breakFieldPoint): self {
        $this->breakFieldPoint = $breakFieldPoint;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUseInlineJs(): bool {
        return $this->useInlineJs;
    }

    /**
     * @param bool $useInlineJs
     * @return $this
     */
    public function setUseInlineJs(bool $useInlineJs): self {
        $this->useInlineJs = $useInlineJs;
        return $this;
    }

    /**
     * @return string
     */
    public function getWrapperClass(): string {
        return $this->wrapperClass;
    }

    /**
     * @param string $wrapperClass
     * @return $this
     */
    public function setWrapperClass(string $wrapperClass): self {
        $this->wrapperClass = $wrapperClass;
        return $this;
    }

    /**
     * @return $this
     */
    public function withCustomFields(): self {
        $customFields = [
            'customSelect' => \Tec\Base\Forms\Fields\CustomSelectField::class,
            'editor' => \Tec\Base\Forms\Fields\EditorField::class,
            'onOff' => \Tec\Base\Forms\Fields\OnOffField::class,
            'customRadio' => \Tec\Base\Forms\Fields\CustomRadioField::class,
            'mediaImage' => \Tec\Base\Forms\Fields\MediaImageField::class,
            'mediaImages' => \Tec\Base\Forms\Fields\MediaImagesField::class,
            'mediaFile' => \Tec\Base\Forms\Fields\MediaFileField::class,
            'customColor' => \Tec\Base\Forms\Fields\ColorField::class,
            'time' => \Tec\Base\Forms\Fields\TimeField::class,
            'date' => \Tec\Base\Forms\Fields\DateField::class,
            'autocomplete' => \Tec\Base\Forms\Fields\AutocompleteField::class,
            'html' => \Tec\Base\Forms\Fields\HtmlField::class,
            'repeater' => \Tec\Base\Forms\Fields\RepeaterField::class,
        ];

        foreach ($customFields as $key => $field) {
            $this->addCustomField($key, $field);
        }

        return apply_filters('form_custom_fields', $this, $this->formHelper);
    }

    /**
     * @param string $name
     * @param string $class
     * @return $this|Form
     */
    public function addCustomField($name, $class) {
        if (!$this->formHelper->hasCustomField($name)) {
            parent::addCustomField($name, $class);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function hasTabs(): self {
        $this->setFormOption('template', 'core/base::forms.form-tabs');

        return $this;
    }

    /**
     * @return int
     */
    public function hasMainFields(): int {
        if (!$this->breakFieldPoint) {
            return count($this->fields);
        }

        $mainFields = [];

        /**
         * @var FormField $field
         */
        foreach ($this->fields as $field) {
            if ($field->getName() == $this->breakFieldPoint) {
                break;
            }

            $mainFields[] = $field;
        }

        return count($mainFields);
    }

    /**
     * @return $this
     */
    public function disableFields() {
        parent::disableFields();

        return $this;
    }

    /**
     * @param array $options
     * @param bool $showStart
     * @param bool $showFields
     * @param bool $showEnd
     * @return string
     */
    public function renderForm(array $options = [], $showStart = true, $showFields = true, $showEnd = true): string {
        Assets::addScripts(['form-validation', 'are-you-sure']);

        apply_filters(BASE_FILTER_BEFORE_RENDER_FORM, $this, $this->getModel());

        return parent::renderForm($options, $showStart, $showFields, $showEnd);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function renderValidatorJs(): string {
        $element = null;
        if ($this->getFormOption('id')) {
            $element = '#' . $this->getFormOption('id');
        } elseif ($this->getFormOption('class')) {
            $element = '.' . $this->getFormOption('class');
        }

        return JsValidator::formRequest($this->getValidatorClass(), $element);
    }

    /**
     * @return string
     */
    public function getValidatorClass(): string {
        return $this->validatorClass;
    }

    /**
     * @param string $validatorClass
     * @return $this
     */
    public function setValidatorClass(string $validatorClass): self {
        $this->validatorClass = $validatorClass;

        return $this;
    }

    /**
     * Set model to form object.
     *
     * @param mixed $model
     * @return $this
     */
    public function setModel($model) {
        $this->model = $model;

        $this->rebuildForm();

        return $this;
    }

    /**
     * Setup model for form, add namespace if needed for child forms.
     *
     * @param string $model
     * @return $this
     */
    protected function setupModel($model) {
        if (!$this->model) {
            $this->model = $model;
            $this->setupNamedModel();
        }

        return $this;
    }

    /**
     * Set form options.
     *
     * @param array $formOptions
     * @return $this
     */
    public function setFormOptions(array $formOptions) {
        parent::setFormOptions($formOptions);

        if (isset($formOptions['template'])) {
            $this->template = $formOptions['template'];
        }

        return $this;
    }
}
