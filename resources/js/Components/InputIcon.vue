<template>
    <div class="relative flex">
        <TextInput v-bind="$attrs" :class="{
            'pl-12': iconPosition == 'left'
        }"></TextInput>
        <div class="absolute bottom-[1px] top-[1px] flex items-center bg-slate-100 rounded" :class="{
            'left-[1px] rounded-r-none': iconPosition == 'left',
            'right-[1px] rounded-l-none': iconPosition == 'right',
            'pointer-events-none': !pointerEvents,
        }">
            <div class="px-2">
                <slot />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    inheritAttrs: false
}
</script>

<script setup>
import TextInput from '@/Components/TextInput.vue';
defineProps({
    iconPosition: {
        type: String,
        default: 'right',
    },
    pointerEvents: {
        type: Boolean,
        default: false,
    }
});
</script>
