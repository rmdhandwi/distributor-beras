<script setup>
import { onMounted } from 'vue'
import { Head, router, useForm } from '@inertiajs/vue3'

import { useToast } from 'primevue'
// import Layouts
import GuestLayout from '@/Layouts/GuestLayout.vue'

onMounted(()=>
{
    checkNotif()
})

const props = defineProps({
    flash : Object,
});

const toast = useToast()


const loginForm = useForm({
    username: null,
    password: null,
    role: null,
});

const userRoles = [
    { role : 'Admin'},
    { role : 'Produsen'},
    { role : 'Pemilik'},
]

const checkNotif = () =>
{
    const notifStatus = loginForm.errors?'error':'success'

    if(props.flash.notif_status??loginForm.errors.username??loginForm.errors.password)
    {
        setTimeout(() =>
        {
            toast.add({
                severity : props.flash.notif_status??notifStatus,
                summary : 'Notifikasi',
                detail : props.flash.notif_message??loginForm.errors.username??loginForm.errors.password,
                life : 2000,
                group : 'tc'
            })
        },1000)
    }
}

const submitForm = () => {
    loginForm.post(route('login.submit'), {
        onFinish: () => {
            checkNotif()
        }
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Login"/>
            <form @submit.prevent="submitForm" class="flex flex-col gap-4 mt-4" autocomplete="off">
                <div>
                    <FloatLabel variant="on">
                        <InputText id="on_label" v-model="loginForm.username" fluid/>
                        <label for="on_label">Username</label>
                    </FloatLabel>
                    <span class="text-red-500" v-if="loginForm.errors.username"> {{ loginForm.errors.username }} </span>
                </div>

                <div>
                    <FloatLabel variant="on">
                        <Password id="password_label" v-model="loginForm.password" toggleMask fluid/>
                        <label for="password_label">Password</label>
                    </FloatLabel>
                    <span class="text-red-500" v-if="loginForm.errors.password"> {{ loginForm.errors.password }} </span>
                </div>

                <div>
                    <FloatLabel variant="on">
                        <Select v-model="loginForm.role" inputId="on_label" :options="userRoles" optionLabel="role" optionValue="role" fluid/>
                        <label for="on_label">Pilih Role</label>
                    </FloatLabel>
                </div>

                <Button type="submit" :label="loginForm.processing?null:'Login'" class="min-w-full" :disabled="loginForm.processing" :icon="loginForm.processing?'pi pi-spin pi-spinner':null" />
                <Button label="Registrasi Produsen" variant="outlined" @click="router.visit(route('register.page'))"/>
            </form>
    </GuestLayout>
</template>
