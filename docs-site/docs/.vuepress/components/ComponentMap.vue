<script lang="ts">
import data from './all-components.json';
import { uniq, reduce } from 'lodash';

console.log(data);

export default {
	props: {},
	data() {
		return this.processData();
	},
	computed: {},
	methods: {
		processData() {
			return {
				traits: uniq(data.map((item) => item.traits).flat()),
				groups: {
					Renderable: this.buildTree(data, 'Renderable')
				}
			}
		},
		buildTree(data, fromItem) {
			const children = data.filter(item => item.extends === fromItem);
			return reduce(children, (acc, item) => {
				acc[item.name] = this.buildTree(data, item.name);
				return acc;
			}, {});
		},
	}
};
</script>

<template>
	<div class="component-map">
		<table>
			<thead>
			<tr>
				<th rowspan="2" scope="col">Components</th>
				<th :colspan="traits.length">Traits</th>
			</tr>
			<tr>
				<th v-for="trait in traits" :key="trait">{{ trait }}</th>
			</tr>
			</thead>
			<tbody v-for="(group, key) in groups">

			</tbody>
		</table>
	</div>
</template>

<style scoped lang="scss">
.component-map {
	max-width: 100%;
	overflow-x: auto;
}
</style>
