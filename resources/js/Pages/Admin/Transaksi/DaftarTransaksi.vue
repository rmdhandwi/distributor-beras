<script setup>
import { ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
})

const props = defineProps({
    dataTransaksi : Object,
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
    if(tanggal)
    {
        const [tahun, bulan, hari] = tanggal.split('-')
        return `${hari}/${bulan}/${tahun}`
    }
}

const uploadBukti = id_pemesanan =>
{
    const dataFilter = props.dataTransaksi.filter((transaksi) => transaksi.id_pemesanan === id_pemesanan)
    emit('editData', dataFilter)
}

const switchStatus = status =>
{
    switch(status)
    {
        case 'Pending' : return 'warn';

        case 'Pengiriman Dijadwalkan' : return 'info';

        case 'Dalam Pengiriman' : return 'info';

        case 'Transaksi Selesai' : return 'success';
    }
}

const switchStatusBayar = status =>
{
    switch(status)
    {
        case 'Pending' : return 'warn';

        case 'Berhasil Upload' : return 'Success';
    }
}

</script>

<template>
    <div class="flex flex-col">
        <DataTable :value="props.dataTransaksi" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters">
            <template #header>
                <div class="flex justify-between items-center gap-x-2">
                    <IconField class="w-full">
                        <InputIcon>
                            <i class="pi pi-search me-4" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Cari Data Transaksi" size="small" fluid/>
                    </IconField>
                    <Button icon="pi pi-print" severity="contrast" variant="outlined" label="CSV" size="small" />
                </div>
            </template>
            <template #footer>
                <span>Jumlah Data Transaksi ({{ props.dataTransaksi?.length }})</span>
            </template>
            <template #loading>
                <span class="flex justify-center">Sedang Memuat Data...</span>
            </template>
            <template #empty>
                <span class="flex justify-center">Tidak Ada Transaksi</span>
            </template>
            <Column sortable field="nomor" header="No" frozen/>
            <Column sortable header="Nama Beras" style="min-width: 150px;" frozen>
                <template #body="{data}">
                    {{ data.pemesanan?.beras?.nama_beras }}
                </template>
            </Column>
            <Column sortable header="Produsen" style="min-width: 150px;" frozen>
                <template #body="{data}">
                    {{ data.pemesanan?.produsen?.nama_produsen }}
                </template>
            </Column>
            <Column sortable header="Tanggal Transaksi" style="min-width: 100px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_transaksi) ?? 'Belum dijadwalkan' }}</span>
                </template>
            </Column>
            <Column field="jmlh" header="Jumlah Pesan" style="min-width: 100px;"/>
            <Column header="Harga Satuan" style="min-width: 100px;">
                <template #body="{data}">
                    {{ formatRupiah(data.harga_satuan) }}
                </template>
            </Column>
            <Column header="Total Harga" style="min-width: 100px;">
                <template #body="{data}">
                    {{ formatRupiah(data.total_harga) }}
                </template>
            </Column>
            <Column header="Bukti Bayar" style="min-width: 150px;">
                <template #body="{data}">
                    <div class="size-10 overflow-hidden border rounded" v-if="data?.bukti_bayar">
                        <Image :src="data?.bukti_bayar" class="size-full" preview />
                    </div>
                    <Tag severity="warn" value="Silahkan Upload Bukti Pembayaran" v-else/>
                </template>
            </Column>
            <Column header="Status Pembayaran" style="min-width: 150px;">
                <template #body="{data}">
                    <Tag :value="data.status_pembayaran" :severity="switchStatusBayar(data.status_pembayaran)"/>
                </template>
            </Column>
            <Column header="Status Pengiriman" style="min-width: 150px;">
                <template #body="{data}">
                    <Tag :value="data.status_pengiriman" :severity="switchStatus(data.status_pengiriman)"/>
                </template>
            </Column>
            <Column sortable header="Tanggal Pengiriman" style="min-width: 100px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_pengiriman) ??'Belum dijadwalkan'  }}</span>
                </template>
            </Column>
            <Column field="catatan" header="Catatan" style="min-width: 150px;"/>
            <Column header="Action" frozen align-frozen="right">
                <template #body="{data}">
                    <div class="flex place-content-center gap-2">
                        <Button @click="uploadBukti(data.id_transaksi)" severity="info" size="small" icon="pi pi-camera" :disabled="data.bukti_bayar"/>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
