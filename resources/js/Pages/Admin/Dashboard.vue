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
    dataBerasChart : Object,
    dataBeras : Object,
    dataGudang : Object,
    dataProdusen : Object,
    dataTransaksi : Object,
})

const toast = useToast()

const pageTitle = "Dashboard"

const pieData = ref()

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

    return {
        labels: props.dataBerasChart?.map(item => item.nama_produsen),
        datasets: [
            {
                data: props.dataBerasChart?.map(item => item.total_stok),

                backgroundColor: props.dataBerasChart?.map(item => {
                    const stok = item.total_stok;

                    if (stok === 0) return documentStyle.getPropertyValue('--p-slate-500');           // Tidak ada stok
                    if (stok <= 50) return documentStyle.getPropertyValue('--p-red-500');            // Stok rendah
                    if (stok <= 100) return documentStyle.getPropertyValue('--p-yellow-400');        // Stok sedang
                    if (stok <= 200) return documentStyle.getPropertyValue('--p-yellow-500');        // Stok sedang
                    if (stok <= 300) return documentStyle.getPropertyValue('--p-amber-400');        // Stok sedang
                    if (stok <= 400) return documentStyle.getPropertyValue('--p-amber-500');        // Stok sedang
                    if (stok <= 500) return documentStyle.getPropertyValue('--p-blue-500');        // Stok sedang
                    return documentStyle.getPropertyValue('--p-green-600');                          // Stok tinggi
                }),

                hoverBackgroundColor: props.dataBerasChart?.map(item => {
                    const stok = item.total_stok;

                    if (stok === 0) return documentStyle.getPropertyValue('--p-slate-400');           // Tidak ada stok
                    if (stok <= 50) return documentStyle.getPropertyValue('--p-red-400');            // Stok rendah
                    if (stok <= 100) return documentStyle.getPropertyValue('--p-yellow-300');        // Stok sedang
                    if (stok <= 200) return documentStyle.getPropertyValue('--p-yellow-400');        // Stok sedang
                    if (stok <= 300) return documentStyle.getPropertyValue('--p-amber-300');        // Stok sedang
                    if (stok <= 400) return documentStyle.getPropertyValue('--p-amber-400');        // Stok sedang
                    if (stok <= 500) return documentStyle.getPropertyValue('--p-blue-400');        // Stok sedang
                    return documentStyle.getPropertyValue('--p-green-500');                         // Stok tinggi
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
                <!-- Cards -->
                <!-- Card Stok Beras -->
                <Card class="p-2 w-[18rem] border border-slate-50 hover:border-amber-500">
                    <template #title>
                        <div class="flex gap-x-2 items-center">
                            <i class="pi pi-box" style="font-size: 1.2rem"></i>
                            <span>Stok Beras</span>
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
                                    {{ props.dataTransaksi?.find(item => item.status_pengiriman==='Dijadwalkan').jumlah ?? 0 }}
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
            <div class="flex mt-8">
                <Card class="p-2 border border-slate-50 hover:border-amber-500">
                    <template #title>
                        <div class="flex gap-x-2 items-center">
                            <span>Stok Beras Tersedia</span>
                        </div>
                    </template>
                    <template #content>
                        <Chart type="pie" :data="pieData"  class="w-full md:w-[24rem]"/>
                    </template>
                </Card>
            </div>
        </template>
    </AuthenticatedLayout>
</template>
