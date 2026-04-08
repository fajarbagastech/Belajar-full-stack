<template>
    <div class="space-y-4 animate-pulse">
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div v-for="i in 3" :key="i" class="h-32 bg-gray-200 rounded-3xl"></div>
        </div>

        <div class="p-6 bg-white shadow-sm rounded-3xl">
            <div class="w-1/4 h-6 mb-6 bg-gray-200 rounded"></div>
            <div v-for="i in 5" :key="i" class="flex items-center justify-between py-4 border-b border-gray-100">
                <div class="w-1/3 h-4 bg-gray-200 rounded"></div>
                <div class="w-1/4 h-4 bg-gray-200 rounded"></div>
                <div class="w-16 h-4 bg-gray-200 rounded"></div>
            </div>
        </div>
    </div>
</template>