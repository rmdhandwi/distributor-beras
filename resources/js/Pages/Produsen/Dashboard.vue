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
    dataBeras : Object,
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
                            <span>Stok Beras</span>
                        </div>
                    </template>
                    <template #content>
                        <Chart type="pie" :data="pieData"  class="w-full md:w-[24rem]"/>
                    </template>
                </Card>

                <!-- Card Stok Beras -->
                <Card class="p-2 w-[18rem] h-fit border border-slate-50 hover:border-amber-500">
                    <template #title>
                        <div class="flex gap-x-2 items-center">
                            <span>Harga Beras</span>
                        </div>
                    </template>
                    <template #content>
                        <div class="flex flex-col mt-4">
                            <div>
                                <span>Harga Rata-rata : </span>
                                <span class="text-green-500">
                                    {{ formatRupiah(props.dataBeras[0]?.avg_harga_jual) ?? 0 }}
                                </span>
                            </div>
                            <div>
                                <span>Harga Termurah : </span>
                                <span class="text-green-500">
                                    {{ formatRupiah(props.dataBeras[0]?.min_harga_jual) ?? 0 }}
                                </span>
                            </div>
                            <div>
                                <span>Harga Termahal : </span>
                                <span class="text-green-500">
                                    {{ formatRupiah(props.dataBeras[0]?.max_harga_jual) ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </template>
                    <template #footer>
                        <div class="border-t">
                            <span>Beras Tersedia : </span>
                            <span>
                                {{ props.dataBeras[0]?.total_stok_tersedia ?? 0}}
                            </span>
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
