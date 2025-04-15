<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue';

import { adminMenu } from '@/Composables/SidebarLists'

const adminMenus = ref(adminMenu)

</script>

<template>
   <div class="w-[200px] transition-all ease-in-out duration-[450ms] rounded-lg h-[99vh] p-2 flex flex-col bg-gradient-to-b from-amber-500 to-amber-400 gap-[2rem] justify-between fixed">

        <div class="flex flex-col gap-4 text-lg items-center">
            <Button v-if="$page.props.auth.user.role === 'Admin'" v-for="menu in adminMenus" :key="menu.route" :label="menu.label" :icon="menu.icon" class="w-full p-1 flex items-center justify-center gap-2 rounded" :class="{'bg-slate-50 text-amber-500' : route().current(menu.current), 'text-slate-50 bg-transparent' : !route().current(menu.route)}" unstyled/>
        </div>
    </div>
</template>

<style scoped>
</style>
