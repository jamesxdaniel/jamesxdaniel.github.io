<template>
	<div class="blog-post-page">
		<div class="background-pattern"></div>
		<div class="post-container">
			<header class="post-header">
				<button class="theme-toggle" @click="toggleTheme" :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
					<div class="toggle-track">
						<div class="toggle-slider" :class="{ 'is-dark': isDark }">
							<span class="toggle-icon">{{ isDark ? '☀️' : '🌙' }}</span>
						</div>
					</div>
				</button>
				<NuxtLink to="/blog/" class="back-link">← Back to Blog</NuxtLink>
			</header>

			<article class="post-content" v-if="post">
				<div class="post-meta">
					<time :datetime="post.date">{{ formatDate(post.date) }}</time>
					<div class="tags" v-if="post.tags && post.tags.length">
						<span v-for="tag in post.tags" :key="tag" class="tag">{{ tag }}</span>
					</div>
				</div>
				<h1 class="post-title">{{ post.title }}</h1>
				<p class="post-description" v-if="post.description">{{ post.description }}</p>
				<div class="prose" v-html="renderMarkdown(post.body)"></div>
			</article>

			<div class="not-found" v-else>
				<p>Post not found.</p>
				<NuxtLink to="/blog/" class="back-link">← Back to Blog</NuxtLink>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { marked } from 'marked';

const route = useRoute();
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

function formatDate(dateString) {
	if (!dateString) return '';
	const date = new Date(dateString);
	return date.toLocaleDateString('en-US', {
		year: 'numeric',
		month: 'long',
		day: 'numeric'
	});
}

// Configure marked renderer to handle external links
const renderer = new marked.Renderer();
const originalLinkRenderer = renderer.link.bind(renderer);

renderer.link = function(href, title, text) {
	const html = originalLinkRenderer(href, title, text);
	
	// Check if link is external (starts with http:// or https://)
	if (href && (href.startsWith('http://') || href.startsWith('https://'))) {
		// Add target="_blank" and rel="noopener noreferrer" for security
		return html.replace(/^<a /, '<a target="_blank" rel="noopener noreferrer" ');
	}
	
	return html;
};

// Configure marked options
marked.setOptions({
	breaks: true,
	gfm: true,
	renderer: renderer
});

// Render markdown to HTML using marked
function renderMarkdown(md) {
	if (!md) return '';
	try {
		return marked.parse(md);
	} catch (error) {
		console.error('Error rendering markdown:', error);
		return md;
	}
}

// Extract slug from route path (e.g., /blog/2026-01-22-modern-css -> 2026-01-22-modern-css)
const slug = route.path.replace('/blog/', '');

// Fetch post from our custom API
const { data: postData } = await useAsyncData(`post-${slug}`, () =>
	$fetch(`/api/blog/${slug}`)
);

// Convert to format expected by template
const post = computed(() => {
	if (!postData.value) return null;
	return {
		title: postData.value.title,
		description: postData.value.description,
		date: postData.value.date,
		tags: postData.value.tags,
		body: postData.value.body
	};
});

useHead({
	title: post.value ? `${post.value.title} - James Daniel Enovero` : 'Blog Post',
	meta: [
		{ name: 'description', content: post.value?.description || '' }
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
	--code-bg: #f5f5f5;
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
	--code-bg: #1a1a1a;
}
</style>

<style scoped>
.blog-post-page {
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

.post-container {
	position: relative;
	z-index: 1;
	max-width: 1200px;
	margin: 0 auto;
	padding: 40px 20px;
}

.post-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	min-height: 80px;
	margin-bottom: 30px;
	background: var(--header-bg);
	padding: 20px 30px;
	border-radius: 10px;
	box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
	position: relative;
}

.back-link {
	display: inline-block;
	font: normal 14px monospace;
	color: var(--header-text);
	opacity: 0.8;
	transition: opacity 0.3s;
}

.back-link:hover {
	opacity: 1;
}

.post-content {
	background: var(--header-bg);
	padding: 40px;
	border-radius: 10px;
	box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
}

.post-meta {
	display: flex;
	align-items: center;
	gap: 16px;
	margin-bottom: 20px;
	flex-wrap: wrap;
}

.post-meta time {
	font: normal 14px monospace;
	color: var(--header-text);
	opacity: 0.7;
}

.tags {
	display: flex;
	gap: 8px;
	flex-wrap: wrap;
}

.tag {
	font: normal 11px monospace;
	background: var(--toggle-bg);
	color: var(--toggle-slider-bg);
	padding: 5px 10px;
	border-radius: 4px;
	text-transform: uppercase;
}

.post-title {
	font: bold 32px/1.3 monospace;
	color: var(--header-text);
	margin-bottom: 16px;
}

.post-description {
	font: italic 16px/1.6 monospace;
	color: var(--header-text);
	opacity: 0.8;
	margin-bottom: 30px;
	padding-bottom: 30px;
	border-bottom: 1px solid var(--border-color);
}

.prose {
	font: normal 16px/1.8 monospace;
	color: var(--header-text);
}

.prose :deep(h2) {
	font: bold 24px/1.4 monospace;
	margin: 32px 0 16px;
}

.prose :deep(h3) {
	font: bold 20px/1.4 monospace;
	margin: 28px 0 14px;
}

.prose :deep(p) {
	margin-bottom: 16px;
}

.prose :deep(ul),
.prose :deep(ol) {
	margin: 16px 0;
	padding-left: 24px;
}

.prose :deep(li) {
	margin-bottom: 8px;
	list-style: disc;
}

.prose :deep(ol li) {
	list-style: decimal;
}

.prose :deep(code) {
	font-family: monospace;
	background: var(--code-bg);
	padding: 2px 6px;
	border-radius: 4px;
	font-size: 14px;
}

.prose :deep(pre) {
	background: var(--code-bg);
	padding: 20px;
	border-radius: 8px;
	overflow-x: auto;
	margin: 20px 0;
}

.prose :deep(pre code) {
	background: none;
	padding: 0;
}

.prose :deep(a) {
	color: var(--header-text);
	text-decoration: underline;
}

.prose :deep(blockquote) {
	border-left: 4px solid var(--border-color);
	padding-left: 20px;
	margin: 20px 0;
	font-style: italic;
	opacity: 0.9;
}

.prose :deep(strong) {
	font-weight: bold;
}

.not-found {
	text-align: center;
	padding: 60px 20px;
	background: var(--header-bg);
	border-radius: 10px;
	box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.not-found p {
	font: normal 18px monospace;
	color: var(--header-text);
	margin-bottom: 20px;
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
	.post-container {
		padding: 20px 15px;
	}

	.post-content {
		padding: 25px 20px;
	}

	.post-title {
		font-size: 24px;
	}

	.post-header {
		padding: 15px 20px;
	}
}
</style>
