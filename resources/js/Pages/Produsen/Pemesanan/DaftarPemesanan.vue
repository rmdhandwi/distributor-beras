<script setup>
import { ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
})

const props = defineProps({
    pageType : String,
    dataPemesanan : Object,
})

const emit = defineEmits(['confirmData'])

function formatTanggal(tanggal) {
  const [tahun, bulan, hari] = tanggal.split('-')
  return `${hari}/${bulan}/${tahun}`
}

const confirmPemesanan = id_pemesanan =>
{
    const dataFilter = props.dataPemesanan.filter((gudang) => gudang.id_pemesanan === id_pemesanan)
    emit('confirmData', dataFilter)
}

const switchStatus = status =>
{
    switch(status)
    {
        case 'Pending' : return 'warn';

        case 'Telah Dikonfirmasi' : return 'info';

        case 'Bukti Diterima' : return 'success';
    }
}

</script>

<template>
    <div class="flex flex-col">
        <DataTable :value="props.dataPemesanan" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters">
            <template #header>
                <div class="flex justify-between items-center gap-x-2">
                    <IconField class="w-full">
                        <InputIcon>
                            <i class="pi pi-search me-4" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Cari Data Pemesanan" size="small" fluid/>
                    </IconField>
                    <Button icon="pi pi-print" severity="contrast" variant="outlined" label="CSV" size="small" />
                </div>
            </template>
            <template #footer>
                <span>Jumlah Data Pemesanan ({{ props.dataPemesanan.length }})</span>
            </template>
            <template #loading>
                <span class="flex justify-center">Sedang Memuat Data...</span>
            </template>
            <template #empty>
                <span class="flex justify-center">Tidak Ada Pemesanan</span>
            </template>
            <Column sortable field="nomor" header="No" frozen/>
            <Column sortable header="Nama Beras" style="min-width: 180px;" frozen>
                <template #body="{data}">
                    {{ data.beras?.nama_beras }}
                </template>
            </Column>
            <Column sortable header="Produsen" style="min-width: 180px;" frozen>
                <template #body="{data}">
                    {{ data.produsen?.nama_produsen }}
                </template>
            </Column>
            <Column field="jmlh" header="Jumlah Pesan" style="min-width: 100px;"/>
            <Column sortable header="Tanggal Pemesanan" style="min-width: 100px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_pemesanan) }}</span>
                </template>
            </Column>
            <Column header="Status" style="min-width: 150px;">
                <template #body="{data}">
                    <Tag :value="data.status_pesanan" :severity="switchStatus(data.status_pesanan)"/>

                </template>
            </Column>
            <Column field="catatan" header="Catatan" style="min-width: 150px;"/>
            <Column header="Action" frozen align-frozen="right"v-if="pageType==='Konfirmasi'">
                <template #body="{data}">
                    <div class="flex place-content-center gap-2">
                        <Button @click="confirmPemesanan(data.id_pemesanan)" severity="info" size="small" icon="pi pi-pen-to-square"/>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
