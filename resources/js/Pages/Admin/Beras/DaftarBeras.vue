<script setup>
import { ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
})

const props = defineProps({
    dataBeras : Object,
})

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
</script>

<template>
     <div class="flex flex-col">
        <DataTable :value="props.dataBeras" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters">
            <template #header>
                <div class="flex justify-between items-center gap-x-2">
                    <IconField class="w-full">
                        <InputIcon>
                            <i class="pi pi-search me-4" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Cari Data Beras" size="small" fluid/>
                    </IconField>
                    <Button icon="pi pi-print" severity="contrast" variant="outlined" label="CSV" size="small" />
                </div>
            </template>
            <template #footer>
                <span>Jumlah Data Beras ({{ props.dataBeras.length }})</span>
            </template>
            <template #loading>
                <span class="flex justify-center">Sedang Memuat Data...</span>
            </template>
            <template #empty>
                <span class="flex justify-center">Tidak Ada Beras</span>
            </template>
            <Column sortable field="nomor" header="No" frozen/>
            <Column sortable field="nama_beras" header="Nama Beras" style="min-width: 180px;" frozen/>
            <Column sortable field="produsen.nama_produsen" header="Produsen" style="min-width: 180px;"/>
            <Column field="jenis_beras" header="Jenis Beras" style="min-width: 140px;"/>
            <Column sortable field="harga_jual" header="Harga Jual" style="min-width: 180px;">
                <template #body="{data}">
                    <span>{{ formatRupiah(data.harga_jual) }}</span>
                </template>
            </Column>
            <Column sortable field="stok_awal" header="Stok Awal" style="min-width: 140px;"/>
            <Column sortable field="stok_tersedia" header="Stok Tersedia" style="min-width: 160px;"/>
            <Column header="Status">
                <template #body="{data}">
                    <Badge :value="data.status_beras" :severity="data.status_beras==='Tersedia'?'success':'danger'"/>
                </template>
            </Column>
            <Column field="kualitas_beras" header="Kualitas" style="min-width: 240px;"/>
            <Column field="sertifikat_beras" header="Sertifikat" style="min-width: 240px;"/>
            <Column sortable header="Tanggal Produksi" style="min-width: 240px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_produksi) }}</span>
                </template>
            </Column>
            <Column sortable header="Tanggal Kadaluarsa" style="min-width: 240px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_kadaluarsa) }}</span>
                </template>
            </Column>
        </DataTable>

    </div>
</template>

<style scoped>
</style>
