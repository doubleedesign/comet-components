<script lang="ts">
// This page adds "in-page navigation" to pages with it enabled in the frontmatter with showInPageNav: true,
// so that the main sidebar can be kept simple by not having all the headings in it
// (it's complex enough with the nested structure it already has).
// Designed for pages that are long by necessity and have many sections.
// To be used sparingly: Would it be better to separate this content into multiple pages?
export default {
    data() {
        return {
            // Default values
            headings: [],
            showInPageNav: false,
            isOpen: false,
            title: '',
            isLargeViewport: false,
            activeHeading: ''
        }
    },
    mounted() {
        this.initialize();
        this.$router.afterEach(() => {
            this.initialize();
        });
    },
    methods: {
        initialize() {
            // Reset data on page change
            this.headings = [];
            this.showInPageNav = false;
            this.activeHeading = '';
            
            this.showInPageNav = this.$page.frontmatter.showInPageNav;
            if (this.showInPageNav) {
                this.headings = this.$page.headers;
                this.title = this.$page.title;

                this.checkScreenSize();
                window.addEventListener('resize', this.debounce(this.checkScreenSize, 200));
                window.addEventListener('urlHashChanged', this.debounce(this.updateActiveFromHash, 100));
            }
        },
        async checkScreenSize() {
            this.isLargeViewport = window.matchMedia(`(min-width: 1140px)`).matches;
            this.isOpen = this.isLargeViewport;
        },
        updateActiveFromHash() {
            if (window.location.hash) {
                this.activeHeading = window.location.hash.substring(1)
            }
        },
        debounce(func, delay) {
            let timerId;
            return function () {
                clearTimeout(timerId);
                timerId = setTimeout(func, delay);
            };
        },
    },
    beforeUnmount() {
        // Clean up event listeners
        window.removeEventListener('resize', this.debounceResize);
        window.removeEventListener('urlHashChanged', this.debounceUpdateHash);
    },
}
</script>

<template>
    <div class="in-page-nav" v-if="showInPageNav">
        <button class="in-page-nav__trigger"
                v-if="!isOpen"
                @click="isOpen = true"
                aria-label="Open in-page navigation"
                title="Open in-page navigation"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <!--!Font Awesome Pro 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2025 Fonticons, Inc.-->
                <path class="fa-secondary"
                      opacity=".4"
                      d="M160 96c0-17.7 14.3-32 32-32l288 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-288 0c-17.7 0-32-14.3-32-32zM288 256c0-17.7 14.3-32 32-32l160 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-160 0c-17.7 0-32-14.3-32-32zm0 160c0-17.7 14.3-32 32-32l160 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-160 0c-17.7 0-32-14.3-32-32z"
                />
                <path class="fa-primary"
                      d="M24 48C10.7 48 0 58.7 0 72l0 48c0 13.3 10.7 24 24 24l0 112 0 128c0 30.9 25.1 56 56 56l48 0c0 13.3 10.7 24 24 24l48 0c13.3 0 24-10.7 24-24l0-48c0-13.3-10.7-24-24-24l-48 0c-13.3 0-24 10.7-24 24l-48 0c-4.4 0-8-3.6-8-8l0-104 56 0c0 13.3 10.7 24 24 24l48 0c13.3 0 24-10.7 24-24l0-48c0-13.3-10.7-24-24-24l-48 0c-13.3 0-24 10.7-24 24l-56 0 0-88c13.3 0 24-10.7 24-24l0-48c0-13.3-10.7-24-24-24L24 48z"
                />
            </svg>
        </button>
        <Transition name="slide">
            <div class="in-page-nav__content" v-if="isOpen">
                <div class="in-page-nav__content__header">
                    <h2>{{ title }}</h2>
                    <button class="in-page-nav__close"
                            @click="isOpen = false"
                            aria-label="Close in-page navigation"
                            title="Close in-page navigation"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                            <!--!Font Awesome Pro 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2025 Fonticons, Inc.-->
                            <path d="M345 137c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-119 119L73 103c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l119 119L39 375c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l119-119L311 409c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-119-119L345 137z"/>
                        </svg>
                    </button>
                </div>
                <nav class="in-page-nav__content__menu">
                    <ol>
                        <li v-for="(heading, index) in headings"
                            :key="index"
                            :class="{ 'active': this.activeHeading === heading.slug }"
                        >
                            <a
                                :href="'#' + heading.slug"
                                v-if="heading.level === 2"
                            >
                                {{ heading.title }}
                            </a>
                            <ol v-if="heading.children.length > 0">
                                <li v-for="(child, childIndex) in heading.children"
                                    :key="childIndex"
                                    :class="{ 'active': this.activeHeading === child.slug }"
                                >
                                    <a
                                        :href="'#' + child.slug"
                                        v-if="child.level === 3"
                                    >
                                        {{ child.title }}
                                    </a>
                                </li>
                            </ol>
                        </li>
                    </ol>
                </nav>
            </div>
        </Transition>
    </div>
