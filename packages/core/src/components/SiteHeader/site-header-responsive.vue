<script>
export default {
    name: 'SiteHeaderResponsive',
    inheritAttrs: false,
    props: {
        breakpoint: String,
        toggleButtonIconPrefix: String,
        toggleButtonIconClass: String,
        submenuToggleIconClass: String,
        menuData: String, // JSON encoded string of the menu array, so Vue can parse and render it
        menuHtml: String, // prerendered menu HTML for when we don't need Vue to do anything with it
        responsiveStartHtml: String, // prerendered HTML to appear before the responsive menu
        responsiveEndHtml: String, // prerendered HTML to appear after the responsive menu
    },
    data() {
        return {
            responsiveMenuOpen: true,
            isBelowBreakpoint: true,
            menuItems: [],
            submenus: {} // Track state of submenus by their parent item's ID
        };
    },
    mounted() {
        this.menuItems = JSON.parse(this.menuData || '[]');

        // Check screen size first; or if breakpoint is 0, we're always in responsive mode
        if (['0', '0px', '0rem'].includes(this.breakpoint)) {
            this.isBelowBreakpoint = true;
        } else {
            this.checkScreenSize();
        }

        // If we're in "desktop" mode, we want the menu visible
        this.responsiveMenuOpen = !this.isBelowBreakpoint;

        // Listen for resize events and re-check screen size (if breakpoint is not 0)
        if (!['0', '0px', '0rem'].includes(this.breakpoint)) {
            window.addEventListener('resize', this.debounce(this.checkScreenSize, 200));
        }

        // Store heights of all menu-list elements after DOM is fully rendered
        this.$nextTick(() => {
            // Ensure the DOM is fully rendered before measuring
            setTimeout(() => {
                this.setSubmenuHeights();
            }, 0);
        });
    },
    computed: {},
    methods: {
        toggleMenu() {
            this.responsiveMenuOpen = !this.responsiveMenuOpen;
            if (this.responsiveMenuOpen) {
                this.setSubmenuHeights();
            }
        },
        toggleSubmenu(itemId) {
            console.log('Toggling submenu', itemId);
            this.submenus[itemId] = {
                ...this.submenus?.[itemId],
                open: !this.submenus[itemId]?.open ?? true
            }
        },
        setSubmenuHeights() {
            setTimeout(() => {
                const submenus = document.querySelectorAll('.menu-list--responsive');
                submenus.forEach((menuList, index) => {
                    try {
                        const height = menuList.scrollHeight;
                        this.submenus[menuList.id.replace('submenu-', '')] = {
                            ...this.submenus?.[menuList.id.replace('submenu-', '')],
                            height
                        };
                    } catch (error) {
                        console.warn(`Error measuring menu list ${index}:`, error);
                    }
                });
            }, 200);
        },
        async checkScreenSize() {
            this.isBelowBreakpoint = window.matchMedia(`(max-width: ${this.breakpoint})`).matches;
        },
        debounce(func, delay) {
            let timerId;
            return function () {
                clearTimeout(timerId);
                timerId = setTimeout(func, delay);
            };
        },
    },
};
</script>

<template>
    <button @click="toggleMenu"
            class="site-header__responsive__menu-toggle"
            v-if="isBelowBreakpoint"
            aria-label="Toggle menu"
            title="Toggle menu"
            aria-haspopup="true"
            :aria-expanded="responsiveMenuOpen"
    >
        <i v-if="responsiveMenuOpen" :class="toggleButtonIconPrefix + ' fa-close'"></i>
        <i v-else :class="toggleButtonIconPrefix + ' ' + toggleButtonIconClass"></i>
    </button>
    <div v-if="isBelowBreakpoint" class="site-header__responsive__content" :data-open="responsiveMenuOpen">
        <Transition>
            <div v-if="responsiveMenuOpen">
                <div v-if="responsiveStartHtml"
                     class="site-header__responsive__start"
                     v-html="responsiveStartHtml"
                ></div>
                <nav class="site-header__menu--responsive">
                    <ul class="site-header__menu-list">
                        <li v-for="item in menuItems"
                            :key="item.id"
                            :class="[...Object.values(item?.classes)].join(' ')"
                        >
                            <a :href="item.link_attributes.href"
                               :class="[...Object.values(item.link_attributes?.classes)].join(' ')"
                               :aria-current="item.link_attributes['aria-current'] ?? null"
                            >
                                {{ item.title }}
                            </a>
                            <button v-if="item.children.length > 0"
                                    class="site-header__menu-list__item__toggle"
                                    aria-label="Toggle submenu"
                                    @click="toggleSubmenu(item.id)"
                                    :aria-controls="`submenu-${item.id}`"
                                    :aria-haspopup="true"
                                    :aria-expanded="submenus[item.id]?.open ?? 'false'"
                            >
                                <i :class="toggleButtonIconPrefix + ' ' + submenuToggleIconClass"></i>
                            </button>
                            <ul v-if="item.children.length > 0"
                                class="menu-list menu-list--responsive"
                                :data-open="submenus?.[item.id]?.open"
                                :aria-hidden="submenus?.[item.id]?.open ? 'false' : 'true'"
                                :style="{ height: submenus?.[item.id]?.open ? submenus?.[item.id]?.height + 'px' : '0' }"
                                :id="`submenu-${item.id}`"
                            >
                                <li v-for="child in item.children"
                                    :key="child.id"
                                    :class="[...Object.values(child?.classes)].join(' ')"
                                >
                                    <a :href="child.link_attributes.href"
                                       :class="[...Object.values(child.link_attributes?.classes)].join(' ')"
                                       :aria-current="child.link_attributes['aria-current'] ?? null"
                                       :tabindex="submenus?.[item.id]?.open ? '0' : '-1'"
                                    >
                                        {{ child.title }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <div v-if="responsiveEndHtml" class="site-header__responsive__end" v-html="responsiveEndHtml"></div>
            </div>
        </Transition>
    </div>
    <template v-else>
        <div v-if="responsiveStartHtml" class="site-header__responsive__start" v-html="responsiveStartHtml"></div>
        <div v-if="menuHtml" class="site-header__responsive__menu" v-html="menuHtml"></div>
        <div v-if="responsiveEndHtml" class="site-header__responsive__end" v-html="responsiveEndHtml"></div>
    </template>

</template>

<style lang="css">
.v-enter-active,
.v-leave-active {
    transition: opacity 0.3s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
