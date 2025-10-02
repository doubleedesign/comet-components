import React from 'react';

export const withFocalPointPicker = () => (Story, context) => {
	return (
		<div style={{ maxWidth: '300px', border: '1px solid #ccc', padding: '10px' }}>
			<Story {...context} />
		</div>
	);
}
