class CacheManagement {
    init() {
        $(document).on('click', '.btn-clear-cache', (event) => {
            event.preventDefault()

            let _self = $(event.currentTarget)

            Tec.showButtonLoading(_self)

            $httpClient
                .make()
                .post(_self.data('url'), { type: _self.data('type') })
                .then(({ data }) => Tec.showSuccess(data.message))
                .finally(() => Tec.hideButtonLoading(_self))
        })
    }
}

$(() => {
    new CacheManagement().init()
})
