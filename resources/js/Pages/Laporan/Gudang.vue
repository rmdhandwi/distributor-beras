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
    stok10kg : {
        stok_awal : 0,
        rusak : 0,
        hilang : 0,
        stok_sisa : 0,
    },
    stok20kg : {
        stok_awal : 0,
        rusak : 0,
        hilang : 0,
        stok_sisa : 0,
    },
    stok50kg : {
        stok_awal : 0,
        rusak : 0,
        hilang : 0,
        stok_sisa : 0,
    },
})

const setDataStats = () =>
{
    props.dataCetak.forEach(item => {
        dataStats.value.stok10kg.stok_awal += item.stok10kg?.stok_awal
        dataStats.value.stok10kg.rusak += item.stok10kg?.rusak
        dataStats.value.stok10kg.hilang += item.stok10kg?.hilang
        dataStats.value.stok10kg.stok_sisa += item.stok10kg?.stok_sisa
        dataStats.value.stok20kg.stok_awal += item.stok20kg?.stok_awal
        dataStats.value.stok20kg.rusak += item.stok20kg?.rusak
        dataStats.value.stok20kg.hilang += item.stok20kg?.hilang
        dataStats.value.stok20kg.stok_sisa += item.stok20kg?.stok_sisa
        dataStats.value.stok50kg.stok_awal += item.stok50kg?.stok_awal
        dataStats.value.stok50kg.rusak += item.stok50kg?.rusak
        dataStats.value.stok50kg.hilang += item.stok50kg?.hilang
        dataStats.value.stok50kg.stok_sisa += item.stok50kg?.stok_sisa
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
    <Head title="Laporan Gudang"/>
    <div class="p-2">
         <DataTable class="w-fit px-2 text-xs" :value="props.dataCetak" dataKey="index" showGridlines size="small">
             <template #header>
                <span class="flex justify-center text-lg font-bold">Laporan Data Gudang</span>
            </template>
            <template #footer>
                <span>Tanggal Cetak Laporan : {{ props.tanggalCetak }}</span>
            </template>
            <template #loading>
                <span class="flex justify-center">Sedang Memuat Data...</span>
            </template>
            <template #empty>
                <span class="flex justify-center">Tidak Ada Gudang</span>
            </template>
            <ColumnGroup type="header">
                <Row>
                    <Column field="nomor" header="No" rowspan="2"/>
                    <Column header="Nama Beras" rowspan="2"/>
                    <Column header="Jenis" rowspan="2"/>
                    <Column header="Produsen" rowspan="2"/>
                    <Column header="Stok 10kg" colspan="4"/>
                    <Column header="Stok 20kg" colspan="4"/>
                    <Column header="Stok 50kg" colspan="4"/>
                </Row>
                <Row>
                    <!-- 10kg -->
                    <Column field="stok10kg.stok_awal"  header="Awal"/>
                    <Column field="stok10kg.rusak"  header="Rusak"/>
                    <Column field="stok10kg.hilang"  header="Hilang"/>
                    <Column field="stok10kg.stok_sisa"  header="Sisa"/>
                    <!-- 20kg -->
                    <Column field="stok20kg.stok_awal"  header="Awal"/>
                    <Column field="stok20kg.rusak"  header="Rusak"/>
                    <Column field="stok20kg.hilang"  header="Hilang"/>
                    <Column field="stok20kg.stok_sisa"  header="Sisa"/>
                    <!-- 50kg -->
                    <Column field="stok50kg.stok_awal"  header="Awal"/>
                    <Column field="stok50kg.rusak"  header="Rusak"/>
                    <Column field="stok50kg.hilang"  header="Hilang"/>
                    <Column field="stok50kg.stok_sisa"  header="Sisa"/>
                </Row>
            </ColumnGroup>
            <Column field="nomor"/>
            <Column field="beras.nama_beras" style="min-width: 180px;">
                <template #body="{data}">
                    {{ data.beras?.nama_beras }}
                </template>
            </Column>
            <Column style="min-width: 180px;">
                <template #body="{data}">
                    {{ data.beras?.jenis_beras }}
                </template>
            </Column>
            <Column style="min-width: 180px;">
                <template #body="{data}">
                    {{ data.produsen?.nama_produsen }}
                </template>
            </Column>
            <!-- 10kg -->
            <Column>
                <template #body="{data}">
                    {{ data.stok10kg?.stok_awal ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok10kg?.rusak ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok10kg?.hilang ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok10kg?.stok_sisa ?? '-' }}
                </template>
            </Column>
            <!-- 20kg -->
            <Column>
                <template #body="{data}">
                    {{ data.stok20kg?.stok_awal ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok20kg?.rusak ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok20kg?.hilang ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok20kg?.stok_sisa ?? '-' }}
                </template>
            </Column>
            <!-- 50kg -->
            <Column>
                <template #body="{data}">
                    {{ data.stok50kg?.stok_awal ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok50kg?.rusak ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok50kg?.hilang ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok50kg?.stok_sisa ?? '-' }}
                </template>
            </Column>
            <ColumnGroup type="footer">
                <Row>
                    <Column footer="Total :" colspan="4" footerStyle="text-align:right"/>
                    <!-- 10kg -->
                    <Column :footer="dataStats.stok10kg?.stok_awal"/>
                    <Column :footer="dataStats.stok10kg?.rusak"/>
                    <Column :footer="dataStats.stok10kg?.hilang"/>
                    <Column :footer="dataStats.stok10kg?.stok_sisa"/>
                    <!-- 20kg -->
                    <Column :footer="dataStats.stok20kg?.stok_awal"/>
                    <Column :footer="dataStats.stok20kg?.rusak"/>
                    <Column :footer="dataStats.stok20kg?.hilang"/>
                    <Column :footer="dataStats.stok20kg?.stok_sisa"/>
                    <!-- 50kg -->
                    <Column :footer="dataStats.stok50kg?.stok_awal"/>
                    <Column :footer="dataStats.stok50kg?.rusak"/>
                    <Column :footer="dataStats.stok50kg?.hilang"/>
                    <Column :footer="dataStats.stok50kg?.stok_sisa"/>
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
