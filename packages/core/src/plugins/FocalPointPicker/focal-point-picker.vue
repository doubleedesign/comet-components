<script lang="ts">
/// <reference lib="dom" />

export default {
	name: 'FocalPointPicker',
	props: {
		content: {
			type: String
		}
	},
	methods: {
		handleChange(name: string, value: any) {
			console.log(({ name, value }));
			this.$emit('change', { name, value });
		}
	}
};
</script>

<template>
	<div class="focal-point-picker-app">
		<div class="focal-point-picker">
			<!-- The rendered HTML passed into the Vue component -->
			<div class="focal-point-picker__image" v-html="content"></div>
			<!-- Focal point picker UI -->
			<div class="focal-point-picker__controls">
				<vueform>
					<select-element
						name="aspect-ratio"
						label="Aspect ratio"
						:native="false"
						:default="'4:3'"
						:items="[
						    { value: '4:3', label: '4:3 (Standard)'},
							{ value: '3:4', label: '3:4 (Portrait)'},
							{ value: '1:1', label: '1:1 (Square)'},
							{ value: '16:9', label: '16:9 (Widescreen)'},
							{ value: '9:16', label: '9:16 (Tall)'},
							{ value: '3:2', label: '3:2 (Classic)'},
							{ value: '2:3', label: '2:3 (Classic portrait)'},
							{ value: '21:9', label: '21:9 (Cinematic)'},
							{ value: '2.35:1', label: '2.35:1 (Cinemascope)'},
						]"
						@change="this.handleChange('aspect-ratio', $event)"
					></select-element>
					<group-element name="focalPoint" label="Focal point">
						<text-element
							input-type="number"
							name="focalPoint.x"
							placeholder="X"
							default="50"
							@change="this.handleChange('focalPoint.x', $event)"
						/>
						<text-element
							input-type="number"
							name="focalPoint.y"
							placeholder="Y"
							default="50"
							@change="this.handleChange('focalPoint.y', $event)"
						/>
					</group-element>
					<group-element name="offset" label="Offset">
						<text-element
							input-type="number"
							name="offset.x"
							placeholder="X"
							default="0"
							@change="this.handleChange('focalPoint.x', $event)"
							readonly
						/>
						<text-element
							input-type="number"
							name="offset.y"
							placeholder="Y"
							default="0"
							@change="this.handleChange('focalPoint.y', $event)"
							readonly
						/>
					</group-element>
				</vueform>
			</div>
		</div>
	</div>
</template>

<style scoped>
.focal-point-picker-app {
	container-type: size;
	width: 100dvw;
	height: 100dvh;
	overflow: hidden;
}

.focal-point-picker {
	width: 100cqw;
	height: 100cqh;
	display: grid;
	grid-template-columns: 3fr 1fr;

	.focal-point-picker__image {
		height: 100%;
	}

	.focal-point-picker__controls {
		border: 1px solid #CCC;
		padding: 1rem;
		box-sizing: border-box;
	}
}
</style>
