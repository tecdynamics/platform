@php
    /** @var \Tec\Table\Abstracts\TableAbstract $table */
@endphp

<div class="table-wrapper">
    @if ($table->hasFilters())
        <div
            class="table-configuration-wrap"
            @if ($table->isFiltering()) style="display: block;" @endif
        >
            <span class="configuration-close-btn btn-show-table-options"><i class="fa fa-times"></i></span>
            {!! $table->renderFilter() !!}
        </div>
    @endif

    <div class="portlet light bordered portlet-no-padding">
        <div class="portlet-title">
            <div class="caption">
                <div class="wrapper-action">
                    @if ($table->hasBulkActions())
                        <div class="btn-group">
                            <a
                                class="btn btn-secondary dropdown-toggle"
                                data-bs-toggle="dropdown"
                                href="#"
                            >{{ trans('core/table::table.bulk_actions') }}
                            </a>

                            <ul class="dropdown-menu">
                                @foreach ($table->getBulkActions() as $action)
                                    <li>
                                        {!! $action !!}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($table->hasFilters())
                        <button
                            class="btn btn-primary btn-show-table-options">{{ trans('core/table::table.filters') }}</button>
                    @endif
                </div>
            </div>
        </div>
        <div class="portlet-body">
            <div
                class="table-responsive @if ($table->hasBulkActions()) table-has-actions @endif @if ($table->hasFilters()) table-has-filter @endif">
                @section('main-table')
                    {!! $dataTable->table(compact('id', 'class'), false) !!}
                @show
            </div>
        </div>
    </div>
</div>
@push('footer')
@include('core/table::modal')
    {!! $dataTable->scripts() !!}
@endpush
