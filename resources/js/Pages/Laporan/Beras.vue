<script setup>
import { onMounted, ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'

onMounted(() =>
{
    setDataStats()
})

const props = defineProps({
    backRoute : String,
    dataCetak : Object,
    tanggalCetak : String,
})

const showButton = ref(true)

const dataStats = ref({
    total_tersedia : null,
    jumlah10kg : null,
    jumlah20kg : null,
    jumlah50kg : null,
})

const setDataStats = () =>
{
    props.dataCetak.forEach(item => {
        dataStats.value.total_tersedia += item.stok_tersedia
        dataStats.value.jumlah10kg += item.stok10kg?.jumlah ?? 0
        dataStats.value.jumlah20kg += item.stok20kg?.jumlah ?? 0
        dataStats.value.jumlah50kg += item.stok50kg?.jumlah ?? 0
    })
}

function formatDecimal(angka)
{
    if(angka)
    {
        return angka.toLocaleString('id-ID');
    }
}

function formatRupiah(angka) {
    if(angka)
    {
        return new Intl.NumberFormat('id-ID', {
          style: 'currency',
          currency: 'IDR',
          minimumFractionDigits: 0
        }).format(angka);
    }
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
                <span class="text-sm font-bold">Tanggal Cetak Laporan : {{ props.tanggalCetak }}</span>
            </template>
            <ColumnGroup type="header">
                <Row>
                    <Column header="No" rowspan="2"/>
                    <Column header="Nama Beras"" rowspan="2"/>
                    <Column header="Produsen"" rowspan="2"/>
                    <Column header="Jenis Beras" rowspan="2"/>
                    <Column header="Stok Tersedia" rowspan="2"/>
                    <Column header="Status" rowspan="2"/>
                    <Column header="Kualitas" rowspan="2"/>
                    <Column header="10kg" colspan="2"/>
                    <Column header="20kg" colspan="2"/>
                    <Column header="50kg" colspan="2"/>
                    <Column header="Sertifikat" rowspan="2"/>
                    <Column header="Tanggal Produksi" rowspan="2"/>
                </Row>
                <Row>
                    <Column header="Jumlah"/>
                    <Column header="Harga"/>
                    <Column header="Jumlah"/>
                    <Column header="Harga"/>
                    <Column header="Jumlah"/>
                    <Column header="Harga"/>
                </Row>
            </ColumnGroup>
            <Column field="nomor"/>
            <Column field="nama_beras""/>
            <Column field="produsen.nama_produsen" "/>
            <Column field="jenis_beras"/>
            <Column field="stok_tersedia">
                <template #body="{data}">
                    {{ data.stok_tersedia+'kg'}}
                </template>
            </Column>
            <Column >
                <template #body="{data}">
                    <span>{{ data.status_beras }}</span>
                </template>
            </Column>
            <Column field="kualitas_beras"/>
            <Column>
                <template #body="{data}">
                    {{ data.stok10kg?.jumlah ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok10kg?.harga) ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok20kg?.jumlah ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok20kg?.harga) ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok50kg?.jumlah ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok50kg?.harga) ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    <span v-if="data?.sertifikat_beras">Terdaftar</span>
                    <span v-else>Tidak ada sertifikat</span>
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_produksi) }}</span>
                </template>
            </Column>
            <ColumnGroup type="footer" >
                <Row>
                    <Column footer="Total :" colspan="4" footerStyle="text-align:right"/>
                    <Column :footer="formatDecimal(dataStats.total_tersedia)+' kg' ?? 0+' kg'" colspan="3"/>
                    <Column :footer="formatDecimal(dataStats.jumlah10kg)" colspan="2"/>
                    <Column :footer="formatDecimal(dataStats.jumlah20kg)" colspan="2"/>
                    <Column :footer="formatDecimal(dataStats.jumlah50kg)" colspan="4"/>
                </Row>
            </ColumnGroup>
        </DataTable>
    </div>
    <div class="p-2 mt-3 flex items-center gap-4">
        <Button size="small" label="Kembali" @click="router.get(route(props.backRoute))" v-if="showButton" />
        <Button size="small" label="Cetak" @click="cetakLaporan" v-if="showButton" />
    </div>
</template>

<style scoped>
::v-deep(.p-datatable-tbody td) {
  color: black;
}
::v-deep(.p-datatable-column-title) {
  color: black;
}
::v-deep(.p-datatable-column-footer) {
  color: black;
}

::v-deep(table th) {
  border: 1px solid black;
}
::v-deep(table td) {
  border: 1px solid black;
}
::v-deep(.p-datatable-footer) {
  color: black;
  border-bottom: 1px solid black;
  border-left: 1px solid black;
  border-right: 1px solid black;
}
::v-deep(.p-datatable-header) {
  color: black;
  border-top: 1px solid black;
  border-left: 1px solid black;
  border-right: 1px solid black;
}
</style>
