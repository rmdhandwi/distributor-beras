<script setup>
import { onMounted, ref } from 'vue'
import { Head } from '@inertiajs/vue3'

import { useToast } from 'primevue'

// import layout
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

onMounted(() =>
{
    checkNotif()
    pieData.value = setPieData()
    barData.value = setBarData()
})

const props = defineProps({
    flash : Object,
    dataBerasChart : Object,
    statistikHarga : Object,
    dataGudang : Object,
    dataProdusen : Object,
    dataTransaksi : Object,
})

const toast = useToast()

const pageTitle = "Dashboard"

const pieData = ref()
const barData = ref()

const chartColors = [
    '--p-cyan-',
    '--p-red-',
    '--p-green-',
    '--p-amber-',
    '--p-blue-',
    '--p-purple-',
    '--p-pink-',
    '--p-indigo-',
    '--p-slate-'
];

const checkNotif = async () =>
{
    if(props.flash.notif_status)
    {
        await new Promise(resolve =>
        {
            toast.add({
                severity : props.flash.notif_status,
                summary : 'Notifikasi',
                detail : props.flash.notif_message,
                life : 4000,
            })

            setTimeout(() => { resolve()}, 4000)
        })
    }
}

function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
}

const setPieData = () =>
{
    const documentStyle = getComputedStyle(document.body);

    const pieDataFix = props.dataBerasChart?.map((item, index) => (
    {
        ...item,
        color: chartColors[index % chartColors.length]
    }));

    return {
        labels: pieDataFix?.map(item => item.nama_produsen),
        datasets: [
            {
                data: props.dataBerasChart?.map(item => item.total_stok),

                backgroundColor: pieDataFix.map(item => {
                    return documentStyle.getPropertyValue(item.color+'500');
                }),

                hoverBackgroundColor: pieDataFix.map(item => {
                    return documentStyle.getPropertyValue(item.color+'600');
                }),
            }
        ]
    };
}

const setBarData = () =>
{
    const documentStyle = getComputedStyle(document.body);

    return {
        labels: ['Stok Awal','Rusak','Hilang','Stok Sisa'],
        datasets: [
            {
                label : 'Data Gudang',
                labelColor : '--p-slate-50',
                data: Object.values(props.dataGudang),

                backgroundColor: Object.keys(props.dataGudang)?.map((col) => {
                    const column = col;
                    if (column === 'total_stok_awal') return documentStyle.getPropertyValue('--p-blue-400');
                    if (column === 'total_rusak') return documentStyle.getPropertyValue('--p-red-400');
                    if (column === 'total_hilang') return documentStyle.getPropertyValue('--p-slate-400');
                    if (column === 'total_stok_sisa') return documentStyle.getPropertyValue('--p-green-400');
                }),

                hoverBackgroundColor: Object.keys(props.dataGudang)?.map((col) => {
                    const column = col;
                    if (column === 'total_stok_awal') return documentStyle.getPropertyValue('--p-blue-500');
                    if (column === 'total_rusak') return documentStyle.getPropertyValue('--p-red-500');
                    if (column === 'total_hilang') return documentStyle.getPropertyValue('--p-slate-500');
                    if (column === 'total_stok_sisa') return documentStyle.getPropertyValue('--p-green-500');
                }),
            }
        ]
    };
}
</script>

