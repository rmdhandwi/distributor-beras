<script setup>
import { onMounted, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useConfirm, useToast } from 'primevue'


onMounted(() =>
{
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
    id_gudang : props.dataGudang?.[0]?.id_gudang ?? null,
    id_beras : props.dataGudang?.[0]?.id_beras ?? null,
    id_produsen : props.dataGudang?.[0]?.id_produsen ?? null,
    stok10kg : {
        berat : '10',
        id_detail_gudang : props.dataGudang?.[0]?.stok10kg?.id_detail_gudang ?? null,
        stok_awal : props.dataGudang?.[0]?.stok10kg?.stok_awal ?? 0,
        rusak : props.dataGudang?.[0]?.stok10kg?.rusak ?? 0,
        hilang : props.dataGudang?.[0]?.stok10kg?.hilang ?? 0,
        stok_sisa : props.dataGudang?.[0]?.stok10kg?.stok_sisa ?? 0,
    },
    stok20kg : {
        berat : '20',
        id_detail_gudang : props.dataGudang?.[0]?.stok20kg?.id_detail_gudang ?? 0,
        stok_awal : props.dataGudang?.[0]?.stok20kg?.stok_awal ?? 0,
        rusak : props.dataGudang?.[0]?.stok20kg?.rusak ?? 0,
        hilang : props.dataGudang?.[0]?.stok20kg?.hilang ?? 0,
        stok_sisa : props.dataGudang?.[0]?.stok20kg?.stok_sisa ?? 0,
    },
    stok50kg : {
        berat : '50',
        id_detail_gudang : props.dataGudang?.[0]?.stok50kg?.id_detail_gudang ?? 0,
        stok_awal : props.dataGudang?.[0]?.stok50kg?.stok_awal ?? 0,
        rusak : props.dataGudang?.[0]?.stok50kg?.rusak ?? 0,
        hilang : props.dataGudang?.[0]?.stok50kg?.hilang ?? 0,
        stok_sisa : props.dataGudang?.[0]?.stok50kg?.stok_sisa ?? 0,
    },
})


const selectIdProdusen = async () =>
{
    const filterData = await props.dataBeras?.find(item => item.id_beras === stokForm.id_beras)
    selectProdusen.value = filterData.produsen.nama_produsen
    selectBeras.value = filterData

    stokForm.id_produsen = filterData.id_produsen
    stokForm.stok_awal = filterData.stok_awal
}

