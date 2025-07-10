<script setup>
import { onBeforeMount, ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'
import { useForm } from '@inertiajs/vue3'
import { useConfirm, useToast } from 'primevue'

onBeforeMount(() =>
{
    dataRekeningFix.value = props.dataRekening
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
})

const props = defineProps({
    dataRekening : Object,
})

const emit = defineEmits(['refreshPage'])

const confirm = useConfirm()
const toast = useToast()

const dataRekeningFix = ref([])

const rekForm = useForm({
    id_rekening : null,
    no_rekening : null,
    nama_rekening : null,
})

const hapusRek = id =>
{
    const findRek = props.dataRekening.find( rek => rek.id_rekening === id)
    rekForm.id_rekening = id
    rekForm.no_rekening = findRek.no_rekening
    rekForm.nama_rekening = findRek.nama_rekening

    console.log(findRek)

    confirm.require({
        message: `Hapus rekening ${rekForm.nama_rekening+' - '+rekForm.no_rekening}?`,
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: `Hapus`,
            severity : 'danger',
        },
        accept: () => {

            rekForm.post(route('produsen.rekening.destroy'), {
                onError : () => {
                    toast.add({
                        severity : 'error',
                        summary : 'Notifikasi',
                        detail : 'Terjadi kesalahan',
                        life : 3000,
                    })
                },
                onSuccess : () => {
                    rekForm.reset()
                    rekForm.clearErrors()
                    emit('refreshPage')
                }
            })
        },
    })
}
</script>

<template>
    <div class="flex flex-col">
        <DataTable :value="dataRekeningFix" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters">
            <template #header>
                <div class="flex flex-col gap-y-2">
                    <!-- Basic Filter -->
                    <div class="flex justify-between items-center gap-x-2">
                        <IconField class="w-full">
                            <InputIcon>
                                <i class="pi pi-search me-4" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari Data Rekening" size="small" fluid/>
                        </IconField>
                    </div>
                </div>
            </template>
            <template #footer>
                <span>Jumlah Data Rekening ({{ dataRekeningFix.length }})</span>
            </template>
            <template #loading>
                <span class="flex justify-center">Sedang Memuat Data...</span>
            </template>
            <template #empty>
                <span class="flex justify-center">Tidak Ada Rekening</span>
            </template>
            <Column sortable field="nomor" header="No"/>
            <Column sortable field="no_rekening" header="Nomor Rekening"/>
            <Column sortable field="nama_rekening" header="Nama Rekening"/>
            <Column header="Action" frozen align-frozen="right">
                <template #body="{data}">
                    <div class="flex place-content-center gap-2">
                        <Button @click="hapusRek(data.id_rekening)" severity="danger" size="small" icon="pi pi-trash"/>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
