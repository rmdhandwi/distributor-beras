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

const totalStats = ref(0)
const showButton = ref(true)

function formatRupiah(angka) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(angka);
}

function formatTanggal(tanggal) {
    if(tanggal)
    {
        const [tahun, bulan, hari] = tanggal.split('-')
        return `${hari}/${bulan}/${tahun}`
    }
    else return '-'
}

const setDataStats = () =>
{
    props.dataCetak.forEach(item => {
        totalStats.value += item.total_harga
    })
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
    <Head title="Laporan Transaksi"/>
    <div class="p-2">
        <DataTable class="text-xs" :value="props.dataCetak" dataKey="index" showGridlines striped-rows scrollable size="small">
            <template #header>
                <span class="flex justify-center text-lg font-bold">Laporan Data Transaksi</span>
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
            <Column field="nomor" header="No"/>
            <Column header="Nama Beras">
                <template #body="{data}">
                    {{ data.pemesanan?.beras?.nama_beras }}
                </template>
            </Column>
            <Column header="Produsen">
                <template #body="{data}">
                    {{ data.pemesanan?.produsen?.nama_produsen }}
                </template>
            </Column>
            <Column header="Tanggal Transaksi">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_transaksi)}}</span>
                </template>
            </Column>
            <Column field="jmlh" header="Jumlah Pesan"/>
            <Column header="Harga Satuan">
                <template #body="{data}">
                    {{ formatRupiah(data.harga_satuan) }}
                </template>
            </Column>
            <Column header="Total Harga">
                <template #body="{data}">
                    {{ formatRupiah(data.total_harga) }}
                </template>
            </Column>
            <Column header="Bukti Bayar">
                <template #body="{data}">
                    <span>{{ data.bukti_bayar?'Ada':'Belum Bayar' }}</span>
                </template>
            </Column>
            <Column header="Status Pembayaran">
                <template #body="{data}">
                    <span>{{ data.status_pembayaran}}</span>
                </template>
            </Column>
            <Column header="Status Pengiriman">
                <template #body="{data}">
                    <span>{{ data.status_pengiriman }}</span>
                </template>
            </Column>
            <Column header="Tanggal Pengiriman" style="min-width: 100px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_pengiriman) ??'Belum dijadwalkan'  }}</span>
                </template>
            </Column>
            <Column field="catatan" header="Catatan" style="min-width: 150px;"/>
            <ColumnGroup type="footer">
                <Row>
                    <Column footer="Total :" colspan="6" footerStyle="text-align:right"/>
                    <Column :footer="formatRupiah(totalStats)" colspan="6"/>
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
