<script setup>
import { onMounted, ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'

onMounted(() =>
{
    dataPemesananFix.value = props.dataPemesanan
    setDataStats()
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
})

const props = defineProps({
    dataPemesanan : Object,
})

const dataPemesananFix = ref([])

const emit = defineEmits(['editData'])

const dataStats = ref({
    jumlah : 0,
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
    dataPemesananFix.value.forEach(item => {
        dataStats.value.jumlah += item.jmlh
        dataStats.value.stok10kg.jumlah += item.stok10kg?.jumlah ?? 0
        dataStats.value.stok10kg.total_harga += item.stok10kg?.total_harga ?? 0
        dataStats.value.stok20kg.jumlah += item.stok20kg?.jumlah ?? 0
        dataStats.value.stok20kg.total_harga += item.stok20kg?.total_harga ?? 0
        dataStats.value.stok50kg.jumlah += item.stok50kg?.jumlah ?? 0
        dataStats.value.stok50kg.total_harga += item.stok50kg?.total_harga ?? 0
    })
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
  const [tahun, bulan, hari] = tanggal.split('-')
  return `${hari}/${bulan}/${tahun}`
}

const editPemesanan = id_pemesanan =>
{
    const dataFilter = props.dataPemesanan.filter((gudang) => gudang.id_pemesanan === id_pemesanan)
    emit('editData', dataFilter)
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
        <DataTable :value="dataPemesananFix" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters">
            <template #header>
                <div class="flex justify-between items-center gap-x-2">
                    <IconField class="w-full">
                        <InputIcon>
                            <i class="pi pi-search me-4" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Cari Data Pemesanan" size="small" fluid/>
                    </IconField>
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
            <ColumnGroup type="header">
                <Row>
                    <Column sortable field="nomor" header="No" frozen rowspan="2"/>
                    <Column sortable field="beras.nama_beras" header="Nama Beras" style="min-width: 160px;" frozen rowspan="2"/>
                    <Column sortable field="produsen.nama_produsen" header="Produsen" style="min-width: 160px;" rowspan="2"/>
                    <Column sortable header="Tanggal Pemesanan" style="min-width: 200px;" rowspan="2"/>
                    <Column sortable field="jmlh" header="Jumlah Pesan" style="min-width: 100px;" rowspan="2"/>
                    <Column header="10kg" colspan="3"/>
                    <Column header="20kg" colspan="3"/>
                    <Column header="50kg" colspan="3"/>
                    <Column header="Status" style="min-width: 150px;" rowspan="2"/>
                    <Column header="Catatan" style="min-width: 150px;" rowspan="2"/>
                    <Column header="Action" frozen align-frozen="right" rowspan="2"/>
                </Row>
                <Row>
                    <!-- 10kg -->
                    <Column sortable field="stok10kg.jumlah" header="Jumlah"/>
                    <Column sortable field="stok10kg.harga_satuan" header="Harga Satuan"/>
                    <Column sortable field="stok10kg.total_harga" header="Total Harga"/>
                    <!-- 20kg -->
                    <Column sortable field="stok20kg.jumlah" header="Jumlah"/>
                    <Column sortable field="stok20kg.harga_satuan" header="Harga Satuan"/>
                    <Column sortable field="stok20kg.total_harga" header="Total Harga"/>
                    <!-- 50kg -->
                    <Column sortable field="stok50kg.jumlah" header="Jumlah"/>
                    <Column sortable field="stok50kg.harga_satuan" header="Harga Satuan"/>
                    <Column sortable field="stok50kg.total_harga" header="Total Harga"/>
                </Row>
            </ColumnGroup>
            <Column field="nomor" frozen/>
            <Column field="beras.nama_beras" frozen>
                <template #body="{data}">
                    {{ data.beras?.nama_beras }}
                </template>
            </Column>
            <Column field="produsen.nama_produsen">
                <template #body="{data}">
                    {{ data.produsen?.nama_produsen }}
                </template>
            </Column>
            <Column field="tgl_pemesanan">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_pemesanan) }}</span>
                </template>
            </Column>
            <Column field="jmlh"/>
            <!-- 10kg -->
            <Column>
                <template #body="{data}">
                    {{ data.stok10kg?.jumlah ?? 0 }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok10kg?.harga_satuan) }}
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
                    {{ data.stok20kg?.jumlah ?? 0 }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok20kg?.harga_satuan) }}
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
                    {{ data.stok50kg?.jumlah ?? 0 }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok50kg?.harga_satuan) }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok50kg?.total_harga) }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    <Tag :value="data.status_pesanan" :severity="switchStatus(data.status_pesanan)"/>
                </template>
            </Column>
            <Column field="catatan"/>
            <Column frozen align-frozen="right">
                <template #body="{data}">
                    <div class="flex place-content-center gap-2">
                        <Button @click="editPemesanan(data.id_pemesanan)" severity="info" size="small" icon="pi pi-pen-to-square" :disabled="!data.editable"/>
                    </div>
                </template>
            </Column>
            <ColumnGroup type="footer">
                <Row>
                    <Column frozen/>
                    <Column frozen/>
                    <Column/>
                    <Column footer="Total :" footerStyle="text-align:right"/>
                    <Column :footer="dataStats.jumlah"/>
                    <!-- 10kg -->
                    <Column :footer="dataStats.stok10kg?.jumlah" colspan="2"/>
                    <Column :footer="formatRupiah(dataStats.stok10kg?.total_harga)"/>
                    <!-- 20kg -->
                    <Column :footer="dataStats.stok20kg?.jumlah" colspan="2"/>
                    <Column :footer="formatRupiah(dataStats.stok20kg?.total_harga)"/>
                    <!-- 50kg -->
                    <Column :footer="dataStats.stok50kg?.jumlah" colspan="2"/>
                    <Column :footer="formatRupiah(dataStats.stok50kg?.total_harga)"/>
                    <Column colspan="2"/>
                    <Column frozen align-frozen="right"/>
                </Row>
            </ColumnGroup>
        </DataTable>
    </div>
</template>
