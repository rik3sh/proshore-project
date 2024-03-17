<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { router } from "@inertiajs/vue3";
import { ref } from 'vue'

defineProps({
    questionnaires: Object,
    // questions: Object,
    // showDialog: false,
});

const questions = ref([]) // Use [] to initialize an array
const showDialog = ref(false)

function sendMail(id) {
    if (confirm("Are you sure you want to send?")) {
        router.post(route('send', id));
    }
}

function opemModal(allQuestions, idx) {
    showDialog.value = true;

    questions.value = allQuestions;
}

function closeModal() {
    showDialog.value = false;
}

</script>

<template>
    <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true" v-if="showDialog">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Questions</h3>
                                <div class="mt-2">
                                    <ul class="max-w-md divide-y divide-gray-900 dark:questions-gray-900">
                                        <li class="pb-3 sm:pb-4" v-for="qu in questions">
                                            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ qu.title }}
                                                    </p>

                                                    <span v-for="opt in qu.options" 
                                                    class="text-sm truncate dark:text-red-900"
                                                    :class="{'dark:text-green-900 font-bold': opt.is_correct}"
                                                    >
                                                        {{ opt.answer }} &nbsp;
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="closeModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <Head title="Questionnaires" />
    
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Questionnaires</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div v-if="$page.props.flash.success" class="text-blue-600 mb-4">
                    {{ $page.props.flash.success }}
                </div>
                
                <Link :href="route('questionnaires.create')" class="inline-block px-2 py-2 bg-blue-500 text-white rounded mb-2">Add new</Link>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900"> 
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left">ID</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Title</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Expiry Date</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                <tr v-for="q in questionnaires">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ q.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ q.title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ q.expiry_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">
                                        <button type="button" @click="sendMail(q.id)" class="inline-block px-2 py-2 bg-green-500 text-white rounded mb-2">Mail Students</button> &nbsp;
                                        <button type="button" @click="opemModal(q.questions)" class="inline-block px-2 py-2 bg-orange-500 text-white rounded mb-2">View Questions</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
