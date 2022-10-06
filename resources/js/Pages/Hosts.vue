<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/inertia-vue3';

defineProps({
    hosts: Object
})
</script>

<template>
    <Head title="Hosts"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Hosts
            </h2>
        </template>

        <div class="pb-12">
            <!-- This is an example component -->
            <div class="max-w-7xl mx-auto">

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div class="p-4 flex justify-between">
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                     viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input type="text" v-model="searchTerm" @keyup="search" id="table-search"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 pl-10 p-2.5  light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-blue-500 light:focus:border-blue-500"
                                   placeholder="Search for items">
                        </div>

                        <button
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add host
                        </button>
                    </div>


                    <button @click="modal.toggle()" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                        Toggle modal
                    </button>

                    <Modal :id="'add_host'" >
                        <template #content>
                            asdasd
                        </template>
                    </Modal>


                    <table class="w-full text-sm text-left text-gray-500 light:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 light:bg-gray-700 light:text-gray-400">
                        <tr>
                            <th scope="col" class="p-4">
                                <div class="flex items-center">
                                    <input id="checkbox-all-search" type="checkbox"
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 light:focus:ring-blue-600 light:ring-offset-gray-800 focus:ring-2 light:bg-gray-700 light:border-gray-600">
                                    <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Host
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Created At
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Updated At
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="(host, index) in mutableHosts"
                            :key="index"
                            class="bg-white border-b light:bg-gray-800 light:border-gray-700 hover:bg-gray-50 light:hover:bg-gray-600">
                            <td class="w-4 p-4">
                                <div class="flex items-center">
                                    <input id="checkbox-table-search-1" type="checkbox"
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 light:focus:ring-blue-600 light:ring-offset-gray-800 focus:ring-2 light:bg-gray-700 light:border-gray-600">
                                    <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                </div>
                            </td>
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 light:text-white whitespace-nowrap">
                                {{ host.host }}
                            </th>
                            <td class="px-6 py-4">
                                {{ host.description }}
                            </td>
                            <td class="px-6 py-4">
                                {{ host.created_at }}
                            </td>
                            <td class="px-6 py-4">
                                {{ host.updated_at }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="#"
                                   class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script>
import Modal from '../Components/Modal.vue'

export default {
    components: {Modal},
    data() {
        return {
            searchTerm: '',
            mutableHosts: '',
            add_modal: false
        }
    },
    methods: {
        search: window._.debounce(function () {
            if (this.searchTerm.length === 0) {
                return this.mutableHosts = this.hosts
            }

            axios.get(route('search-hosts'), {
                params: {
                    'searchTerm': this.searchTerm
                }
            })
                .then((data) => {
                    console.log(data);
                    this.mutableHosts = data.data.data;
                })
        }, 1000)
    },
    mounted() {
        this.mutableHosts = this.hosts;
    }
}
</script>
