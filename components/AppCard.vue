<template>
	<NuxtLink :to="to" class="app-card">
		<article>
			<div class="card-content">
				<h2 class="card-title">{{ title }}</h2>
				<p class="card-description">{{ description }}</p>
				<div class="card-footer">
					<div class="tags" v-if="tags && tags.length">
						<span v-for="tag in tags.slice(0, 3)" :key="tag" class="tag">{{ tag }}</span>
					</div>
					<span class="open-app">Open app →</span>
				</div>
			</div>
		</article>
	</NuxtLink>
</template>

<script setup>
defineProps({
	title: {
		type: String,
		required: true
	},
	description: {
		type: String,
		required: true
	},
	tags: {
		type: Array,
		default: () => []
	},
	to: {
		type: String,
		required: true
	}
});
</script>

<style scoped>
.app-card {
	display: block;
	background: var(--header-bg);
	border-radius: 12px;
	box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
	transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
	text-decoration: none;
	overflow: hidden;
	animation: fadeInUp 0.5s ease-out both;
	position: relative;
	border: 2px solid transparent;
}

.app-card::before {
	content: '';
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	border-radius: 12px;
	padding: 2px;
	background: linear-gradient(135deg, rgba(100, 100, 255, 0), rgba(100, 100, 255, 0));
	-webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
	-webkit-mask-composite: xor;
	mask-composite: exclude;
	opacity: 0;
	transition: opacity 0.4s ease;
	pointer-events: none;
}

@keyframes fadeInUp {
	from {
		opacity: 0;
		transform: translateY(20px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

.app-card:hover {
	transform: translateY(-12px) scale(1.02);
	box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
	border-color: rgba(100, 100, 255, 0.3);
}

.app-card:hover::before {
	background: linear-gradient(135deg, rgba(100, 100, 255, 0.5), rgba(200, 100, 255, 0.5));
	opacity: 1;
}

.card-content {
	padding: 32px 24px;
	text-align: left;
	transition: background-color 0.3s ease;
}

.app-card:hover .card-content {
	background: linear-gradient(135deg, rgba(100, 100, 255, 0.03), rgba(200, 100, 255, 0.03));
}

.card-title {
	font: bold 22px/1.3 monospace;
	color: var(--header-text);
	margin-bottom: 12px;
	text-align: left;
	transition: all 0.3s ease;
}

.app-card:hover .card-title {
	transform: translateX(4px);
	text-shadow: 0 0 20px rgba(100, 100, 255, 0.3);
}

.card-description {
	font: normal 14px/1.6 monospace;
	color: var(--header-text);
	opacity: 0.8;
	margin-bottom: 20px;
	display: -webkit-box;
	-webkit-line-clamp: 3;
	-webkit-box-orient: vertical;
	overflow: hidden;
	text-align: left;
	transition: opacity 0.3s ease;
}

.app-card:hover .card-description {
	opacity: 1;
}

.card-footer {
	display: flex;
	flex-wrap: wrap;
	flex-direction: column;
	align-items: flex-start;
	justify-content: flex-start;
	gap: 12px;
}

.tags {
	display: flex;
	gap: 6px;
	flex-wrap: wrap;
}

.tag {
	font: normal 10px monospace;
	background: var(--toggle-bg);
	color: var(--toggle-slider-bg);
	padding: 4px 10px;
	border-radius: 4px;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	transition: all 0.3s ease;
}

.app-card:hover .tag {
	transform: translateY(-2px);
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.open-app {
	font: bold 13px monospace;
	color: var(--header-text);
	opacity: 0.9;
	transition: all 0.3s ease;
	white-space: nowrap;
	display: inline-block;
}

.app-card:hover .open-app {
	opacity: 1;
	transform: translateX(4px);
}
</style>
