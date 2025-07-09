<script setup>
import { useForm } from '@inertiajs/vue3'
import { useConfirm, useToast } from 'primevue'

const emit = defineEmits(['refreshPage'])

const toast = useToast()
const confirm = useConfirm()

const rekeningForm = useForm({
    no_rekening : null,
    nama_rekening : null,
})

const submitRekening = () =>
{
    confirm.require({
        message: `Tambahkan rekening ${rekeningForm.nama_rekening ?? ''}?`,
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: `Tambah`,
        },
        accept: () => {

            rekeningForm.post(route('produsen.rekening.store'), {
                onError : () => {
                    toast.add({
                        severity : 'error',
                        summary : 'Notifikasi',
                        detail : 'Terjadi kesalahan',
                        life : 3000,
                    })
                },
                onSuccess : () => {
                    rekeningForm.reset()
                    rekeningForm.clearErrors()
                    emit('refreshPage')
                }
            })
        },
    })
}
</script>

<template>
    <form @submit.prevent class="flex flex-col gap-4 mt-4" autocomplete="off">
        <div>
            <FloatLabel variant="on">
                <InputText id="no_rekening" v-model="rekeningForm.no_rekening" fluid/>
                <label for="no_rekening">Nomor Rekening</label>
            </FloatLabel>
            <span class="text-red-500" v-if="rekeningForm.errors.no_rekening"> {{ rekeningForm.errors.no_rekening }}</span>
        </div>
        <div>
            <FloatLabel variant="on">
                <InputText id="nama_rekening" v-model="rekeningForm.nama_rekening" fluid/>
                <label for="nama_rekening">Nama Rekening</label>
            </FloatLabel>
            <span class="text-red-500" v-if="rekeningForm.errors.nama_rekening"> {{ rekeningForm.errors.nama_rekening }}</span>
        </div>
        <div>
            <Button @click="submitRekening" label="Submit"/>
        </div>
    </form>
</template>
