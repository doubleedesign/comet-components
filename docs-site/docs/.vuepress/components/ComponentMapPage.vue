<script lang="ts">
import Modal from './Modal.vue';
import ComponentMap from './ComponentMap.vue';

export default {
    props: {},
    components: {
        Modal,
        ComponentMap
    },
    data() {
        return {
            showModal: false
        };
    },
    computed: {},
    methods: {
        openModal() {
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        }
    }
};
</script>;

<template>
    <div class="component-map-page">
        <button @click="openModal">Open full-screen</button>
        <ComponentMap></ComponentMap>

        <Teleport to=".vp-page">
            <Modal :show="showModal" @close="closeModal">
                <template #content>
                    <ComponentMap></ComponentMap>
                </template>
            </Modal>
        </Teleport>
    </div>
</template>;

<style scoped lang="scss">
.component-map-page {

}
</style>;