</template>

<style scoped lang="scss">
.in-page-nav {
    position: fixed;
    right: 0;
    top: calc(var(--announcement-banner-height) + var(--navbar-height));
    z-index: 1100;

    &__trigger,
    &__close {
        width: 2rem;
        height: 2rem;
        appearance: none;
        background: transparent;
        border: 0;
        cursor: pointer;
        transition: color 0.2s ease;

        svg path {
            transition: fill 0.2s ease;
            fill: currentColor;
        }

        &:hover, &:focus, &:active {
            color: var(--vp-c-accent);
        }
    }

    &__trigger {
        background: var(--vp-c-accent);
        color: white;
        transition: background-color 0.2s ease;
        height: 3rem;
        width: 2.5rem;
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
        transform: translateY(1rem);

        svg {
            width: 1.5rem;
            height: 1.5rem;
        }

        svg path {
            fill: currentColor;
        }

        &:hover, &:focus, &:active {
            color: white;
            background: color-mix(in srgb, var(--vp-c-accent), black 15%);
        }
    }

    &__close {
        color: var(--vp-c-text);
        width: 1.6rem;
        height: 1.6rem;
    }

    &__content {
        position: absolute;
        right: 0;
        top: 1rem;
        width: 16rem;
        background: #fff;
        box-shadow: 0 0 0.5rem 2px rgba(60, 60, 67, 0.15);
        padding: 0.5rem 1rem 1rem 1rem;
        border-radius: 0.25rem;
        overflow-y: auto;
        max-height: calc(100vh - 120px);

        &__header {
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--vp-c-gutter);
            margin-block-end: 0.25rem;

            h2 {
                font-size: 1.35rem;
                margin: 0;
                padding: 0;
                border: 0;
                line-height: 2.25;
            }
        }

        &__menu {

            ol {
                margin: 0;
                margin-inline-start: 1.5rem;
                padding: 0;
                list-style: decimal-leading-zero;

                li {

                    a {
                        display: block;
                        padding: 0.25rem 0;
                        color: var(--vp-c-text);
                        text-decoration: underline;
                        text-decoration-color: transparent;

                        &:hover, &:focus, &:active {
                            color: var(--vp-c-accent);
                            text-decoration-color: currentColor;
                        }
                    }

                    &.active {
                        > a {
                            color: var(--vp-c-accent);
                            text-decoration-color: currentColor;
                        }
                    }

                    ol {
                        list-style: circle;
                        margin-inline-start: 0.75rem;

                        li {
                            font-size: 0.9rem;
                            font-weight: 300;
                            line-height: 1.2;

                            a {
                                font-weight: 300;
                            }
                        }
                    }
                }
            }
        }
    }
}


// Transition animation
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
    opacity: 0;
}

.slide-enter-to,
.slide-leave-from {
    transform: translateX(0);
    opacity: 1;
}
</style>
