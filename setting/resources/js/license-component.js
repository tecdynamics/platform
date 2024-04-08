import LicenseForm from './components/LicenseComponent.vue'
        //'./components/LicenseForm.vue'

if (typeof vueApp !== 'undefined') {
    vueApp.booting((vue) => {
        vue.component('v-license-form', LicenseForm)
    })
}
