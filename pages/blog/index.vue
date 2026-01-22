<template>
	<div class="blog-page">
		<div class="background-pattern"></div>
		<div class="blog-container">
			<header class="blog-header">
				<button class="theme-toggle" @click="toggleTheme" :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
					<div class="toggle-track">
						<div class="toggle-slider" :class="{ 'is-dark': isDark }">
							<span class="toggle-icon">{{ isDark ? '☀️' : '🌙' }}</span>
						</div>
					</div>
				</button>
				<NuxtLink to="/" class="back-link">← Back to Home</NuxtLink>
				<h1 class="blog-title">Blog</h1>
				<p class="blog-subtitle">Tech tutorials, tips, and insights</p>
			</header>

			<div class="posts-grid" v-if="posts && posts.length">
				<BlogCard v-for="post in posts" :key="post._path" :post="post" />
			</div>

			<div class="no-posts" v-else>
				<p>No posts yet. Check back soon!</p>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const isDark = ref(false);

onMounted(() => {
	const savedTheme = localStorage.getItem('theme');
	if (savedTheme) {
		isDark.value = savedTheme === 'dark';
		document.documentElement.setAttribute('data-theme', savedTheme);
	} else {
		isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
		document.documentElement.setAttribute('data-theme', isDark.value ? 'dark' : 'light');
	}
});

function toggleTheme() {
	isDark.value = !isDark.value;
	const theme = isDark.value ? 'dark' : 'light';
	document.documentElement.setAttribute('data-theme', theme);
	localStorage.setItem('theme', theme);
}

const { data: posts } = await useAsyncData('blog-posts', () =>
	queryContent('/blog')
		.sort({ date: -1 })
		.find()
);

useHead({
	title: 'Blog - James Daniel Enovero',
	meta: [
		{ name: 'description', content: 'Tech tutorials, tips, and insights from James Daniel Enovero' }
	]
});
</script>

<style>
:root[data-theme="light"] {
	--bg-color: #ffffff;
	--text-color: #333333;
	--header-bg: #ffffff;
	--header-text: #333333;
	--border-color: #e5e5e5;
	--pattern-invert: 1;
	--toggle-bg: #333333;
	--toggle-slider-bg: #ffffff;
	--toggle-shadow: rgba(0, 0, 0, 0.2);
	--toggle-hover-shadow: rgba(0, 0, 0, 0.3);
}

:root[data-theme="dark"] {
	--bg-color: #333333;
	--text-color: #ffffff;
	--header-bg: #333333;
	--header-text: #ffffff;
	--border-color: #e5e5e5;
	--pattern-invert: 0;
	--toggle-bg: #ffffff;
	--toggle-slider-bg: #333333;
	--toggle-shadow: rgba(255, 255, 255, 0.1);
	--toggle-hover-shadow: rgba(255, 255, 255, 0.2);
}
</style>

<style scoped>
.blog-page {
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

.blog-container {
	position: relative;
	z-index: 1;
	max-width: 900px;
	margin: 0 auto;
	padding: 40px 20px;
}

.blog-header {
	text-align: center;
	margin-bottom: 50px;
	background: var(--header-bg);
	padding: 40px 30px;
	border-radius: 10px;
	box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
	position: relative;
}

.back-link {
	display: inline-block;
	font: normal 14px monospace;
	color: var(--header-text);
	margin-bottom: 20px;
	opacity: 0.8;
	transition: opacity 0.3s;
}

.back-link:hover {
	opacity: 1;
}

.blog-title {
	font: bold 36px monospace;
	color: var(--header-text);
	margin-bottom: 10px;
}

.blog-subtitle {
	font: normal 16px monospace;
	color: var(--header-text);
	opacity: 0.7;
}

.posts-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
	gap: 24px;
}

.no-posts {
	text-align: center;
	padding: 60px 20px;
	background: var(--header-bg);
	border-radius: 10px;
	box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.no-posts p {
	font: normal 16px monospace;
	color: var(--header-text);
	opacity: 0.7;
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
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	display: flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
}

@media only screen and (max-width: 768px) {
	.blog-container {
		padding: 20px 15px;
	}

	.blog-header {
		padding: 30px 20px;
	}

	.blog-title {
		font-size: 28px;
	}

	.posts-grid {
		grid-template-columns: 1fr;
	}
}
</style>
