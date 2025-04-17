<script setup>
import { useForm } from '@inertiajs/vue3'
import { useConfirm } from 'primevue'


const confirm = useConfirm()

const tambahForm = useForm({
  nama_produsen: null,
  alamat: null,
  no_telp: null,
  email: null,
  jenis_beras: null,
  harga_beras: null,
  jml_stok: null,
})

const jenisBeras = [
    { value : 'A', label : 'A' },
    { value : 'B', label : 'B' },
    { value : 'C', label : 'C' },
    { value : 'D', label : 'D' },
]

const emit = defineEmits(['refreshPage'])

const submitProdusen = () =>
{
    confirm.require({
        message: `Tambah Produsen ${tambahForm.nama_produsen??''}?`,
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: 'Tambah',
        },
        accept: () => {
            tambahForm.post(route('admin.produsen.store'), {
                onSuccess : () => {
                    tambahForm.reset()
                    tambahForm.clearErrors()
                    emit('refreshPage')
                }
            })
        },
    })
}

</script>

<template>
    <!-- <h1>Tambah Produsen</h1> -->
    <form @submit.prevent="submitProdusen" class="flex flex-col gap-4 mt-4" autocomplete="off">
        <!-- Nama Produsen -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="tambahForm.nama_produsen" fluid/>
                <label for="on_label">Nama Produsen</label>
            </FloatLabel>
            <span class="text-red-500" v-if="tambahForm.errors.nama_produsen"> {{ tambahForm.errors.nama_produsen }} </span>
        </div>

        <!-- Alamat -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="tambahForm.alamat" fluid/>
                <label for="on_label">Alamat</label>
            </FloatLabel>
            <span class="text-red-500" v-if="tambahForm.errors.alamat"> {{ tambahForm.errors.alamat }} </span>
        </div>

        <!-- Telepon -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="tambahForm.no_telp" fluid/>
                <label for="on_label">No Telepon</label>
            </FloatLabel>
            <span class="text-red-500" v-if="tambahForm.errors.no_telp"> {{ tambahForm.errors.no_telp }} </span>
        </div>

        <!-- Email -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="tambahForm.email" fluid/>
                <label for="on_label">Email</label>
            </FloatLabel>
            <span class="text-red-500" v-if="tambahForm.errors.email"> {{ tambahForm.errors.email }} </span>
        </div>

        <!-- Jenis Beras -->
        <div>
            <div class="flex flex-wrap gap-4">
                <span>Jenis Beras :</span>
                <div class="flex items-center gap-2" v-for="item in jenisBeras" key="index">
                    <RadioButton v-model="tambahForm.jenis_beras" :inputId="item.label" :value="item.value" />
                    <label :for="item.label">{{ item.label }}</label>
                </div>
            </div>
            <span class="text-red-500" v-if="tambahForm.errors.jenis_beras"> {{ tambahForm.errors.jenis_beras }} </span>
        </div>
        <!-- Harga Beras -->
        <div>
            <FloatLabel variant="on">
                <InputNumber id="on_label" v-model="tambahForm.harga_beras" locale="id-ID" prefix="Rp" fluid/>
                <label for="on_label">Harga Beras</label>
            </FloatLabel>
            <span class="text-red-500" v-if="tambahForm.errors.harga_beras"> {{ tambahForm.errors.harga_beras }} </span>
        </div>

        <!-- Stok Beras -->
        <div>
            <FloatLabel variant="on">
                <InputNumber id="on_label" v-model="tambahForm.jml_stok" fluid/>
                <label for="on_label">Jumlah Stok</label>
            </FloatLabel>
            <span class="text-red-500" v-if="tambahForm.errors.jml_stok"> {{ tambahForm.errors.jml_stok }} </span>
        </div>

        <Button @click="submitProdusen" label="Submit" />
    </form>

</template>

<style scoped>
</style>
