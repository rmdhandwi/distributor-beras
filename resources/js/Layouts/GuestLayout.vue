<script setup>
import leftImg from '../../assets/img/login-img.jpg'
</script>

<template>
    <div class="flex min-h-screen justify-around items-center p-4">
        <div class="overflow-hidden rounded-2xl hidden lg:block lg:w-[800px]">
            <img :src="leftImg" alt="warehouse picture" class="size-full">
        </div>
        <div class="flex flex-col border border-blue-500 rounded-2xl p-8 lg:p-16">
            <h1 class="text-2xl lg:text-4xl font-semibold">Distributor Beras</h1>
            <!-- form login/signup -->
            <slot/>
        </div>
    </div>
</template>

<style scoped>
</style>
