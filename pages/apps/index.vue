<template>
	<div class="apps-page">
		<div class="background-pattern"></div>
		<div class="apps-container">
			<header class="apps-header">
				<button class="theme-toggle" @click="toggleTheme" :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
					<div class="toggle-track">
						<div class="toggle-slider" :class="{ 'is-dark': isDark }">
							<span class="toggle-icon">{{ isDark ? '☀️' : '🌙' }}</span>
						</div>
					</div>
				</button>
				<NuxtLink to="/" class="back-link">← Back to Home</NuxtLink>
				<h1 class="apps-title">Apps</h1>
				<p class="apps-subtitle">Mini-tools and side projects built in the browser.</p>
			</header>

			<div class="apps-grid">
				<AppCard
					v-for="(app, index) in apps"
					:key="app.to"
					:title="app.title"
					:description="app.description"
					:tags="app.tags"
					:to="app.to"
					:style="{ animationDelay: `${index * 0.1}s` }"
				/>
			</div>
		</div>
	</div>
</template>

<script setup>
const { isDark, toggleTheme } = useTheme();

const apps = [
	{
		title: 'Not Following Back',
		description: 'Find out who you follow on Instagram that is not following you back. Upload your exported data — everything runs locally in your browser.',
		tags: ['Instagram', 'Privacy', 'Tool'],
		to: '/not-following-back/'
	}
];

useHead({
	title: 'Apps - James Daniel Enovero',
	meta: [
		{ name: 'description', content: 'Browser-based mini-tools and side projects by James Daniel Enovero.' }
	]
});
</script>

<style scoped>
.apps-page {
	min-height: 100vh;
	position: relative;
	background-color: var(--bg-color);
}

.background-pattern {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAIAAACRXR/mAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAABnSURBVHja7M5RDYAwDEXRDgmvEocnlrQS2SwUFST9uEfBGWs9c97nbGtDcquqiKhOImLs/UpuzVzWEi1atGjRokWLFi1atGjRokWLFi1atGjRokWLFi1af7Ukz8xWp8z8AAAA//8DAJ4LoEAAlL1nAAAAAElFTkSuQmCC") repeat 0 0;
	filter: invert(var(--pattern-invert));
	z-index: 0;
	pointer-events: none;
}

.apps-container {
	position: relative;
	z-index: 1;
	max-width: 1200px;
	margin: 0 auto;
	padding: 40px 20px;
}

.apps-header {
	text-align: left;
	background: var(--header-bg);
	margin-bottom: 30px;
	padding: 50px 40px;
	border-radius: 15px;
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
	position: relative;
	animation: fadeInDown 0.6s ease-out;
}

@keyframes fadeInDown {
	from {
		opacity: 0;
		transform: translateY(-20px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

.back-link {
	display: inline-block;
	font: normal 14px monospace;
	color: var(--header-text);
	margin-bottom: 25px;
	opacity: 0.8;
	transition: all 0.3s;
	text-decoration: none;
}

.back-link:hover {
	opacity: 1;
	transform: translateX(-5px);
}

.apps-title {
	font: bold 48px monospace;
	color: var(--header-text);
	margin-bottom: 15px;
	letter-spacing: -1px;
}

.apps-subtitle {
	font: normal 18px monospace;
	color: var(--header-text);
	opacity: 0.7;
}

.apps-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
	gap: 30px;
	animation: fadeIn 0.6s ease-out 0.2s both;
}

@keyframes fadeIn {
	from { opacity: 0; }
	to { opacity: 1; }
}

.theme-toggle {
	position: absolute;
	top: 20px;
	right: 20px;
	background: none;
	border: none;
	cursor: pointer;
	padding: 0;
	width: 80px;
	height: 40px;
	display: flex;
	align-items: center;
	justify-content: center;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	z-index: 10;
}

.theme-toggle:hover {
	transform: scale(1.05);
}

.theme-toggle:active {
	transform: scale(0.95);
}

.toggle-track {
	width: 100%;
	height: 100%;
	background: var(--toggle-bg);
	border-radius: 20px;
	display: flex;
	align-items: center;
	justify-content: flex-start;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	box-shadow: 0 2px 5px var(--toggle-shadow);
	position: relative;
	overflow: hidden;
	padding: 3px;
}

.toggle-slider {
	width: 34px;
	height: 34px;
	background: var(--toggle-slider-bg);
	border-radius: 50%;
	display: flex;
	align-items: center;
	justify-content: center;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	transform: translateX(0);
	box-shadow: 0 2px 4px var(--toggle-shadow);
}

.toggle-slider.is-dark {
	transform: translateX(40px);
}

.toggle-icon {
	font-size: 20px;
	line-height: 1;
	display: flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
}

@media only screen and (max-width: 768px) {
	.apps-container {
		padding: 20px 15px;
	}

	.apps-header {
		margin-bottom: 20px;
		padding: 35px 25px;
	}

	.apps-title {
		font-size: 32px;
	}

	.apps-subtitle {
		font-size: 15px;
	}

	.apps-grid {
		grid-template-columns: 1fr;
		gap: 20px;
	}
}
</style>
