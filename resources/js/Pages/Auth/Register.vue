<script setup>
import { onMounted } from 'vue'
import { Head, router, useForm } from '@inertiajs/vue3'

import { useToast } from 'primevue'

import GuestLayout from '@/Layouts/GuestLayout.vue'

onMounted(()=>
{
    checkNotif()
})

const props = defineProps({
    flash : Object,
});

const toast = useToast()

const registForm = useForm({
    nama : null,
    username : null,
    password : null,
    alamat : null,
    no_telp : null,
    email : null,
})

const checkNotif = () =>
{
    const notifStatus = registForm.errors?'error':'success'

    if(props.flash.notif_status)
    {
        setTimeout(() =>
        {
            toast.add({
                severity : props.flash.notif_status ?? notifStatus,
                summary : 'Notifikasi',
                detail : props.flash.notif_message,
                life : 4000,
                group : 'tc'
            })
        },1000)
    }
}

const submitForm = () => {
    registForm.post(route('register.submit'), {
        onFinish: () => {
            checkNotif()
        }
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Daftar Akun"/>
        <form @submit.prevent class="flex flex-col gap-4 mt-4" autocomplete="off">
            <!-- Nama -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="nama" v-model="registForm.nama" fluid/>
                    <label for="nama">Nama Produsen</label>
                </FloatLabel>
                <span class="text-red-500" v-if="registForm.errors.nama"> {{ registForm.errors.nama }} </span>
            </div>

            <!-- Username -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="username" v-model="registForm.username" fluid/>
                    <label for="username">Username</label>
                </FloatLabel>
                <span class="text-red-500" v-if="registForm.errors.username"> {{ registForm.errors.username }} </span>
            </div>

            <!-- Email -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="email" v-model="registForm.email" fluid/>
                    <label for="email">Email</label>
                </FloatLabel>
                <span class="text-red-500" v-if="registForm.errors.email"> {{ registForm.errors.email }} </span>
            </div>

            <!-- Password -->
            <div>
                <FloatLabel variant="on">
                    <Password id="password_label" v-model="registForm.password" toggleMask fluid/>
                    <label for="password_label">Password</label>
                </FloatLabel>
                <span class="text-red-500" v-if="registForm.errors.password"> {{ registForm.errors.password }} </span>
            </div>

             <!-- Alamat -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="alamat" v-model="registForm.alamat" fluid/>
                    <label for="alamat">Alamat</label>
                </FloatLabel>
                <span class="text-red-500" v-if="registForm.errors.alamat"> {{ registForm.errors.alamat }} </span>
            </div>

            <!-- Telepon -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="noTelp" v-model="registForm.no_telp" fluid/>
                    <label for="noTelp">No Telepon</label>
                </FloatLabel>
                <span class="text-red-500" v-if="registForm.errors.no_telp"> {{ registForm.errors.no_telp }} </span>
            </div>

            <Button @click="submitForm" :label="registForm.processing?null:'Buat Akun'" class="min-w-full" :disabled="registForm.processing" :icon="registForm.processing?'pi pi-spin pi-spinner':null" />

            <Button @click="router.visit(route('login'))" label="Login" variant="link" class="underline"/>

        </form>

    </GuestLayout>
</template>

<style scoped>
</style>
