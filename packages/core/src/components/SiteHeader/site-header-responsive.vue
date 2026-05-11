<script>
export default {
	name: 'SiteHeaderResponsive',
	inheritAttrs: false,
	props: {
		breakpoint: String,
		responsiveStyle: String, // "default" or "overlay" - the $responsiveStyle PHP value, applicable when below the breakpoint
		toggleButtonIconPrefix: String,
		toggleButtonIconClass: String,
		submenuToggleIconClass: String,
		menuData: String, // JSON encoded string of the menu array, so Vue can parse and render it
		menuHtml: String, // pre-prepared menu HTML for when we don't need Vue to do anything with it
		overlayBackground: String, // background color name for the overlay
		extraContentHtml: String // pre-prepared HTML of other components to render after the menu
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
		}
		else {
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
			this.setSubmenuHeights();
		});
	},
	computed: {},
	methods: {
		toggleMenu() {
			this.responsiveMenuOpen = !this.responsiveMenuOpen;
		},
		toggleSubmenu(itemId) {
			this.submenus[itemId] = {
				...this.submenus?.[itemId],
				open: !this.submenus[itemId]?.open ?? true
			};
		},
		setSubmenuHeights() {
			setTimeout(() => {
				const submenus = document.querySelectorAll('[class*="__sub-menu"]');
				submenus.forEach((menuList, index) => {
					try {
						const height = menuList.scrollHeight;
						this.submenus[menuList.id.replace('submenu-', '')] = {
							...this.submenus?.[menuList.id.replace('submenu-', '')],
							height
						};
					}
					catch (error) {
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
            class="site-header__menu-toggle"
            v-if="isBelowBreakpoint"
            aria-label="Toggle menu"
            title="Toggle menu"
            aria-haspopup="true"
            :data-menu-style="responsiveStyle"
            :aria-expanded="responsiveMenuOpen"
    >
        <!-- Note: Font Awesome needs to be using Web Font mode, not SVG mode, for this to work -->
        <i :class="[toggleButtonIconPrefix, responsiveMenuOpen ? 'fa-close' : toggleButtonIconClass]"></i>
    </button>
    <!-- Content in overlay or off-canvas mode (usually mobile/small viewports, unless always using overlay) -->
    <div v-if="isBelowBreakpoint"
         class="site-header__responsive-content-wrapper"
         :data-style="responsiveStyle"
         :data-open="responsiveMenuOpen"
         :data-background="overlayBackground"
    >
        <Transition @after-enter="setSubmenuHeights">
            <div class="site-header__responsive-content-wrapper__inner" v-if="responsiveMenuOpen" >
                <nav class="site-header__menu">
                        <ul class="site-header__menu__list">
                            <li v-for="item in menuItems"
                                :key="item.id"
                                :class="[...Object.values(item?.classes)].join(' ')"
                            >
                                <a :href="item.link_attributes.href"
                                   :class="[...Object.values(item.link_attributes?.classes)].join(' ')"
                                   :aria-current="item.link_attributes['aria-current'] ?? null"
                                   :target="item.link_attributes['target'] ?? null"
                                   :data-color-theme="item.link_attributes['data-color-theme'] ?? null"
                                >
                                    <span v-html="item.title"></span>
                                </a>
                                <button v-if="item.children.length > 0"
                                        class="site-header__menu__list__item__toggle"
                                        aria-label="Toggle submenu"
                                        @click="toggleSubmenu(item.id)"
                                        :aria-controls="`submenu-${item.id}`"
                                        :aria-haspopup="true"
                                        :aria-expanded="submenus[item.id]?.open ?? 'false'"
                                >
                                    <i :class="toggleButtonIconPrefix + ' ' + submenuToggleIconClass"></i>
                                </button>
                                <ul v-if="item.children.length > 0"
                                    class="site-header__menu__sub-menu"
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
                                            <span v-html="child.title"></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                <div class="site-header__overlay-content" v-if="extraContentHtml" v-html="extraContentHtml"></div>
            </div>
        </Transition>

    </div>
    <!-- Content in non-overlay/non-off-canvas mode (usually desktop/larger viewports, unless always using overlay/off-canvas) -->
    <template v-else>
        <component v-if="menuHtml" v-html="menuHtml"></component>
    </template>
</template>

<style lang="css">
.site-header__responsive-content-wrapper[data-style="overlay"] {
    .v-enter-active,
    .v-leave-active {
        transition: opacity 0.3s ease;
    }

    .v-enter-from,
    .v-leave-to {
        opacity: 0;
    }
}

.site-header__responsive-content-wrapper[data-style="off-canvas"] {
    .v-enter-active,
    .v-leave-active {
        transition: transform 0.3s ease;
    }

    .v-enter-from,
    .v-leave-to {
        transform: translateX(-100%);
    }
}
</style>
