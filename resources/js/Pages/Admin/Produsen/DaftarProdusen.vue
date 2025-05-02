<script setup>
import { ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    'nomor': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'nama_produsen': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'alamat': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'no_telp': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'email': { value: null, matchMode: FilterMatchMode.CONTAINS },
})

const props = defineProps({
    dataProdusen : Object,
})

const emit = defineEmits(['editData'])

function formatRupiah(angka) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(angka);
}

function formatTanggal(tanggal) {
  const [tahun, bulan, hari] = tanggal.split('-')
  return `${hari}/${bulan}/${tahun}`
}

const editProdusen = id_produsen =>
{
    const dataProdusen = props.dataProdusen.filter((produsen) => produsen.id_produsen === id_produsen).map(item => {
        const copy = {...item}
        delete copy.nomor
        return copy
    })

    emit('editData', dataProdusen)
}

</script>

<template>
    <div class="flex flex-col">
        <DataTable :value="props.dataProdusen" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters" :global-filter-fields="['nomor','nama_produsen','alamat','no_telp','email',]">
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
            <Column header="Status">
                <template #body="{data}">
                    <Tag :value="data.status?'Tervalidasi':'Menunggu Validasi'" :severity="data.status?'success':'danger'"/>
                </template>
            </Column>
            <Column sortable header="Tanggal Pendaftaran" style="min-width: 240px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_pendaftaran) }}</span>
                </template>
            </Column>
        </DataTable>

    </div>
</template>

<style scoped>
</style>
