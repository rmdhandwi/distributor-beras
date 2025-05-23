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
})

const props = defineProps({
    flash : Object,
    statistikHarga : Object,
    dataBerasChart : Object,
})

const toast = useToast()

const pageTitle = "Dashboard"

const pieData = ref()

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

function totalStokBeras()
{
    let total = 0;

    props.dataBerasChart.forEach(item =>
    {
        total += item.stok_tersedia
    })

    return formatDecimal(total)
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

const setPieData = () =>
{
    const documentStyle = getComputedStyle(document.body);

    const pieDataFix = props.dataBerasChart?.map((item, index) => (
    {
        ...item,
        color: chartColors[index % chartColors.length]
    }));

    return {
        labels: pieDataFix.map(item => item.nama_beras),
        datasets: [
            {
                data: pieDataFix.map(item => item.stok_tersedia),

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
</script>

<template>
    <Head :title="pageTitle"/>
    <AuthenticatedLayout :pageTitle="pageTitle">
        <template #pageContent>
            <div class="flex gap-x-4">

                <Card class="p-2 border border-slate-50 hover:border-amber-500">
                    <template #title>
                        <div class="flex gap-x-2 items-center">
                            <span>Stok Beras : {{ totalStokBeras() + ' kg' }}</span>
                        </div>
                    </template>
                    <template #content>
                        <Chart type="pie" :data="pieData"  class="w-full md:w-[24rem]"/>
                    </template>
                </Card>

                <!-- Card Stok Beras -->
                <Card class="p-2 h-fit border border-slate-50 hover:border-amber-500">
                    <template #title>
                        <div class="flex gap-x-2 items-center">
                            <span>Statistik Harga Beras</span>
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
            </div>
            <!-- Diagram and Table -->
            <div class="flex gap-6 mt-8">
            </div>

        </template>
    </AuthenticatedLayout>
</template>
