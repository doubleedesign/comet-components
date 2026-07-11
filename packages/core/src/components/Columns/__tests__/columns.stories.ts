import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, ThemeColor, ContainerSize } from '../../../../test/storybook-helpers.ts';
import { ColumnsProps } from '../../../../dist/types';

type ColumnsDemoProps = ColumnsProps & {
	innerComponents?: Array<{
		attributes: any[];
		content: string;
	}>;
	_actualQty?: number;
}

type ColumnsStoryProps = ColumnsDemoProps & {
	count: number;
	innerBackgrounds: boolean;
}

const meta = {
	title: 'Layout/Columns',
	tags: ['wordpress-block', 'autodocs'],
	...(await createStoryBase('Columns')),
	args: {
		qty: 3,
		columnLayout: 'even',
	},
} satisfies Meta<ColumnsStoryProps>;

export default meta;
type Story = StoryObj<ColumnsStoryProps>;

export const Playground: Story = {
	tags: ['!dev']
};

export const Two_In_Two: Story = {
	name: '2/2',
	tags: ['!autodocs'],
	args: {
		columnLayout: 'even',
		qty: 2,
		_actualQty: 2,
	},
	parameters: {
		controls: {
			include: ['size']
		}
	}
};

export const Two_In_Three: Story = {
	name: '2/3',
	tags: ['!autodocs'],
	args: {
		columnLayout: 'even',
		qty: 3,
		_actualQty: 2,
	},
	parameters: {
		controls: {
			include: ['size', 'columnLayout', 'hAlign']
		}
	}
};

export const Two_In_Four: Story = {
	name: '2/4',
	tags: ['!autodocs'],
	args: {
		columnLayout: 'even',
		qty: 4,
		_actualQty: 2,
	},
	parameters: {
		controls: {
			include: ['size', 'columnLayout', 'hAlign']
		}
	}
};

export const Three_In_Three: Story = {
	name: '3/3',
	tags: ['!autodocs'],
	args: {
		columnLayout: 'even',
		qty: 3,
		_actualQty: 3,
	},
	parameters: {
		controls: {
			include: ['size']
		}
	}
};

export const Three_In_Four: Story = {
	name: '3/4',
	tags: ['!autodocs'],
	args: {
		columnLayout: 'even',
		qty: 4,
		_actualQty: 3,
	},
	parameters: {
		controls: {
			include: ['size', 'columnLayout', 'hAlign']
		}
	}
};

export const Four_In_Four: Story = {
	name: '4/4',
	tags: ['!autodocs'],
	args: {
		columnLayout: 'even',
		qty: 4,
		_actualQty: 4,
	},
	parameters: {
		controls: {
			include: ['size']
		}
	}
};

export const Three_In_Five: Story = {
	name: '3/5',
	tags: ['!autodocs'],
	args: {
		columnLayout: 'even',
		qty: 5,
		_actualQty: 3,
	},
	parameters: {
		controls: {
			include: ['size', 'columnLayout', 'hAlign']
		}
	}
};


export const Three_In_Six: Story = {
	name: '3/6',
	tags: ['!autodocs'],
	args: {
		columnLayout: 'even',
		qty: 6,
		_actualQty: 3,
	},
	parameters: {
		controls: {
			include: ['size', 'columnLayout', 'hAlign']
		}
	}
};

export const Four_In_Six: Story = {
	name: '4/6',
	tags: ['!autodocs'],
	args: {
		columnLayout: 'even',
		qty: 6,
		_actualQty: 4,
	},
	parameters: {
		controls: {
			include: ['size', 'columnLayout', 'hAlign']
		}
	}
};


export const Two_In_Three_Even_Centered: Story = {
	name: '2/3 - even centered',
	tags: ['autodocs', '!dev'],
	args: {
		columnLayout: 'even',
		qty: 3,
		_actualQty: 2,
		hAlign: 'center',
	},
	parameters: {
		controls: {
			include: []
		}
	}
}

export const Two_In_Three_Expand_First: Story = {
	name: '2/3 - expand first',
	tags: ['autodocs', '!dev'],
	args: {
		columnLayout: 'expand-first',
		qty: 3,
		_actualQty: 2,
	},
	parameters: {
		controls: {
			include: []
		}
	}
}

export const Two_In_Three_Expand_Last: Story = {
	name: '2/3 - expand last',
	tags: ['autodocs', '!dev'],
	args: {
		columnLayout: 'expand-last',
		qty: 3,
		_actualQty: 2,
	},
	parameters: {
		controls: {
			include: []
		}
	}
}

export const Three_In_Four_Expand_Middle: Story = {
	name: '3/4 - expand middle',
	tags: ['autodocs', '!dev'],
	args: {
		columnLayout: 'expand-middle',
		qty: 4,
		_actualQty: 3,
	},
	parameters: {
		controls: {
			include: []
		}
	}
}
