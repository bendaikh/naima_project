<template>
    <div class="vue-app rounded-lg p-6 bg-white dark:bg-[#161615] shadow-lg border border-[#e3e3e0] dark:border-[#3E3E3A]">
        <h2 class="text-xl font-semibold mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">Vue.js is working</h2>
        <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4">
            This content is rendered by Vue. The rest of the page is Blade.
        </p>
        <p class="text-sm">Counter: {{ count }}</p>
        <button
            type="button"
            @click="count++"
            class="mt-2 px-4 py-2 rounded-sm bg-[#1b1b18] dark:bg-[#eeeeec] dark:text-[#1C1C1A] text-white hover:opacity-90"
        >
            Increment
        </button>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const count = ref(0);
</script>
