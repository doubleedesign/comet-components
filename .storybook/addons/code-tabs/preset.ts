module.exports = {
	managerEntries: (entry = []) => [...entry, require.resolve('./register')]
};
