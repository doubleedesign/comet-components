import '@testing-library/jest-dom';
import jestAxe from 'jest-axe';
import { createApp } from 'vue';

// Add Jest matcher for accessibility testing
const axe = jestAxe.configureAxe({rules: []});
expect.extend(jestAxe.toHaveNoViolations);

// Make Vue globally available for @testing-library/vue
global.Vue = createApp;
globalThis.Vue = createApp;

// Mock global objects that might not be available in the test environment
global.requestAnimationFrame = (callback) => {
	setTimeout(callback, 0);
};

