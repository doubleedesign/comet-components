<script lang="ts">
export default {
    data() {
        return {
            isLocal: false
        }
    },
    mounted() {
        const currentHost = window.location.hostname;
        if(currentHost === 'comet-components.test') {
            this.isLocal = true;
        } else {
            this.isLocal = false;
        }
    }
}
</script>

<template>
    <div class="announcement-banner">
        <div v-if="isLocal" class="announcement-banner__info">
            <p><strong>Local dev:</strong> This is the built version of the docs. If you are working on the docs, you will need to run the dev server for that separately.</p>
        </div>
        <div v-if="!isLocal" class="announcement-banner__info">
            <p><strong>Comet Components is an active work in progress.</strong> These docs and the code currently available via GitHub should be considered an incomplete alpha release.</p>
        </div>
    </div>
</template>

<style scoped lang="scss">

.announcement-banner {
    position: sticky;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    text-align: center;
    font-weight: 300;

    strong {
        font-weight: 600;
    }

    p {
        margin: 0;
    }

    &__info {
        background: color-mix(in srgb, #00d2fc, black 20%);
        color: white;
        padding: 0.5rem;
    }

    &__warning {
        background: color-mix(in srgb, #fc8f00, black 10%);
        color: white;
        padding: 0.5rem;
    }
}
</style>;
