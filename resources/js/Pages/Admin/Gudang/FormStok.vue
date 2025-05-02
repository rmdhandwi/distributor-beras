<script setup>
import { onMounted, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useConfirm, useToast } from 'primevue'


onMounted(() =>
{
    console.log(props.dataBeras)
    if(props.formType==='Create')
    {
        selectDataBeras.value = props.dataBeras?.map(item => ({
            id_beras : item.id_beras,
            nama_beras : item.nama_beras,
        }))
    }
})

const props = defineProps({
    formType : String,
    dataBeras : Object,
    dataGudang : Object,
})

const confirm = useConfirm()
const toast = useToast()

const emit = defineEmits(['refreshPage'])

const selectDataBeras = ref(null)
const selectProdusen = ref(null)
const selectBeras = ref(null)

const stokForm = useForm({
    id_beras : props.dataGudang?.[0]?.id_beras ?? null,
    id_produsen : props.dataGudang?.[0]?.id_produsen ??null,
    stok_awal : props.dataGudang?.[0]?.stok_awal ??  null,
    rusak : props.dataGudang?.[0]?.rusak ?? null,
    hilang : props.dataGudang?.[0]?.hilang ?? null,
    stok_sisa : props.dataGudang?.[0]?.stok_sisa ?? null,
})


const selectIdProdusen = async () =>
{
    const filterData = await props.dataBeras?.find(item => item.id_beras === stokForm.id_beras)
    selectProdusen.value = filterData.produsen.nama_produsen
    selectBeras.value = filterData

    stokForm.id_produsen = filterData.id_produsen
    stokForm.stok_awal = filterData.stok_awal
}

const submitStok = Action =>
{
    confirm.require({
        message: `${Action} Stok ${stokForm.nama_beras??''}?`,
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: `${Action}`,
        },
        accept: () => {
            if(props.formType === 'Create')
            {
                stokForm.post(route('admin.gudang.store'), {
                    onError : () => {
                        toast.add({
                            severity : 'error',
                            summary : 'Notifikasi',
                            detail : 'Terjadi kesalahan',
                            life : 3000,
                        })
                    },
                    onSuccess : () => {
                        stokForm.reset()
                        stokForm.clearErrors()
                        emit('refreshPage')
                    }
                })
            }
            else stokForm.post(route('admin.gudang.update'), {
                onError : () => {
                    toast.add({
                        severity : 'error',
                        summary : 'Notifikasi',
                        detail : 'Terjadi kesalahan',
                        life : 3000,
                    })
                },
                onSuccess : () => {
                    stokForm.reset()
                    stokForm.clearErrors()
                    emit('refreshPage')
                }
            })
        },
    })
}

const confirmHapus = () => {
    confirm.require({
        message: 'Hapus Data stok?',
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batalkan',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Hapus',
            severity: 'danger'
        },
        accept : () => {
            toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'Menghapus Stok...', life: 2000 });
            stokForm.post(route('admin.gudang.destroy'), {
                onError : () => {
                    toast.add({
                        severity : 'error',
                        summary : 'Notifikasi',
                        detail : 'Terjadi kesalahan',
                        life : 3000,
                    })
                },
                onSuccess : () => {
                    stokForm.reset()
                    stokForm.clearErrors()
                    emit('refreshPage')
                }
            })

        },
    });
}
</script>

<template>
    <form @submit.prevent class="flex flex-col gap-4 mt-4" autocomplete="off">
        <!-- Pilih beras -->
        <div v-if="props.formType==='Create'">
            <FloatLabel variant="on">
                <Select @change="selectIdProdusen()" v-model="stokForm.id_beras" inputId="id_beras" :options="selectDataBeras" optionLabel="nama_beras" optionValue="id_beras" fluid/>
                <label for="id_beras">Pilih Beras</label>
            </FloatLabel>
            <span class="text-red-500" v-if="stokForm.errors.id_beras"> {{ stokForm.errors.id_beras }} </span>
        </div>

        <!-- Nama produsen -->
        <div>
            <FloatLabel variant="on">
                <InputText id="produsen" v-model="selectProdusen" fluid disabled/>
                <label for="produsen">Produsen</label>
            </FloatLabel>
            <span class="text-red-500" v-if="stokForm.errors.id_produsen"> {{ stokForm.errors.id_produsen }} </span>
        </div>

        <!-- Stok Awal -->
        <div>
            <FloatLabel variant="on">
                <InputNumber mode="decimal" showButtons :min="0" :max="selectBeras?.stok_awal ?? 100" id="stok_awal" v-model="stokForm.stok_awal" fluid/>
                <label for="stok_awal">Stok Awal</label>
            </FloatLabel>
            <span class="text-red-500" v-if="stokForm.errors.stok_awal"> {{ stokForm.errors.stok_awal }} </span>
        </div>

        <!-- Stok Rusak -->
        <div>
            <FloatLabel variant="on">
                <InputNumber mode="decimal" showButtons :min="0" :max="selectBeras?.stok_awal ?? 100" id="rusak" v-model="stokForm.rusak" fluid/>
                <label for="rusak">Stok Rusak</label>
            </FloatLabel>
            <span class="text-red-500" v-if="stokForm.errors.rusak"> {{ stokForm.errors.rusak }} </span>
        </div>

        <!-- Stok Hilang -->
        <div>
            <FloatLabel variant="on">
                <InputNumber mode="decimal" showButtons :min="0" :max="selectBeras?.stok_awal ?? 100" id="hilang" v-model="stokForm.hilang" fluid/>
                <label for="hilang">Stok Hilang</label>
            </FloatLabel>
            <span class="text-red-500" v-if="stokForm.errors.hilang"> {{ stokForm.errors.hilang }} </span>
        </div>

        <!-- Stok Hilang -->
        <div>
            <FloatLabel variant="on">
                <InputNumber mode="decimal" showButtons :min="0" :max="selectBeras?.stok_awal ?? 100" id="hilang" v-model="stokForm.stok_sisa" fluid/>
                <label for="stok_sisa">Stok Sisa</label>
            </FloatLabel>
            <span class="text-red-500" v-if="stokForm.errors.stok_sisa"> {{ stokForm.errors.stok_sisa }} </span>
        </div>

        <Button @click="submitStok('Tambah')" label="Submit" v-if="props.formType==='Create'"/>
        <Button @click="submitStok('Update')" label="Update"  v-if="props.formType==='Edit'"/>
        <Button @click="confirmHapus()" label="Hapus Stok" severity="danger"  v-if="props.formType==='Edit'"/>

    </form>

</template>

<style scoped>
</style>
