<script setup>
import { Link } from "@inertiajs/vue3";
defineProps({ links: Array });
</script>

<template>
    <div class="d-flex gap-2">
        <template v-for="(link, index) in links" :key="index">
            <span
                v-if="!link.url"
                class="btn btn-sm btn-secondary disabled"
                v-html="link.label"
            >
            </span>
            <span
                v-else-if="link.active"
                class="btn btn-sm btn-primary"
                v-html="link.label"
            >
            </span>
            <Link
                v-else
                :href="link.url"
                class="btn btn-sm btn-outline-primary"
                v-html="link.label"
            >
            </Link>
        </template>
    </div>
</template>
