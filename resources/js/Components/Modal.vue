<template>
    <button
        @click="open()"
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        {{ button_text }}
    </button>

    <div :ref="id" aria-hidden="true" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button @click="close()" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" :data-modal-toggle="id">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>

                <div class="p-6">
                    <div class="mt-4">
                        <slot name="content"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            default: 'pop-up'
        },
        position: {
            type: String,
            default: 'center'
        },
        button_text: {
            type: String,
            default: 'Open Modal'
        },
        button_class: {
            type: String,
            default: 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'
        }
    },
    name: "Modal",
    data() {
        return {
            modal: null
        }
    },
    mounted() {
        // set the modal menu element
        const targetEl = this.$refs[this.id];

        // options with default values
        const options = {
            placement: this.position,
            backdropClasses: 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40',
            onHide: () => {
                console.log('modal is hidden');
            },
            onShow: () => {
                console.log('modal is shown');
            },
            onToggle: () => {
                console.log('modal has been toggled');
            }
        };

        this.modal = new Modal(targetEl, options);
    },
    methods: {
        close() {
            this.modal.hide()
        },
        open() {
            this.modal.show()
        }
    }
}
</script>

<style scoped>

</style>
