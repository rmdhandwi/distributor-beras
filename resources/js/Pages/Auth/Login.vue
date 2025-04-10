<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

// import Layouts
import GuestLayout from '@/Layouts/GuestLayout.vue'


defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const loginForm = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Login"/>
            <form @submit.prevent="submit" class="flex flex-col gap-4 mt-4">
                <FloatLabel variant="on">
                    <InputText fluid id="on_label" v-model="loginForm.username"/>
                    <label for="on_label">Username</label>
                </FloatLabel>

                <FloatLabel variant="on">
                    <Password id="on_label" v-model="loginForm.password" fluid/>
                    <label for="on_label">Password</label>
                </FloatLabel>

            </form>
    </GuestLayout>
</template>
