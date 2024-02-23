<?php

namespace Tec\Base\Forms;

use Closure;
use Tec\Base\Contracts\BaseModel;
use Tec\Base\Events\BeforeUpdateContentEvent;
use Tec\Base\Events\CreatedContentEvent;
use Tec\Base\Events\UpdatedContentEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Tec\Base\Events\BeforeCreateContentEvent;
use Tec\Base\Events\BeforeEditContentEvent;
use Tec\Base\Contracts\Builders\Extensible as ExtensibleContract;
use Tec\Base\Facades\Assets;
use Tec\Base\Forms\Fields\AutocompleteField;
use Tec\Base\Forms\Fields\ColorField;
use Tec\Base\Forms\Fields\CustomRadioField;
use Tec\Base\Forms\Fields\CustomSelectField;
use Tec\Base\Forms\Fields\DatePickerField;
use Tec\Base\Forms\Fields\DatetimeField;
use Tec\Base\Forms\Fields\EditorField;
use Tec\Base\Forms\Fields\HtmlField;
use Tec\Base\Forms\Fields\MediaFileField;
use Tec\Base\Forms\Fields\MediaImageField;
use Tec\Base\Forms\Fields\MediaImagesField;
use Tec\Base\Forms\Fields\OnOffCheckboxField;
use Tec\Base\Forms\Fields\OnOffField;
use Tec\Base\Forms\Fields\RepeaterField;
use Tec\Base\Forms\Fields\TagField;
use Tec\Base\Forms\Fields\TimeField;
use Tec\Base\Supports\Builders\Extensible;
use Tec\Base\Supports\RenderingExtensible;
use Tec\Base\Traits\Forms\HasColumns;
use Tec\Base\Traits\Forms\HasMetadata;
use Tec\JsValidation\Facades\JsValidator;
use Tec\JsValidation\Javascript\JavascriptValidator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;

abstract class FormAbstract extends Form implements ExtensibleContract
{
    use Conditionable;
    use Extensible;
    use HasColumns;
    use HasMetadata;
    use RenderingExtensible;

    protected array $options = [];

    protected string $title = '';

    protected string $validatorClass = '';

    protected array $metaBoxes = [];

    protected string $actionButtons = '';

    protected string $breakFieldPoint = '';

    protected bool $useInlineJs = false;

    protected string $wrapperClass = 'form-body';

    protected $template = 'core/base::forms.form';