watch(
    () => [
        [stokForm.stok10kg.stok_awal, stokForm.stok10kg.rusak, stokForm.stok10kg.hilang],
        [stokForm.stok20kg.stok_awal, stokForm.stok20kg.rusak, stokForm.stok20kg.hilang],
        [stokForm.stok50kg.stok_awal, stokForm.stok50kg.rusak, stokForm.stok50kg.hilang],
    ],
    () => {
        stokForm.stok10kg.stok_sisa = stokForm.stok10kg.stok_awal - stokForm.stok10kg.rusak - stokForm.stok10kg.hilang
        stokForm.stok20kg.stok_sisa = stokForm.stok20kg.stok_awal - stokForm.stok20kg.rusak - stokForm.stok20kg.hilang
        stokForm.stok50kg.stok_sisa = stokForm.stok50kg.stok_awal - stokForm.stok50kg.rusak - stokForm.stok50kg.hilang
    },
    { immediate : true }
)

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
        <div v-else>
            <FloatLabel variant="on">
                <InputText id="nama_beras" :default-value="props.dataGudang[0].beras.nama_beras" fluid readonly/>
                <label for="nama_beras">Beras</label>
            </FloatLabel>
            <span class="text-red-500" v-if="stokForm.errors.id_beras"> {{ stokForm.errors.id_beras }} </span>
        </div>

        <!-- Nama produsen -->
        <div v-if="props.formType==='Create'">
            <FloatLabel variant="on">
                <InputText id="produsen" v-model="selectProdusen" readonly fluid/>
                <label for="produsen">Produsen</label>
            </FloatLabel>
            <span class="text-red-500" v-if="stokForm.errors.id_produsen"> {{ stokForm.errors.id_produsen }} </span>

        </div>
        <div v-else>
            <FloatLabel variant="on">
                <InputText id="produsen" :default-value="props.dataGudang[0].produsen.nama_produsen" readonly fluid/>
                <label for="produsen">Produsen</label>
            </FloatLabel>
            <span class="text-red-500" v-if="stokForm.errors.id_produsen"> {{ stokForm.errors.id_produsen }} </span>
        </div>

        <!-- Stok 10 kg -->
        <div class="flex items-center gap-4">
            <Tag value="10kg"/>
            <!-- Stok Awal -->
            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" showButtons :min="0" id="stok_awal" v-model="stokForm.stok10kg.stok_awal" fluid/>
                    <label for="stok_awal">Stok Awal</label>
                </FloatLabel>
                <span class="text-red-500" v-if="stokForm.errors['stok10kg.stok_awal']"> {{ stokForm.errors['stok10kg.stok_awal'] }} </span>
            </div>

            <!-- Stok Rusak -->
            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" showButtons :min="0" id="rusak" v-model="stokForm.stok10kg.rusak" fluid/>
                    <label for="rusak">Stok Rusak</label>
                </FloatLabel>
                <span class="text-red-500" v-if="stokForm.errors['stok10kg.rusak']"> {{ stokForm.errors['stok10kg.rusak'] }} </span>
            </div>

            <!-- Stok Hilang -->
            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" showButtons :min="0" id="hilang" v-model="stokForm.stok10kg.hilang" fluid/>
                    <label for="hilang">Stok Hilang</label>
                </FloatLabel>
                <span class="text-red-500" v-if="stokForm.errors['stok10kg.hilang']"> {{ stokForm.errors['stok10kg.hilang'] }} </span>
            </div>

            <!-- Stok Sisa -->
            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" :min="0" id="stok_sisa" v-model="stokForm.stok10kg.stok_sisa" readonly fluid/>
                    <label for="stok_sisa">Stok Sisa</label>
                </FloatLabel>
                <span class="text-red-500" v-if="stokForm.errors['stok10kg.stok_sisa']"> {{ stokForm.errors['stok10kg.stok_sisa'] }} </span>
            </div>

        </div>

        <!-- Stok 20 kg -->
        <div class="flex items-center gap-4">
            <Tag value="20kg"/>
            <!-- Stok Awal -->
            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" showButtons :min="0" id="stok_awal" v-model="stokForm.stok20kg.stok_awal" fluid/>
                    <label for="stok_awal">Stok Awal</label>
                </FloatLabel>
                <span class="text-red-500" v-if="stokForm.errors['stok20kg.stok_awal']"> {{ stokForm.errors['stok20kg.stok_awal'] }} </span>
            </div>

            <!-- Stok Rusak -->
            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" showButtons :min="0" id="rusak" v-model="stokForm.stok20kg.rusak" fluid/>
                    <label for="rusak">Stok Rusak</label>
                </FloatLabel>
                <span class="text-red-500" v-if="stokForm.errors['stok20kg.rusak']"> {{ stokForm.errors['stok20kg.rusak'] }} </span>
            </div>

            <!-- Stok Hilang -->
            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" showButtons :min="0" id="hilang" v-model="stokForm.stok20kg.hilang" fluid/>
                    <label for="hilang">Stok Hilang</label>
                </FloatLabel>
                <span class="text-red-500" v-if="stokForm.errors['stok20kg.hilang']"> {{ stokForm.errors['stok20kg.hilang'] }} </span>
            </div>

            <!-- Stok Sisa -->
            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" :min="0" id="stok_sisa" v-model="stokForm.stok20kg.stok_sisa" readonly fluid/>
                    <label for="stok_sisa">Stok Sisa</label>
                </FloatLabel>
                <span class="text-red-500" v-if="stokForm.errors['stok20kg.stok_sisa']"> {{ stokForm.errors['stok20kg.stok_sisa'] }} </span>
            </div>

        </div>

        <!-- Stok 50 kg -->
        <div class="flex items-center gap-4">
            <Tag value="50kg"/>
            <!-- Stok Awal -->
            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" showButtons :min="0" id="stok_awal" v-model="stokForm.stok50kg.stok_awal" fluid/>
                    <label for="stok_awal">Stok Awal</label>
                </FloatLabel>
                <span class="text-red-500" v-if="stokForm.errors['stok50kg.stok_awal']"> {{ stokForm.errors['stok50kg.stok_awal'] }} </span>
            </div>

            <!-- Stok Rusak -->
            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" showButtons :min="0" id="rusak" v-model="stokForm.stok50kg.rusak" fluid/>
                    <label for="rusak">Stok Rusak</label>
                </FloatLabel>
                <span class="text-red-500" v-if="stokForm.errors['stok50kg.rusak']"> {{ stokForm.errors['stok50kg.rusak'] }} </span>
            </div>

            <!-- Stok Hilang -->
            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" showButtons :min="0" id="hilang" v-model="stokForm.stok50kg.hilang" fluid/>
                    <label for="hilang">Stok Hilang</label>
                </FloatLabel>
                <span class="text-red-500" v-if="stokForm.errors['stok50kg.hilang']"> {{ stokForm.errors['stok50kg.hilang'] }} </span>
            </div>

            <!-- Stok Sisa -->
            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" :min="0" id="stok_sisa" v-model="stokForm.stok50kg.stok_sisa" readonly fluid/>
                    <label for="stok_sisa">Stok Sisa</label>
                </FloatLabel>
                <span class="text-red-500" v-if="stokForm.errors['stok50kg.stok_sisa']"> {{ stokForm.errors['stok50kg.stok_sisa']}} </span>
            </div>

        </div>


        <Button @click="submitStok('Tambah')" label="Submit" v-if="props.formType==='Create'"/>
        <Button @click="submitStok('Update')" label="Update"  v-if="props.formType==='Edit'"/>
        <Button @click="confirmHapus()" label="Hapus Stok" severity="danger"  v-if="props.formType==='Edit'"/>

    </form>

</template>

<style scoped>
</style>
