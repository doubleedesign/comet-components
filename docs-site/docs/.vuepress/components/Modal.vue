<script>
export default {
    props: {
        show: Boolean,
    }
    // TODO: Close on esc keypress
}
</script>

<template>
    <Transition name="modal">
        <div v-if="show" class="modal">
            <div class="modal__content">
                <div class="modal__content__header">
                    <button class="close-button" @click="$emit('close')" aria-label="Close modal">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                            <!--!Font Awesome Pro v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2025 Fonticons, Inc.-->
                            <path d="M135.5 169C126.1 159.6 126.1 144.4 135.5 135.1C144.9 125.8 160.1 125.7 169.4 135.1L320.4 286.1L471.4 135.1C480.8 125.7 496 125.7 505.3 135.1C514.6 144.5 514.7 159.7 505.3 169L354.3 320L505.3 471C514.7 480.4 514.7 495.6 505.3 504.9C495.9 514.2 480.7 514.3 471.4 504.9L320.4 353.9L169.4 504.9C160 514.3 144.8 514.3 135.5 504.9C126.2 495.5 126.1 480.3 135.5 471L286.5 320L135.5 169z"/>
                        </svg>
                    </button>
                </div>
                <div class="modal__content__body">
                    <slot name="content"></slot>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style lang="scss">
.modal {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    transition: opacity 0.3s ease;

    &__content {
        position: absolute;
        inset: 1rem;
        padding: 1rem;
        background-color: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
        transition: all 0.3s ease;

        &__header {
            display: flex;
            justify-content: flex-end;

            .close-button {
                appearance: none;
                background: transparent;
                border: 0;
                cursor: pointer;
                transition: 0.2s ease;

                svg {
                    width: 1.5rem
                }

                &:hover, &:focus {
                    color: var(--info-c-text);

                    svg {
                        fill: var(--info-c-text);
                    }
                }
            }
        }

        &__body {

        }
    }
}


.modal-enter-from {
    opacity: 0;
}

.modal-leave-to {
    opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}
</style>
