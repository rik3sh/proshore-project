<script setup>
import ExamLayout from '@/Layouts/ExamLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { reactive } from 'vue'
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

defineProps({
    hasError: false,
    questionnaireUser: Object
});

const $toast = useToast();

const form = reactive({
    option_id: [],
    code: ''
})

function handleFormSubmit(questionnaireUser) {
    const selectedOptions = form.option_id.filter(option => option !== null)

    if (selectedOptions.length !== questionnaireUser.questionnaire.questions.length) {
        let instance = $toast.error('Some of the questions are left to be answered.');
        return;
    }
    
    form.option_id = selectedOptions;
    form.code = questionnaireUser.exam_code;
    
    // Post the answers to the server when all questions are answered
    router.post(route('exam-store'), form);
}

</script>

<template>
    <Head title="Exam" />
    
    <ExamLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ questionnaireUser.questionnaire.title }} <span class="text-red-600">({{ questionnaireUser.user.name }})</span></h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-for="(ques, index) in questionnaireUser.questionnaire.questions" class="my-5">
                            <form id="eForm" @submit.prevent="handleFormSubmit(questionnaireUser)">
                                <div>
                                    <div>
                                        {{ ques.title }}
                                    </div>
                                </div>
                                
                                <div class="py-4">
                                    <div v-for="opt in ques.options">
                                        <input type="radio" v-model="form.option_id[index]" name="option_id_{{ ques.id }}" :value="opt.id"> {{ opt.answer }}
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div v-if="hasError" class="text-red-600">
                            'Please make sure to select an option for each question.'
                        </div>

                        <div class="py-2">
                            <button type="submit" form="eForm" class="inline-block px-2 py-2 bg-blue-500 text-white rounded">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ExamLayout>
</template>
