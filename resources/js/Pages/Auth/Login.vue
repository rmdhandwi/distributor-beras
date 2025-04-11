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
    loginForm.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Login"/>
            <form @submit.prevent="submit" class="flex flex-col gap-4 mt-4" autocomplete="off">
                <FloatLabel variant="on">
                    <InputText id="on_label" v-model="loginForm.username" fluid/>
                    <label for="on_label">Username</label>
                </FloatLabel>

                <FloatLabel variant="on">
                    <Password id="on_label" v-model="loginForm.password" toggleMask fluid/>
                    <label for="on_label">Password</label>
                </FloatLabel>

                <Button type="submit" :label="loginForm.processing?null:'Login'" class="min-w-full" :disabled="loginForm.processing" :icon="loginForm.processing?'pi pi-spin pi-spinner':null" />

            </form>
    </GuestLayout>
</template>
