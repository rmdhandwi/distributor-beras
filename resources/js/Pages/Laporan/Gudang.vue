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
    stok_awal : null,
    rusak : null,
    hilang : null,
    stok_sisa : null,
})

const setDataStats = () =>
{
    props.dataCetak.forEach(item => {
        dataStats.value.stok_awal += item.stok_awal
        dataStats.value.rusak += item.rusak
        dataStats.value.hilang += item.hilang
        dataStats.value.stok_sisa += item.stok_sisa
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
         <DataTable :value="props.dataCetak" dataKey="index" showGridlines>
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
            <Column field="nomor" header="No"/>
            <Column header="Nama Beras">
                <template #body="{data}">
                    {{ data.beras?.nama_beras }}
                </template>
            </Column>
            <Column header="Produsen">
                <template #body="{data}">
                    {{ data.produsen?.nama_produsen }}
                </template>
            </Column>
            <Column field="stok_awal" header="Stok Awal"/>
            <Column field="rusak" header="Rusak"/>
            <Column field="hilang" header="Hilang"/>
            <Column field="stok_sisa" header="Stok Sisa" style="min-width: 100px;"/>
            <ColumnGroup type="footer">
                <Row>
                    <Column footer="Total :" colspan="3" footerStyle="text-align:right"/>
                    <Column :footer="dataStats.stok_awal"/>
                    <Column :footer="dataStats.rusak"/>
                    <Column :footer="dataStats.hilang"/>
                    <Column :footer="dataStats.stok_sisa" colspan="2"/>
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
