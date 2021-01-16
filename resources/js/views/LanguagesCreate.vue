<template>
    <div>
    <heading class="mb-3">{{ __('Add new language') }}</heading>
    <card class="overflow-hidden">
        <form @submit.prevent="createLanguage" autocomplete="off">
            <form-text-field
                :field="{
                    attribute: 'name',
                    name: 'Nom',
                    placeholder: __('English')
                }"
                :errors="validationErrors"
            />
            <form-text-field
                :field="{
                    attribute: 'locale',
                    name: 'Locale',
                    placeholder: __('en')
                }"
                :errors="validationErrors"
            />

            <!-- Create Button -->
            <div class="bg-30 flex px-8 py-4">
                <button class="btn btn-default btn-primary ml-auto">
                    {{ __('Add this language') }}
                </button>
            </div>

        </form>
    </card>
    </div>

</template>

<script>
import { Errors } from 'laravel-nova';
export default {
    data() {
        return {
           validationErrors: new Errors(),
        }
    },

    methods: {
        async createLanguage() {

            try {
                await axios.post('/nova-vendor/nova-translation/languages', {
                    name: document.getElementById('name').value,
                    locale: document.getElementById('locale').value
                });

                this.$toasted.show(this.__('Language added'), { type: 'success' })

                this.$router.push({
                    name: 'nova-translation'
                })
            } catch (error) {
                if (error.response.status == 422) {
                    this.validationErrors = new Errors(error.response.data.errors)
                    this.$toasted.show(this.__('Data entered is incorrect'), { type: 'error' })
                }
            }
        }
    }
}
</script>
