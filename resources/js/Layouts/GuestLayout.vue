<script setup>
import leftImg from '../../assets/img/login-img.jpg'

</script>

<template>
    <Toast position="top-center" group="tc"/>
    <div class="flex min-h-screen justify-around items-center p-4">
        <div class="overflow-hidden rounded-2xl hidden lg:block lg:w-[800px]">
            <img :src="leftImg" alt="warehouse picture" class="size-full">
        </div>
        <div class="flex flex-col p-8 lg:p-16 lg:gap-y-4 lg:w-[500px]">
            <h1 class="text-2xl lg:text-4xl font-semibold">Distributor Beras</h1>
            <!-- form login/signup -->
            <slot/>
        </div>
    </div>
</template>

<style scoped>
</style>
