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
				<p class="blog-subtitle">Tech tutorials, tips, and gaming!</p>
				
				<div class="search-section" v-if="!pending && posts.length > 0">
					<input 
						type="text" 
						v-model="searchQuery" 
						placeholder="Search articles..."
						class="search-input"
					/>
					<div class="filter-tags" v-if="allTags.length">
						<button 
							@click="selectedTag = null" 
							:class="['tag-filter', { active: selectedTag === null }]"
						>
							All
						</button>
						<button 
							v-for="tag in allTags" 
							:key="tag"
							@click="selectedTag = tag"
							:class="['tag-filter', { active: selectedTag === tag }]"
						>
							{{ tag }}
						</button>
					</div>
				</div>
			</header>

			<ClientOnly>
				<template #fallback>
					<div class="loading">
						<p>Loading posts...</p>
					</div>
				</template>
				
				<div v-if="pending" class="loading">
					<p>Loading posts...</p>
				</div>

				<div v-else-if="posts.length > 0" class="posts-section">
									
				<div v-if="filteredPosts.length > 0" class="posts-grid">
					<BlogCard 
						v-for="(post, index) in filteredPosts" 
						:key="post._path" 
						:post="post"
						:style="{ animationDelay: `${index * 0.1}s` }"
					/>
				</div>

				<div v-else class="no-results">
					<p>No articles match your search.</p>
					<button @click="clearFilters" class="clear-button">Clear filters</button>
				</div>
			</div>

				<div v-else class="no-posts">
					<p>No posts yet. Check back soon!</p>
				</div>
			</ClientOnly>
		</div>
	</div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const isDark = ref(false);
const searchQuery = ref('');
const selectedTag = ref(null);

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

function clearFilters() {
	searchQuery.value = '';
	selectedTag.value = null;
}

// Load posts on client side only to avoid hydration issues
const posts = ref([]);
const pending = ref(true);

onMounted(async () => {
	pending.value = true;
	
	// Try JSON file first (most reliable)
	try {
		const jsonData = await $fetch('/blog-posts.json');
		posts.value = jsonData;
		console.log('Posts loaded from JSON:', jsonData.length);
		pending.value = false;
		return;
	} catch (jsonError) {
		console.log('JSON failed, trying queryContent:', jsonError);
	}
	
	// Fallback to queryContent
	try {
		const contentData = await queryContent('blog').sort({ date: -1 }).find();
		if (contentData && contentData.length > 0) {
			posts.value = contentData;
			console.log('Posts loaded from queryContent:', contentData.length);
		}
	} catch (error) {
		console.error('queryContent also failed:', error);
	}
	
	pending.value = false;
});

const allTags = computed(() => {
	const postList = posts.value;
	if (!postList || !Array.isArray(postList)) return [];
	const tags = new Set();
	postList.forEach(post => {
		if (post.tags) {
			post.tags.forEach(tag => tags.add(tag));
		}
	});
	return Array.from(tags).sort();
});

const filteredPosts = computed(() => {
	const postList = posts.value;
	if (!postList || !Array.isArray(postList)) return [];
	
	let filtered = postList;
	
	// Filter by search query
	if (searchQuery.value) {
		const query = searchQuery.value.toLowerCase();
		filtered = filtered.filter(post => 
			post.title?.toLowerCase().includes(query) ||
			post.description?.toLowerCase().includes(query) ||
			post.tags?.some(tag => tag.toLowerCase().includes(query))
		);
	}
	
	// Filter by selected tag
	if (selectedTag.value) {
		filtered = filtered.filter(post => 
			post.tags?.includes(selectedTag.value)
		);
	}
	
	return filtered;
});

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
	max-width: 1200px;
	margin: 0 auto;
	padding: 40px 20px;
}

.blog-header {
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

.blog-title {
	font: bold 48px monospace;
	color: var(--header-text);
	margin-bottom: 15px;
	letter-spacing: -1px;
}

.blog-subtitle {
	font: normal 18px monospace;
	color: var(--header-text);
	opacity: 0.7;
}

.search-section {
	margin-top: 35px;
}

.search-input {
	width: 100%;
	padding: 14px 20px;
	font: normal 15px monospace;
	color: var(--header-text);
	background: var(--bg-color);
	border: 2px solid var(--border-color);
	border-radius: 10px;
	outline: none;
	transition: all 0.3s;
}

.search-input:focus {
	border-color: var(--toggle-bg);
	box-shadow: 0 0 0 3px var(--toggle-shadow);
}

.search-input::placeholder {
	opacity: 0.5;
}

.filter-tags {
	display: flex;
	gap: 10px;
	flex-wrap: wrap;
	justify-content: flex-start;
	margin-top: 20px;
}

.tag-filter {
	font: normal 12px monospace;
	background: var(--bg-color);
	color: var(--header-text);
	border: 2px solid var(--border-color);
	padding: 8px 16px;
	border-radius: 20px;
	cursor: pointer;
	transition: all 0.3s;
	text-transform: uppercase;
	letter-spacing: 0.5px;
}

.tag-filter:hover {
	border-color: var(--toggle-bg);
	transform: translateY(-2px);
}

.tag-filter.active {
	background: var(--toggle-bg);
	color: var(--toggle-slider-bg);
	border-color: var(--toggle-bg);
}

.posts-section {
	animation: fadeIn 0.6s ease-out 0.2s both;
}

@keyframes fadeIn {
	from {
		opacity: 0;
	}
	to {
		opacity: 1;
	}
}

.posts-count {
	margin-bottom: 25px;
	text-align: left;
}

.posts-count p {
	font: normal 14px monospace;
	color: var(--header-text);
	opacity: 0.7;
	text-transform: uppercase;
	letter-spacing: 1px;
}

.posts-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
	gap: 30px;
}

.no-posts,
.no-results,
.loading {
	text-align: center;
	padding: 80px 20px;
	background: var(--header-bg);
	border-radius: 15px;
	box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.no-posts p,
.no-results p,
.loading p {
	font: normal 18px monospace;
	color: var(--header-text);
	opacity: 0.7;
}

.loading p {
	animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
	0%, 100% {
		opacity: 0.4;
	}
	50% {
		opacity: 0.9;
	}
}

.clear-button {
	font: bold 14px monospace;
	color: var(--toggle-slider-bg);
	background: var(--toggle-bg);
	padding: 12px 24px;
	border: none;
	border-radius: 8px;
	cursor: pointer;
	transition: all 0.3s;
}

.clear-button:hover {
	transform: translateY(-2px);
	box-shadow: 0 4px 12px var(--toggle-shadow);
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
		margin-bottom: 20px;
		padding: 35px 25px;
	}

	.blog-title {
		font-size: 32px;
	}

	.blog-subtitle {
		font-size: 15px;
	}

	.search-input {
		font-size: 14px;
		padding: 12px 16px;
	}

	.filter-tags {
		gap: 8px;
	}

	.tag-filter {
		font-size: 11px;
		padding: 6px 12px;
	}

	.posts-grid {
		grid-template-columns: 1fr;
		gap: 20px;
	}

	.no-posts,
	.no-results,
	.loading {
		padding: 60px 20px;
	}
}
</style>
