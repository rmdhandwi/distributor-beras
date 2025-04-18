<script setup>

const props = defineProps({
    dataProdusen : Object,
})

</script>

<template>
    <div class="flex flex-col">
        <DataTable :value="props.dataProdusen" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable>
            <Column field="nomor" header="No" frozen/>
            <Column field="nama_produsen" header="Nama Produsen" frozen/>
            <Column field="alamat" header="Alamat" style="min-width: 250px;"/>
            <Column field="no_telp" header="No Telp"/>
            <Column field="email" header="Email"/>
            <Column field="jenis_beras" header="Jenis Beras"/>
            <Column field="harga_beras" header="Harga Beras"/>
            <Column field="jml_stok" header="Jumlah Stok"/>
            <Column field="status_stok" header="Status Stok"/>
            <Column field="tgl_pendaftaran" header="Tanggal Pendaftaran"/>
        </DataTable>

    </div>
</template>

<style scoped>
</style>
