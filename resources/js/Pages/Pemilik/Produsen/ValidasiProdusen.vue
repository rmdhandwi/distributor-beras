<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

import {FilterMatchMode} from '@primevue/core/api'
import { useConfirm, useToast } from 'primevue'

const props = defineProps({
    dataProdusen : Object,
})

const confirm = useConfirm()
const toast = useToast()

const emit = defineEmits(['refreshPage'])


const produsenForm = useForm({
    id_produsen : null
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    'nomor': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'nama_produsen': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'alamat': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'no_telp': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'email': { value: null, matchMode: FilterMatchMode.CONTAINS },
})

function formatTanggal(tanggal) {
  const [tahun, bulan, hari] = tanggal.split('-')
  return `${hari}/${bulan}/${tahun}`
}

const confirmValidasi = id =>
{
    produsenForm.id_produsen = id
    confirm.require({
        message: 'Validasi Produsen?',
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'danger'
        },
        acceptProps: {
            label: 'Validasi',
            severity: 'success'
        },
        accept : () => {
            toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'Memvalidasi Produsen...', life: 3000 });
            setTimeout(() => {
                produsenForm.post(route('pemilik.produsen.validasi'), {
                    onError : () => {
                        toast.add({
                            severity : 'error',
                            summary : 'Notifikasi',
                            detail : 'Terjadi kesalahan',
                            life : 3000,
                        })
                    },
                    onSuccess : () => {
                        produsenForm.reset()
                        produsenForm.clearErrors()
                        emit('refreshPage')
                    }
                })
            }, 3000)
        },
    });
}

</script>

<template>
    <div class="flex flex-col">
        <DataTable :value="props.dataProdusen" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters" :global-filter-fields="['nomor','nama_produsen','alamat','no_telp','email']">
            <template #header>
                <div class="flex justify-between items-center gap-x-2">
                    <IconField class="w-full">
                        <InputIcon>
                            <i class="pi pi-search me-4" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Cari Data Produsen" size="small" fluid/>
                    </IconField>
                    <Button icon="pi pi-print" severity="contrast" variant="outlined" label="CSV" size="small" />
                </div>
            </template>
            <template #footer>
                <span>Jumlah Data Produsen ({{ props.dataProdusen.length }})</span>
            </template>
            <template #loading>
                <span class="flex justify-center">Sedang Memuat Data...</span>
            </template>
            <template #empty>
                <span class="flex justify-center">Tidak Ada Produsen</span>
            </template>
            <Column sortable field="nomor" header="No" frozen/>
            <Column sortable field="nama_produsen" header="Nama Produsen" style="min-width: 180px;" frozen/>
            <Column field="alamat" header="Alamat" style="min-width: 200px;"/>
            <Column field="no_telp" header="No Telp" style="min-width: 160px;"/>
            <Column field="email" header="Email" style="min-width: 200px;"/>
            <Column header="Status" style="min-width: 180px;">
                <template #body>
                    <Tag value="Menunggu Validasi" severity="danger"/>
                </template>
            </Column>
            <Column sortable header="Tanggal Pendaftaran" style="min-width: 240px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_pendaftaran) }}</span>
                </template>
            </Column>
            <Column header="Action" frozen align-frozen="right">
                <template #body="{data}">
                    <div class="flex place-content-center gap-2">
                        <Button @click="confirmValidasi(data.id_produsen)" severity="success" label="Verifikasi" size="small" iconPos="right" icon="pi pi-check"/>
                    </div>
                </template>
            </Column>
        </DataTable>

    </div>
</template>

<style scoped>
</style>