<template>
    <Head :title="pageTitle"/>
    <AuthenticatedLayout :pageTitle="pageTitle">
        <template #pageContent>
            <div class="flex flex-wrap gap-4">
                <!-- Cards -->
                <!-- Card Stok Beras -->
                <Card class="p-2 border border-slate-50 hover:border-amber-500">
                    <template #title>
                        <div class="flex gap-x-2 items-center">
                            <i class="pi pi-box" style="font-size: 1.2rem"></i>
                            <span>Stok Beras</span>
                        </div>
                    </template>
                    <template #content>
                        <div class="flex items-center gap-2">
                            <div class="flex flex-col">
                                <span>Harga Rata-rata : </span>
                                <div class="px-2 text-sm">
                                    <span>- 10kg : </span>
                                    <span class="text-green-500">
                                        {{ formatRupiah(props.statistikHarga[10].rata_rata ?? 0)}}
                                    </span>
                                </div>
                                <div class="px-2 text-sm">
                                    <span>- 20kg : </span>
                                    <span class="text-green-500">
                                        {{ formatRupiah(props.statistikHarga[20].rata_rata ?? 0)}}
                                    </span>
                                </div>
                                <div class="px-2 text-sm">
                                    <span>- 50kg : </span>
                                    <span class="text-green-500">
                                        {{ formatRupiah(props.statistikHarga[50].rata_rata ?? 0)}}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span>Harga Termurah : </span>
                                <div class="px-2 text-sm">
                                    <span>- 10kg : </span>
                                    <span class="text-green-500">
                                        {{ formatRupiah(props.statistikHarga[10].termurah ?? 0)}}
                                    </span>
                                </div>
                                <div class="px-2 text-sm">
                                    <span>- 20kg : </span>
                                    <span class="text-green-500">
                                        {{ formatRupiah(props.statistikHarga[20].termurah ?? 0)}}
                                    </span>
                                </div>
                                <div class="px-2 text-sm">
                                    <span>- 50kg : </span>
                                    <span class="text-green-500">
                                        {{ formatRupiah(props.statistikHarga[50].termurah ?? 0)}}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span>Harga Termahal : </span>
                                <div class="px-2 text-sm">
                                    <span>- 10kg : </span>
                                    <span class="text-green-500">
                                        {{ formatRupiah(props.statistikHarga[10].termahal ?? 0)}}
                                    </span>
                                </div>
                                <div class="px-2 text-sm">
                                    <span>- 20kg : </span>
                                    <span class="text-green-500">
                                        {{ formatRupiah(props.statistikHarga[20].termahal ?? 0)}}
                                    </span>
                                </div>
                                <div class="px-2 text-sm">
                                    <span>- 50kg : </span>
                                    <span class="text-green-500">
                                        {{ formatRupiah(props.statistikHarga[50].termahal ?? 0)}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>
                </Card>
                <!-- Card Stok Gudang -->
                <Card class="p-2 w-[18rem] border border-slate-50 hover:border-amber-500">
                    <template #title>
                        <div class="flex gap-x-2 items-center">
                            <i class="pi pi-warehouse" style="font-size: 1.2rem"></i>
                            <span>Stok Gudang</span>
                        </div>
                    </template>
                    <template #content>
                        <div class="flex flex-col mt-4">
                            <div>
                                <span>Tersedia : </span>
                                <span class="text-green-500">
                                    {{ props.dataGudang?.total_stok_awal ?? 0 }}
                                </span>
                            </div>
                            <div>
                                <span>Rusak : </span>
                                <span class="text-red-500">
                                    {{ props.dataGudang?.total_rusak ?? 0 }}
                                </span>
                            </div>
                            <div>
                                <span>Hilang : </span>
                                <span class="text-red-500">
                                    {{ props.dataGudang?.total_hilang ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </template>
                    <template #footer>
                        <div class="border-t">
                            <span>Tersisa : </span>
                            <span>
                                {{ props.dataGudang?.total_stok_sisa ?? 0 }}
                            </span>
                        </div>
                    </template>
                </Card>
                <!-- Card Produsen -->
                <Card class="p-2 w-[18rem] border border-slate-50 hover:border-amber-500">
                    <template #title>
                        <div class="flex gap-x-2 items-center">
                            <i class="pi pi-users" style="font-size: 1.2rem"></i>
                            <span>Produsen</span>
                        </div>
                    </template>
                    <template #content>
                        <div class="flex flex-col mt-4">
                            <div>
                                <span>Terverifikasi : </span>
                                <span class="text-green-500">
                                    {{ props.dataProdusen?.verified ?? 0 }}
                                </span>
                            </div>
                            <div>
                                <span>Belum verifikasi : </span>
                                <span class="text-red-500">
                                    {{ props.dataProdusen?.unverified?? 0 }}
                                </span>
                            </div>
                        </div>
                    </template>
                    <template #footer>
                        <div class="border-t">
                            <span>Total : </span>
                            <span>
                                {{ props.dataProdusen?.total ?? 0 }}
                            </span>
                        </div>
                    </template>
                </Card>
                <!-- Card Transaksi -->
                <Card class="p-2 w-[18rem] border border-slate-50 hover:border-amber-500">
                    <template #title>
                        <div class="flex gap-x-2 items-center">
                            <i class="pi pi-arrow-right-arrow-left" style="font-size: 1.2rem"></i>
                            <span>Transaksi</span>
                        </div>
                    </template>
                    <template #content>
                        <div class="flex flex-col mt-4">
                            <div>
                                <span>Dijadwalkan : </span>
                                <span class="text-green-500">
                                    {{ props.dataTransaksi?.find(item => item.status_pengiriman==='Dijadwalkan')?.jumlah ?? 0 }}
                                </span>
                            </div>
                            <div>
                                <span>Menunggu : </span>
                                <span class="text-amber-500">
                                    {{ props.dataTransaksi?.find(item => item.status_pengiriman==='Menunggu')?.jumlah ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </template>
                    <template #footer>
                        <div class="border-t">
                            <span>Total : </span>
                            <span>
                                {{ props.dataTransaksi?.length ?? 0 }}
                            </span>
                        </div>
                    </template>
                </Card>
            </div>
            <!-- Diagram and Table -->
            <div class="flex gap-6 mt-8">
                <Card class="p-2 border border-slate-50 hover:border-amber-500">
                    <template #title>
                        <div class="flex gap-x-2 items-center">
                            <span>Stok Beras Produsen</span>
                        </div>
                    </template>
                    <template #content>
                        <Chart type="pie" :data="pieData"  class="w-full md:w-[24rem]"/>
                    </template>
                </Card>

                <Card class="p-2 border border-slate-50 hover:border-amber-500">
                    <template #title>
                        <div class="flex gap-x-2 items-center">
                            <span>Stok Beras Gudang</span>
                        </div>
                    </template>
                    <template #content>
                        <Chart type="bar" :data="barData"  class="w-full md:w-[36rem]"/>
                    </template>
                </Card>
            </div>
        </template>
    </AuthenticatedLayout>
</template>
