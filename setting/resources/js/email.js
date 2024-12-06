$(() => {
    $('[data-bb-toggle="test-email-send"]').on('click', (event) => {
        event.preventDefault()
        let _self = $(event.currentTarget)
        let form = new FormData(_self.closest('form')[0])

        Tec.showButtonLoading(_self)

        $httpClient
            .make()
            .postForm(_self.data('url'), form)
            .then(({ data }) => {
                Tec.showSuccess(data.message)
                $('#send-test-email-modal').modal('show')
            })
            .finally(() => {
                Tec.hideButtonLoading(_self)
            })
    })

    $('#send-test-email-btn').on('click', (event) => {
        event.preventDefault()
        let _self = $(event.currentTarget)

        Tec.showButtonLoading(_self)

        $httpClient
            .make()
            .post(_self.data('url'), {
                email: _self.closest('.modal-content').find('input[name=email]').val(),
                template: _self.closest('.modal-content').find('select[name=template]').val(),
            })
            .then(({ data }) => {
                Tec.showSuccess(data.message)
                _self.closest('.modal').modal('hide')
            })
            .finally(() => {
                Tec.hideButtonLoading(_self)
            })
    })
})
