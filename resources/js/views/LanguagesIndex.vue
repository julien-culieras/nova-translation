<template>
    <div>
        <div class="flex items-center mb-6">
            <heading class="flex">{{ __('Languages') }}</heading>
            <div class="flex w-full justify-end">
                <router-link
                    class="cursor-pointer btn btn-default btn-primary"
                    :to="{ name: 'nova-translation.languages.create'}"
                    title="Ajouter une langue"
                >
                    {{ __('Add language') }}
                </router-link>
            </div>
        </div>
        <loading-view :loading="initialLoading">
            <loading-card :loading="loading" class="card">
                <table v-if="languages.length > 0"
                    class="table w-full"
                    cellpadding="0"
                    cellspacing="0"
                    data-testid="resource-table"
                >
                    <thead>
                        <tr>
                            <th class="text-left">{{ __('Language') }}</th>
                            <th class="text-left">{{ __('Locale') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="language in languages" :key="`language-${language.id}`">
                            <td>{{ language.name }}</td>
                            <td>{{ language.language }}</td>
                            <td>
                                <router-link
                                    class="cursor-pointer text-70 hover:text-primary"
                                    :to="{
                                        name: 'nova-translation.languages.translations.index',
                                        title: __('View translations'),
                                        query: {
                                            language: language.language
                                        }
                                    }"
                                    :title="__('View translations')"
                                >
                                    <icon type="view" width="22" height="18" view-box="0 0 22 16" />
                                </router-link>
                                <button
                                    title="Delete"
                                    class="appearance-none mr-3 text-70 hover:text-danger"
                                    @click.prevent="openDeleteModal(language)"
                                >
                                    <icon type="delete" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </loading-card>
        </loading-view>
        <portal to="modals">
            <transition name="fade">
                <delete-resource-modal
                    v-if="deleteModalOpen"
                    @confirm="confirmDelete"
                    @close="closeDeleteModal"
                    mode="delete"
                >
                    <div class="p-8">
                        <heading :level="2" class="mb-6">
                            {{ __('Delete language') }}
                        </heading>
                        <p class="text-80 leading-normal">
                            {{ __('Are you sure want to delete this language ?') }} ({{ deleting.name ? deleting.name : deleting.language }}), {{ __('matching translations will delete too') }}.
                        </p>
                    </div>
                </delete-resource-modal>
            </transition>
        </portal>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            initialLoading: true,
            loading: false,
            languages: [],
            deleteModalOpen: false,
            deleting: null,
        }
    },

    methods: {
        listLanguages() {
            Nova.request().get('/nova-vendor/nova-translation/languages')
                .then((response) => {
                    this.languages = response.data;
                    this.initialLoading = false;
                    this.loading = false;
                })
        },
        openDeleteModal(language) {
            this.deleteModalOpen = true;
            this.deleting = language;
        },

        closeDeleteModal() {
            this.deleteModalOpen = false;
            this.deleting = null;
        },

        confirmDelete() {
            axios({
                method: 'delete',
                url: `/nova-vendor/nova-translation/languages/${this.deleting.id}`,
            }).then(res => {
                if (res.data.success) {
                    this.languages = this.languages.filter(language => language.id !== this.deleting.id)
                    this.$toasted.show(this.__('Language deleted'), { type: 'success' })
                    this.closeDeleteModal()
                } else {
                    this.$toasted.show(this.__('An error occurred, try again ...'), { type: 'error' })
                    this.closeDeleteModal()
                }
            }).catch(err => {
                this.$toasted.show(this.__('An error occurred, try again ...'), { type: 'error' })
                this.closeDeleteModal()
            })
        },
    },

    created() {
        this.listLanguages()
    },
}
</script>
