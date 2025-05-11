<script setup>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'

const props = defineProps({
    backRoute : String,
    dataCetak : Object,
    tanggalCetak : String,
})

const showButton = ref(true)

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

const cetakLaporan = () =>
{
    showButton.value = false

    setTimeout(() => {
        window.print()
        showButton.value = true
    },1000)
}
</script>

<template>
    <Head title="Laporan Beras"/>
    <div class="p-2">
        <DataTable class="text-xs" :value="props.dataCetak" dataKey="index" showGridlines removable-sort striped-rows scrollable size="small">
            <template #header>
                <span class="flex justify-center text-lg font-bold">Laporan Data Beras</span>
            </template>
            <template #footer>
                <span>Tanggal Cetak Laporan : {{ props.tanggalCetak }}</span>
            </template>
            <template #loading>
                <span class="flex justify-center">Sedang Memuat Data...</span>
            </template>
            <template #empty>
                <span class="flex justify-center">Tidak Ada Beras</span>
            </template>
            <Column field="nomor" header="No" frozen/>
            <Column field="nama_beras" header="Nama Beras"/>
            <Column field="produsen.nama_produsen" header="Produsen"/>
            <Column field="jenis_beras" header="Jenis Beras"/>
            <Column field="harga_jual" header="Harga Jual">
                <template #body="{data}">
                    <span>{{ formatRupiah(data.harga_jual) }}</span>
                </template>
            </Column>
            <Column field="stok_awal" header="Stok Awal"/>
            <Column field="stok_tersedia" header="Stok Tersedia"/>
            <Column header="Status">
                <template #body="{data}">
                    <span>{{ data.status_beras }}</span>
                </template>
            </Column>
            <Column field="kualitas_beras" header="Kualitas"/>
            <Column header="Sertifikat">
                <template #body="{data}">
                    <span v-if="data?.sertifikat_beras">Terdaftar</span>
                    <span v-else>Tidak ada sertifikat</span>
                </template>
            </Column>
            <Column header="Tanggal Produksi">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_produksi) }}</span>
                </template>
            </Column>
            <Column header="Tanggal Kadaluarsa">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_kadaluarsa) }}</span>
                </template>
            </Column>
        </DataTable>
    </div>
    <div class="p-2 mt-3 flex items-center gap-4">
        <Button size="small" label="Kembali" @click="router.get(route(props.backRoute))" v-if="showButton" />
        <Button size="small" label="Cetak" @click="cetakLaporan" v-if="showButton" />
    </div>
</template>

<style scoped>
::v-deep(table th) {
  border: 1px solid black;
}
::v-deep(table td) {
  border: 1px solid black;
}
::v-deep(.p-datatable-footer) {
  border-bottom: 1px solid black;
  border-left: 1px solid black;
  border-right: 1px solid black;
}
::v-deep(.p-datatable-header) {
  border-top: 1px solid black;
  border-left: 1px solid black;
  border-right: 1px solid black;
}
</style>
