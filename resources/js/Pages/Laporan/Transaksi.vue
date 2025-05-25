<script setup>
import { onMounted, ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'

onMounted(() =>
{
    dataTransaksiFix.value = props.dataCetak
    setDataStats()
})

const props = defineProps({
    backRoute : String,
    dataCetak : Object,
    tanggalCetak : String,
})

const dataTransaksiFix = ref([])

const showButton = ref(true)

const dataStats = ref({
    jumlah_pesan : 0,
    total_bayar : 0,
    stok10kg : {
        jumlah : 0,
        total_harga : 0,
    },
    stok20kg : {
        jumlah : 0,
        total_harga : 0,
    },
    stok50kg : {
        jumlah : 0,
        total_harga : 0,
    },
})

const setDataStats = () =>
{
    dataTransaksiFix.value.forEach(item => {
        dataStats.value.jumlah_pesan += item.jmlh
        dataStats.value.total_bayar += item.total_bayar
        dataStats.value.stok10kg.jumlah += item.stok10kg?.jumlah ?? 0
        dataStats.value.stok10kg.total_harga += item.stok10kg?.total_harga ?? 0
        dataStats.value.stok20kg.jumlah += item.stok20kg?.jumlah ?? 0
        dataStats.value.stok20kg.total_harga += item.stok20kg?.total_harga ?? 0
        dataStats.value.stok50kg.jumlah += item.stok50kg?.jumlah ?? 0
        dataStats.value.stok50kg.total_harga += item.stok50kg?.total_harga ?? 0
    })
}

function formatDecimal(angka)
{
    if(angka)
    {
        return angka.toLocaleString('id-ID');
    }

    return 0
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

    return 0
}

function formatTanggal(tanggal) {
    if(tanggal)
    {
        const [tahun, bulan, hari] = tanggal.split('-')
        return `${hari}/${bulan}/${tahun}`
    }
    return '-'
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
        <DataTable class="w-fit px-2 text-xs" :value="dataTransaksiFix" dataKey="index" showGridlines striped-rows scrollable size="small">
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
            <ColumnGroup type="header">
                <Row>
                    <Column  field="nomor" header="No"  rowspan="2"/>
                    <Column  field="pemesanan.beras.nama_beras" header="Nama Beras"  rowspan="2"/>
                    <Column  field="tgl_transaksi" header="Tanggal Transaksi" rowspan="2"/>
                    <Column field="jmlh" header="Jumlah Pesan" rowspan="2"/>
                    <Column header="10kg" colspan="2"/>
                    <Column header="20kg" colspan="2"/>
                    <Column header="50kg" colspan="2"/>
                    <Column header="Total Bayar" rowspan="2"/>
                    <Column header="Bukti Bayar" rowspan="2"/>
                    <Column header="Status Pembayaran" rowspan="2"/>
                    <Column header="Status Pengiriman" rowspan="2"/>
                    <Column field="tgl_pengiriman"  header="Tanggal Pengiriman" rowspan="2"/>
                    <Column field="catatan" header="Catatan" rowspan="2"/>
                </Row>
                <Row>
                    <!-- 10kg -->
                    <Column  field="stok10kg.jumlah" header="Jumlah"/>
                    <Column  field="stok10kg.total_bayar" header="Total Harga"/>
                    <!-- 20kg -->
                    <Column  field="stok20kg.jumlah" header="Jumlah"/>
                    <Column  field="stok20kg.total_bayar" header="Total Harga"/>
                    <!-- 50kg -->
                    <Column  field="stok50kg.jumlah" header="Jumlah"/>
                    <Column  field="stok50kg.total_bayar" header="Total Harga"/>
                </Row>
            </ColumnGroup>
            <Column field="nomor" />
            <Column >
                <template #body="{data}">
                    {{ data.pemesanan?.beras?.nama_beras }}
                </template>
            </Column>
            <Column >
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_transaksi) ?? 'Belum dijadwalkan' }}</span>
                </template>
            </Column>
            <Column field="jmlh"/>
            <!-- 10kg -->
            <Column>
                <template #body="{data}">
                    {{ formatDecimal(data.stok10kg?.jumlah) }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok10kg?.total_harga) }}
                </template>
            </Column>
            <!-- 20kg -->
            <Column>
                <template #body="{data}">
                    {{ formatDecimal(data.stok20kg?.jumlah) }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok20kg?.total_harga) }}
                </template>
            </Column>
            <!-- 50kg -->
            <Column>
                <template #body="{data}">
                    {{ formatDecimal(data.stok50kg?.jumlah) }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok50kg?.total_harga) }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.total_bayar) }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.bukti_bayar ? 'Ada' : 'Tidak ada' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.status_pembayaran }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.status_pengiriman }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_pengiriman) ??'Belum dijadwalkan'  }}</span>
                </template>
            </Column>
            <Column field="catatan"/>
            <ColumnGroup type="footer">
                <Row>
                    <Column />
                    <Column />
                    <Column footer="Total :" footerStyle="text-align:right"/>
                    <Column :footer="dataStats.jumlah_pesan"/>
                    <!-- 10kg -->
                    <Column :footer="dataStats.stok10kg?.jumlah"/>
                    <Column :footer="formatRupiah(dataStats.stok10kg?.total_harga)"/>
                    <!-- 20kg -->
                    <Column :footer="dataStats.stok20kg?.jumlah"/>
                    <Column :footer="formatRupiah(dataStats.stok20kg?.total_harga)"/>
                    <!-- 50kg -->
                    <Column :footer="dataStats.stok50kg?.jumlah"/>
                    <Column :footer="formatRupiah(dataStats.stok50kg?.total_harga)"/>
                    <Column colspan="2" :footer="formatRupiah(dataStats.total_bayar)"/>
                    <Column colspan="4"/>
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
