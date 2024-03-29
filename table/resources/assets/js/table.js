;(($, DataTable) => {
    'use strict'

    let _buildParams = (dt, action) => {
        let params = dt.ajax.params()
        params.action = action
        params._token = $('meta[name="csrf-token"]').attr('content')

        return params
    }

    let _downloadFromUrl = function (url, params) {
        let postUrl = url + '/export'
        let xhr = new XMLHttpRequest()
        xhr.open('POST', postUrl, true)
        xhr.responseType = 'arraybuffer'
        xhr.onload = function () {
            if (this.status === 200) {
                let filename = ''
                let disposition = xhr.getResponseHeader('Content-Disposition')
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/
                    let matches = filenameRegex.exec(disposition)
                    if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '')
                }
                let type = xhr.getResponseHeader('Content-Type')

                let blob = new Blob([this.response], { type: type })
                if (typeof window.navigator.msSaveBlob !== 'undefined') {
                    // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                    window.navigator.msSaveBlob(blob, filename)
                } else {
                    let URL = window.URL || window.webkitURL
                    let downloadUrl = URL.createObjectURL(blob)

                    if (filename) {
                        // use HTML5 a[download] attribute to specify filename
                        let a = document.createElement('a')
                        // safari doesn't support this yet
                        if (typeof a.download === 'undefined') {
                            window.location = downloadUrl
                        } else {
                            a.href = downloadUrl
                            a.download = filename
                            document.body.appendChild(a)
                            a.trigger('click')
                        }
                    } else {
                        window.location = downloadUrl
                    }

                    setTimeout(() => {
                        URL.revokeObjectURL(downloadUrl)
                    }, 100) // cleanup
                }
            }
        }
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
        xhr.send($.param(params))
    }

    let _buildUrl = (dt, action) => {
        let url = dt.ajax.url() || ''
        let params = dt.ajax.params()
        params.action = action

        if (url.indexOf('?') > -1) {
            return url + '&' + $.param(params)
        }

        return url + '?' + $.param(params)
    }

    DataTable.ext.buttons.excel = {
        className: 'buttons-excel',

        text: (dt) => {
            return (
                '<i class="far fa-file-excel"></i> ' +
                dt.i18n(
                    'buttons.excel',
                    TecVariables.languages.tables.excel ? TecVariables.languages.tables.excel : 'Excel'
                )
            )
        },

        action: (e, dt) => {
            window.location = _buildUrl(dt, 'excel')
        },
    }

    DataTable.ext.buttons.postExcel = {
        className: 'buttons-excel',

        text: (dt) => {
            return (
                '<i class="far fa-file-excel"></i> ' +
                dt.i18n(
                    'buttons.excel',
                    TecVariables.languages.tables.excel ? TecVariables.languages.tables.excel : 'Excel'
                )
            )
        },

        action: (e, dt) => {
            let url = dt.ajax.url() || window.location.href
            let params = _buildParams(dt, 'excel')

            _downloadFromUrl(url, params)
        },
    }

    DataTable.ext.buttons.export = {
        extend: 'collection',

        className: 'buttons-export',

        text: (dt) => {
            return (
                '<i class="fa fa-download"></i> ' +
                dt.i18n(
                    'buttons.export',
                    TecVariables.languages.tables.export ? TecVariables.languages.tables.export : 'Export'
                ) +
                '&nbsp;<span class="caret"/>'
            )
        },

        buttons: ['csv', 'excel'],
    }

    DataTable.ext.buttons.csv = {
        className: 'buttons-csv',

        text: (dt) => {
            return (
                '<i class="fas fa-file-csv"></i> ' +
                dt.i18n(
                    'buttons.csv',
                    TecVariables.languages.tables.csv ? TecVariables.languages.tables.csv : 'CSV'
                )
            )
        },

        action: (e, dt) => {
            window.location = _buildUrl(dt, 'csv')
        },
    }

    DataTable.ext.buttons.postCsv = {
        className: 'buttons-csv',

        text: (dt) => {
            return (
                '<i class="fas fa-file-csv"></i> ' +
                dt.i18n(
                    'buttons.csv',
                    TecVariables.languages.tables.csv ? TecVariables.languages.tables.csv : 'CSV'
                )
            )
        },

        action: (e, dt) => {
            let url = dt.ajax.url() || window.location.href
            let params = _buildParams(dt, 'csv')

            _downloadFromUrl(url, params)
        },
    }

    DataTable.ext.buttons.pdf = {
        className: 'buttons-pdf',

        text: (dt) => {
            return '<i class="far fa-file-pdf"></i> ' + dt.i18n('buttons.pdf', 'PDF')
        },

        action: (e, dt) => {
            window.location = _buildUrl(dt, 'pdf')
        },
    }

    DataTable.ext.buttons.postPdf = {
        className: 'buttons-pdf',

        text: (dt) => {
            return '<i class="far fa-file-pdf"></i> ' + dt.i18n('buttons.pdf', 'PDF')
        },

        action: (e, dt) => {
            let url = dt.ajax.url() || window.location.href
            let params = _buildParams(dt, 'pdf')

            _downloadFromUrl(url, params)
        },
    }

    DataTable.ext.buttons.print = {
        className: 'buttons-print',

        text: (dt) => {
            return (
                '<i class="fa fa-print"></i> ' +
                dt.i18n(
                    'buttons.print',
                    TecVariables.languages.tables.print ? TecVariables.languages.tables.print : 'Print'
                )
            )
        },

        action: (e, dt) => {
            window.location = _buildUrl(dt, 'print')
        },
    }

    DataTable.ext.buttons.reset = {
        className: 'buttons-reset',

        text: (dt) => {
            return (
                '<i class="fa fa-undo"></i> ' +
                dt.i18n(
                    'buttons.reset',
                    TecVariables.languages.tables.reset ? TecVariables.languages.tables.reset : 'Reset'
                )
            )
        },

        action: () => {
            $('.table thead input').val('').keyup()
            $('.table thead select').val('').change()
        },
    }

    DataTable.ext.buttons.reload = {
        className: 'buttons-reload',

        text: (dt) => {
            return (
                '<i class="fas fa-sync"></i> ' +
                dt.i18n(
                    'buttons.reload',
                    TecVariables.languages.tables.reload ? TecVariables.languages.tables.reload : 'Reload'
                )
            )
        },

        action: (e, dt) => {
            dt.draw(false)
        },
    }

    DataTable.ext.buttons.create = {
        className: 'buttons-create',

        text: (dt) => {
            return '<i class="fa fa-plus"></i> ' + dt.i18n('buttons.create', 'Create')
        },

        action: () => {
            window.location = window.location.href.replace(/\/+$/, '') + '/create'
        },
    }

    if (typeof DataTable.ext.buttons.copyHtml5 !== 'undefined') {
        $.extend(DataTable.ext.buttons.copyHtml5, {
            text: (dt) => {
                return '<i class="fa fa-copy"></i> ' + dt.i18n('buttons.copy', 'Copy')
            },
        })
    }

    if (typeof DataTable.ext.buttons.colvis !== 'undefined') {
        $.extend(DataTable.ext.buttons.colvis, {
            text: (dt) => {
                return '<i class="fa fa-eye"></i> ' + dt.i18n('buttons.colvis', 'Column visibility')
            },
        })
        $.extend(DataTable.ext.buttons.columnVisibility, {
            _columnText: function (b, a) {
                let c = b.column(a.columns).index(),
                    d = b.settings()[0].aoColumns[c].titleAttr || b.settings()[0].aoColumns[c].sTitle
                d || (d = b.column(c).header().innerHTML)
                d = d
                    .replace(/\n/g, ' ')
                    .replace(/<br\s*\/?>/gi, ' ')
                    .replace(/<select(.*?)<\/select>/g, '')
                    .replace(/<!\-\-.*?\-\->/g, '')
                    .replace(/<.*?>/g, '')
                    .replace(/^\s+|\s+$/g, '')
                return a.columnText ? a.columnText(b, c, d) : d
            },
        })
    }

    class TableManagement {
        constructor() {
            this.init()
            this.handleActionsRow()
            this.handleActionsExport()
        }

        init() {
            $(document).on('change', '.table-check-all', (event) => {
                let _self = $(event.currentTarget)
                let set = _self.attr('data-set')
                let checked = _self.prop('checked')

                $(set).each((index, el) => {
                    if (checked) {
                        $(el).prop('checked', true).trigger('change')
                    } else {
                        $(el).prop('checked', false).trigger('change')
                    }
                })
            })

            $(document).find('.table-check-all').closest('th').removeAttr('title')

            $(document).on('change', '.checkboxes', (event) => {
                let _self = $(event.currentTarget)
                let table = _self.closest('.table-wrapper').find('.table').prop('id')
                let checkboxAll = _self.closest('.table-wrapper').find('.table-check-all')

                let ids = []
                let $table = $('#' + table)

                $table.find('.checkboxes:checked').each((i, el) => {
                    ids[i] = $(el).val()
                })

                let row = _self.closest('tr')

                if (_self.prop('checked')) {
                    row.addClass('selected')
                } else {
                    row.removeClass('selected')
                }

                if (ids.length !== $table.find('.checkboxes').length) {
                    checkboxAll.prop('checked', false)

                    if (ids.length > 0) {
                        checkboxAll.prop('indeterminate', true)
                    } else {
                        checkboxAll.prop('indeterminate', false)
                    }
                } else {
                    checkboxAll.prop('checked', true)
                    checkboxAll.prop('indeterminate', false)
                }
            })

            $(document).on('click', '.btn-show-table-options', (event) => {
                event.preventDefault()
                $(event.currentTarget).closest('.table-wrapper').find('.table-configuration-wrap').slideToggle(500)
            })

            $(document).on('click', '.action-item', (event) => {
                event.preventDefault()
                let span = $(event.currentTarget).find('span[data-href]')
                let action = span.data('action')
                let url = span.data('href')
                if (action && url !== '#') {
                    window.location.href = url
                }
            })
        }

        handleActionsRow() {
            let that = this
            $(document).on('click', '.deleteDialog', (event) => {
                event.preventDefault()
                let _self = $(event.currentTarget)

                $('.delete-crud-entry')
                    .data('section', _self.data('section'))
                    .data('parent-table', _self.closest('.table').prop('id'))
                $('.modal-confirm-delete').modal('show')
            })

            $('.delete-crud-entry').on('click', (event) => {
                event.preventDefault()
                let _self = $(event.currentTarget)

                _self.addClass('button-loading')

                let deleteURL = _self.data('section')

                $httpClient
                    .make()
                    .delete(deleteURL)
                    .then(({ data }) => {
                        window.LaravelDataTables[_self.data('parent-table')]
                            .row($('a[data-section="' + deleteURL + '"]').closest('tr'))
                            .remove()
                            .draw()

                        Tec.showSuccess(data.message)

                        _self.closest('.modal').modal('hide')
                    })
                    .finally(() => {
                        _self.removeClass('button-loading')
                    })
            })

            $(document).on('click', '.delete-many-entry-trigger', (event) => {
                event.preventDefault()
                let _self = $(event.currentTarget)

                let table = _self.closest('.table-wrapper').find('.table').prop('id')

                let ids = []

                $('#' + table)
                    .find('.checkboxes:checked')
                    .each((i, el) => {
                        ids[i] = $(el).val()
                    })

                if (ids.length === 0) {
                    Tec.showError(
                        TecVariables.languages.tables.please_select_record
                            ? TecVariables.languages.tables.please_select_record
                            : 'Please select at least one record to perform this action!'
                    )
                    return false
                }

                $('.delete-many-entry-button')
                    .data('href', _self.prop('href'))
                    .data('parent-table', table)
                    .data('class-item', _self.data('class-item'))

                $('.delete-many-modal').modal('show')
            })

            $('.delete-many-entry-button').on('click', (event) => {
                event.preventDefault()

                let _self = $(event.currentTarget)

                _self.addClass('button-loading')

                let $table = $('#' + _self.data('parent-table'))

                let ids = []
                $table.find('.checkboxes:checked').each((i, el) => {
                    ids[i] = $(el).val()
                })

                $httpClient
                    .make()
                    .delete(_self.data('href'), {
                        ids: ids,
                        class: _self.data('class-item'),
                    })
                    .then(({ data }) => {
                        Tec.showSuccess(data.message)

                        $table.find('.table-check-all').prop('checked', false).prop('indeterminate', false)

                        window.LaravelDataTables[_self.data('parent-table')].draw()

                        _self.closest('.modal').modal('hide')
                    })
                    .finally(() => {
                        _self.removeClass('button-loading')
                    })
            })

            $(document).on('click', '[data-trigger-bulk-action]', (event) => {
                event.preventDefault()

                const _self = $(event.currentTarget)

                const tableId = _self.closest('.table-wrapper').find('.table').prop('id')

                const ids = []

                $(`#${tableId}`)
                    .find('.checkboxes:checked')
                    .each((i, el) => ids.push($(el).val()))

                if (ids.length === 0) {
                    Tec.showError(
                        TecVariables.languages.tables.please_select_record
                            ? TecVariables.languages.tables.please_select_record
                            : 'Please select at least one record to perform this action!'
                    )

                    return false
                }

                $('.confirm-trigger-bulk-actions-button')
                    .data('href', _self.prop('href'))
                    .data('method', _self.data('method'))
                    .data('table-id', tableId)
                    .data('table-target', _self.data('table-target'))
                    .data('target', _self.data('target'))

                const modal = $('.bulk-action-confirm-modal')

                modal.find('.modal-title > strong').text(_self.data('confirmation-modal-title'))
                modal.find('.modal-body > div').text(_self.data('confirmation-modal-message'))
                modal.find('button.btn-warning').text(_self.data('confirmation-modal-cancel-button'))
                modal.find('button.confirm-trigger-bulk-actions-button').text(_self.data('confirmation-modal-button'))

                modal.modal('show')
            })

            $(document).on('click', '.confirm-trigger-bulk-actions-button', (event) => {
                event.preventDefault()

                const _self = $(event.currentTarget)

                _self.addClass('button-loading')

                const tableId = _self.data('table-id')
                const method = _self.data('method').toLowerCase() || 'post'

                const $table = $(`#${tableId}`)

                const ids = []

                $table.find('.checkboxes:checked').each((i, el) => ids.push($(el).val()))

                $httpClient
                    .make()
                    [method](_self.data('href'), {
                        ids: ids,
                        bulk_action: 1,
                        bulk_action_table: _self.data('table-target'),
                        bulk_action_target: _self.data('target'),
                    })
                    .then(({ data }) => {
                        Tec.showSuccess(data.message)

                        $table.find('.table-check-all').prop('checked', false).prop('indeterminate', false)

                        window.LaravelDataTables[tableId].draw()

                        _self.closest('.modal').modal('hide')
                    })
                    .finally(() => {
                        _self.removeClass('button-loading')
                    })
            })

            $(document).on('click', '[data-dt-single-action]', (event) => {
                event.preventDefault()

                const _self = $(event.currentTarget)

                const tableId = _self.closest('.table-wrapper').find('.table').prop('id')

                if (_self.data('confirmation-modal')) {
                    $('.confirm-trigger-single-action-button')
                        .data('href', _self.prop('href'))
                        .data('method', _self.data('method'))
                        .data('table-id', tableId)

                    const modal = $('.single-action-confirm-modal')

                    modal.find('.modal-title > strong').text(_self.data('confirmation-modal-title'))
                    modal.find('.modal-body > div').text(_self.data('confirmation-modal-message'))
                    modal.find('button.btn-warning').text(_self.data('confirmation-modal-cancel-button'))
                    modal
                        .find('button.confirm-trigger-single-action-button')
                        .text(_self.data('confirmation-modal-button'))

                    modal.modal('show')
                } else {
                    triggerSingleAction(tableId, _self.prop('href'), _self.data('method'))
                }
            })

            $(document).on('click', '.confirm-trigger-single-action-button', (event) => {
                event.preventDefault()

                const _self = $(event.currentTarget)

                _self.addClass('button-loading')

                triggerSingleAction(
                    _self.data('table-id'),
                    _self.data('href'),
                    _self.data('method'),
                    () => {
                        _self.closest('.modal').modal('hide')

                        _self.removeClass('button-loading')
                    },
                    () => {
                        _self.removeClass('button-loading')
                    }
                )
            })

            const triggerSingleAction = (tableId, url, method, onSuccess, onError) => {
                const $table = $(`#${tableId}`)
                const $method = method.toLowerCase() || 'post'

                $httpClient
                    .make()
                    [$method](url)
                    .then(({ data }) => {
                        Tec.showSuccess(data.message)

                        $table.find('.table-check-all').prop('checked', false).prop('indeterminate', false)

                        window.LaravelDataTables[tableId].draw()

                        typeof onSuccess === 'function' && onSuccess.apply(this, data)
                    })
                    .catch((error) => {
                        typeof onError === 'function' && onError.apply(this, error)
                    })
            }

            $(document).on('click', '.bulk-change-item', (event) => {
                event.preventDefault()
                let _self = $(event.currentTarget)

                let table = _self.closest('.table-wrapper').find('.table').prop('id')

                let ids = []
                $('#' + table)
                    .find('.checkboxes:checked')
                    .each((i, el) => {
                        ids[i] = $(el).val()
                    })

                if (ids.length === 0) {
                    Tec.showError(
                        TecVariables.languages.tables.please_select_record
                            ? TecVariables.languages.tables.please_select_record
                            : 'Please select at least one record to perform this action!'
                    )
                    return false
                }

                that.loadBulkChangeData(_self)

                $('.confirm-bulk-change-button')
                    .data('parent-table', table)
                    .data('class-item', _self.data('class-item'))
                    .data('key', _self.data('key'))
                    .data('url', _self.data('save-url'))
                $('.modal-bulk-change-items').modal('show')
            })

            $(document).on('click', '.confirm-bulk-change-button', (event) => {
                event.preventDefault()
                let _self = $(event.currentTarget)
                let value = _self.closest('.modal').find('.input-value').val()
                let inputKey = _self.data('key')

                let $table = $('#' + _self.data('parent-table'))

                let ids = []
                $table.find('.checkboxes:checked').each((i, el) => {
                    ids[i] = $(el).val()
                })

                _self.addClass('button-loading')

                $httpClient
                    .make()
                    .post(_self.data('url'), {
                        ids: ids,
                        key: inputKey,
                        value: value,
                        class: _self.data('class-item'),
                    })
                    .then(({ data }) => {
                        Tec.showSuccess(data.message)

                        $table.find('.table-check-all').prop('checked', false).prop('indeterminate', false)

                        $.each(ids, (index, item) => {
                            window.LaravelDataTables[_self.data('parent-table')]
                                .row($table.find('.checkboxes[value="' + item + '"]').closest('tr'))
                                .remove()
                                .draw()
                        })
                        _self.closest('.modal').modal('hide')
                    })
                    .finally(() => {
                        _self.removeClass('button-loading')
                    })
            })
        }

        loadBulkChangeData($element) {
            let $modal = $('.modal-bulk-change-items')

            $httpClient
                .make()
                .get($modal.find('.confirm-bulk-change-button').data('load-url'), {
                    class: $element.data('class-item'),
                    key: $element.data('key'),
                })
                .then((response) => {
                    let data = $.map(response.data.data, (value, key) => {
                        return { id: key, name: value }
                    })

                    let $parent = $('.modal-bulk-change-content')

                    $parent.html(response.data.html)

                    let $input = $modal.find('input[type=text].input-value')
                    if ($input.length) {
                        $input.typeahead({ source: data })
                        $input.data('typeahead').source = data
                    }

                    Tec.initResources()
                })
        }

        handleActionsExport() {
            $(document).on('click', '.export-data', (event) => {
                let _self = $(event.currentTarget)
                let table = _self.closest('.table-wrapper').find('.table').prop('id')

                let ids = []
                $('#' + table)
                    .find('.checkboxes:checked')
                    .each((i, el) => {
                        ids[i] = $(el).val()
                    })

                event.preventDefault()

                $httpClient
                    .make()
                    .post(_self.prop('href'), {
                        'ids-checked': ids,
                    })
                    .then(({ data }) => {
                        let a = document.createElement('a')
                        a.href = data.file
                        a.download = data.name
                        document.body.appendChild(a)
                        a.trigger('click')
                        a.remove()
                    })
            })
        }
    }

    $(document).ready(() => {
        new TableManagement()
    })
})(jQuery, jQuery.fn.dataTable)