    public function __construct()
    {
        $this->setMethod('POST');
        $this->setFormOption('template', $this->template);
        $this->setFormOption('id', strtolower(Str::slug(Str::snake(get_class($this)))));
        $this->setFormOption('class', 'js-base-form');
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMetaBoxes(): array
    {
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

    public function getMetaBox(string $name): string|View
    {
        if (! Arr::get($this->metaBoxes, $name)) {
            return '';
        }

        $metaBox = $this->metaBoxes[$name];

        $view = view('core/base::forms.partials.meta-box', compact('metaBox'));

        if (Arr::get($metaBox, 'render') === false) {
            return $view;
        }

        return $view->render();
    }

    public function addMetaBoxes(array|string $boxes): self
    {
        if (! is_array($boxes)) {
            $boxes = [$boxes];
        }

        $this->metaBoxes = array_merge($this->metaBoxes, $boxes);

        return $this;
    }

    public function removeMetaBox(string $name): self
    {
        Arr::forget($this->metaBoxes, $name);

        return $this;
    }

    public function getActionButtons(): string
    {
        if ($this->actionButtons === '') {
            return view('core/base::forms.partials.form-actions')->render();
        }

        return $this->actionButtons;
    }

    public function setActionButtons(string $actionButtons): self
    {
        $this->actionButtons = $actionButtons;

        return $this;
    }

    public function removeActionButtons(): self
    {
        $this->actionButtons = '';

        return $this;
    }

    public function getBreakFieldPoint(): string
    {
        return $this->breakFieldPoint;
    }

    public function setBreakFieldPoint(string $breakFieldPoint): self
    {
        $this->breakFieldPoint = $breakFieldPoint;

        return $this;
    }

    public function isUseInlineJs(): bool
    {
        return $this->useInlineJs;
    }

    public function setUseInlineJs(bool $useInlineJs): self
    {
        $this->useInlineJs = $useInlineJs;

        return $this;
    }

    public function getWrapperClass(): string
    {
        return $this->wrapperClass;
    }

    public function setWrapperClass(string $wrapperClass): self
    {
        $this->wrapperClass = $wrapperClass;

        return $this;
    }

    public function withCustomFields(): self
    {
        $customFields = [
            'customSelect' => CustomSelectField::class,
            'editor' => EditorField::class,
            'onOff' => OnOffField::class,
            'onOffCheckbox' => OnOffCheckboxField::class,
            'customRadio' => CustomRadioField::class,
            'mediaImage' => MediaImageField::class,
            'mediaImages' => MediaImagesField::class,
            'mediaFile' => MediaFileField::class,
            'customColor' => ColorField::class,
            'time' => TimeField::class,
            'datePicker' => DatePickerField::class,
            'datetime' => DatetimeField::class,
            'autocomplete' => AutocompleteField::class,
            'html' => HtmlField::class,
            'repeater' => RepeaterField::class,
            'tags' => TagField::class,
        ];

        foreach ($customFields as $key => $field) {
            $this->addCustomField($key, $field);
        }

        return apply_filters('form_custom_fields', $this, $this->formHelper);
    }

    public function addCustomField($name, $class): self
    {
        if ($this->rebuilding && $this->formHelper->hasCustomField($name)) {
            return $this;
        }

        if (! $this->formHelper->hasCustomField($name)) {
            $this->formHelper->addCustomField($name, $class);
        }

        return $this;
    }

    public function hasTabs(): self
    {
        $this->setFormOption('template', 'core/base::forms.form-tabs');

        return $this;
    }

    public function hasMainFields(): int
    {
        if (! $this->breakFieldPoint) {
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

    public function disableFields(): self
    {
        parent::disableFields();

        return $this;
    }

    public function renderForm(array $options = [], $showStart = true, $showFields = true, $showEnd = true): string
    {
        Assets::addScripts(['form-validation', 'are-you-sure']);

        $class = $this->getFormOption('class');
        $this->setFormOption('class', $class . ' dirty-check');



        $model = $this->getModel();

        apply_filters(BASE_FILTER_BEFORE_RENDER_FORM, $this, $model);

        if ($model->getKey()) {
            event(new BeforeEditContentEvent($this->request, $model));
        } else {
            event(new BeforeCreateContentEvent($this->request, $model));
        }

        return parent::renderForm($options, $showStart, $showFields, $showEnd);
    }

    public function renderValidatorJs(): string|JavascriptValidator
    {
        $element = null;
        if ($this->getFormOption('id')) {
            $element = '#' . $this->getFormOption('id');
        } elseif ($this->getFormOption('class')) {
            $element = '.' . $this->getFormOption('class');
        }

        return JsValidator::formRequest($this->getValidatorClass(), $element);
    }

    public function getValidatorClass(): string
    {
        return $this->validatorClass;
    }

    public function setValidatorClass(string $validatorClass): self
    {
        $this->validatorClass = $validatorClass;

        return $this;
    }

    public function setModel($model): self
    {
        $this->model = $model;

        $this->rebuildForm();

        return $this;
    }

    protected function setupModel($model): self
    {
        if (! $this->model) {
            $this->model = $model;
            $this->setupNamedModel();
        }

        return $this;
    }

    public function setFormOptions(array $formOptions): self
    {
        parent::setFormOptions($formOptions);

        if (isset($formOptions['template'])) {
            $this->template = $formOptions['template'];
        }

        return $this;
    }

    public function add($name, $type = 'text', array $options = [], $modify = false): self
    {
        $options['attr']['v-pre'] = 1;

        parent::add($name, $type, $options, $modify);

        return $this;
    }

    public function tap(callable $callback = null): self
    {
        $callback($this);

        return $this;
    }

    public function template(string $template): self
    {
        $this->setFormOption('template', $template);

        return $this;
    }

    public function contentOnly(): self
    {
        $this->setFormOption('template', 'core/base::forms.form-content-only');

        return $this;
    }

    public function setUrl($url): self
    {
        $this->setFormOption('url', $url);

        return $this;
    }
    public function onlyValidatedData(bool $onlyValidatedData = true): static
    {
        $this->onlyValidatedData = $onlyValidatedData;

        return $this;
    }

    public function getRequestData(): array
    {
        $request = $this->request;

        if ($this->onlyValidatedData && $request instanceof FormRequest) {
            return $request->validated();
        }

        return $request->input();
    }

    public static function beforeSaving(callable|Closure $callback, int $priority = 100): void
    {
        if (static::class === \Tec\Base\Forms\FormAbstract::class) {
            add_filter(BASE_FILTER_BEFORE_SAVE_FORM, $callback, $priority, 2);

            return;
        }

        add_filter(static::getFilterPrefix() . '_before_saving', $callback, $priority, 2);
    }

    public static function afterSaving(callable|Closure $callback, int $priority = 100): void
    {
        if (static::class === FormAbstract::class) {
            add_filter(BASE_FILTER_AFTER_SAVE_FORM, $callback, $priority, 2);

            return;
        }

        add_filter(static::getFilterPrefix() . '_after_saving', $callback, $priority, 2);
    }

    public function save(): void
    {
        $this->saving(function (FormAbstract $form) {
            $form
                ->getModel()
                ->fill($form->getRequestData())
                ->save();
        });
    }

    public function saveOnlyValidatedData(): void
    {
        $this->onlyValidatedData()->save();
    }

    public function saving(callable|Closure $callback): void
    {
        $model = $this->getModel();
        $request = $this->request;

        if ($model instanceof BaseModel) {
            if ($model->getKey()) {
                BeforeUpdateContentEvent::dispatch($request, $model);
            } else {
                \Tec\Base\Events\BeforeCreateContentEvent::dispatch($request, $model);
            }
        }

        $this->dispatchBeforeSaving();

        call_user_func($callback, $this);

        $this->saveMetadataFields();

        $this->dispatchAfterSaving();

        if ($model instanceof Model) {
            if ($model->wasRecentlyCreated) {
                CreatedContentEvent::dispatch('form', $request, $model);
            } else {
                UpdatedContentEvent::dispatch('form', $request, $model);
            }
        }
    }

    protected function dispatchBeforeSaving(): void
    {
        apply_filters(BASE_FILTER_BEFORE_SAVE_FORM, $this);
        apply_filters(static::getFilterPrefix() . '_before_saving', $this);
    }

    protected function dispatchAfterSaving(): void
    {
        apply_filters(BASE_FILTER_AFTER_SAVE_FORM, $this);
        apply_filters(static::getFilterPrefix() . '_after_saving', $this);
    }

    public static function getFilterPrefix(): string
    {
        return sprintf(
            'base_form_%s',
            Str::of(static::class)->snake()->lower()->replace('\\', '')->toString()
        );
    }

    public static function getGlobalClassName(): string
    {
        return FormAbstract::class;
    }

    public static function hasGlobalExtend(): bool
    {
        return true;
    }

    public static function globalExtendFilterName(): string
    {
        return BASE_FILTER_EXTENDED_FORM;
    }

    public static function hasGlobalRendering(): bool
    {
        return true;
    }

    public static function globalBeforeRenderingFilterName(): string
    {
        return BASE_FILTER_BEFORE_RENDER_FORM;
    }

    public static function globalAfterRenderingFilterName(): string
    {
        return BASE_FILTER_AFTER_RENDER_FORM;
    }

    public static function create(array $options = [], array $data = []): static
    {
        return app(FormBuilder::class)->create(static::class, $options, $data);
    }

    public static function createFromArray(array $object, array $options = [], array $data = []): static
    {
        return static::create([...$options, 'model' => $object], $data);
    }

    public static function createFromModel(BaseModel|Model $model, array $options = [], array $data = []): static
    {
        return static::create([...$options, 'model' => $model], $data);
    }

    public function hasFiles(): static
    {
        $this->setFormOption('files', true);

        return $this;
    }

    public function formClass(string $class, bool $override = false): static
    {
        if ($override) {
            $this->setFormOption('class', $class);

            return $this;
        }

        $this->setFormOption('class', $this->getFormOption('class') . ' ' . $class);

        return $this;
    }
}
