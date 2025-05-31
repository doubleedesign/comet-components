// jest.config.js (rename from .mjs if you have that)
export default {
	testEnvironment: 'jsdom',
	transform: {
		'^.+\\.vue$': '@vue/vue3-jest',
		'^.+\\.(ts|tsx)$': 'ts-jest',
		'^.+\\.js$': 'babel-jest'
	},
	moduleFileExtensions: ['vue', 'js', 'ts', 'tsx', 'jsx', 'json'],
	moduleNameMapper: {
		'^@/(.*)$': '<rootDir>/src/$1',
		'^vue$': 'vue/dist/vue.cjs.js'
	},
	testMatch: [
		'**/src/**/__tests__/*.test.(js|ts)'
	],
	setupFilesAfterEnv: ['<rootDir>/jest.setup.js'],
	transformIgnorePatterns: [
		'node_modules/(?!(vue|@vue)/)'
	],
	testEnvironmentOptions: {
		customExportConditions: ['node', 'node-addons']
	},
	globals: {
		Vue: true
	}
};
