<script setup>
import { ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
})


const props = defineProps({
    dataBeras : Object,
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
  const [tahun, bulan, hari] = tanggal.split('-')
  return `${hari}/${bulan}/${tahun}`
}

const editProdusen = id_beras =>
{
    const dataBeras = props.dataBeras.filter((beras) => beras.id_beras === id_beras).map(item => {
        const copy = {...item}
        delete copy.nomor
        return copy
    })

    emit('editData', dataBeras)
}
</script>

<template>
    <div>
    </div>
</template>

<style scoped>
</style>
