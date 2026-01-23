<template>
	<NuxtLink :to="post._path" class="blog-card">
		<article>
			<div class="card-image" v-if="post.image">
				<img :src="post.image" :alt="post.title" loading="lazy">
				<div class="card-overlay"></div>
			</div>
			<div class="card-content">
				<div class="card-meta">
					<time :datetime="post.date">{{ formatDate(post.date) }}</time>
					<span class="reading-time" v-if="readingTime">{{ readingTime }} min read</span>
				</div>
				<h2 class="card-title">{{ post.title }}</h2>
				<p class="card-description">{{ post.description }}</p>
				<div class="card-footer">
					<div class="tags" v-if="post.tags && post.tags.length">
						<span v-for="tag in post.tags.slice(0, 3)" :key="tag" class="tag">{{ tag }}</span>
					</div>
					<span class="read-more">Read more →</span>
				</div>
			</div>
		</article>
	</NuxtLink>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
	post: {
		type: Object,
		required: true
	}
});

function formatDate(dateString) {
	if (!dateString) return '';
	const date = new Date(dateString);
	return date.toLocaleDateString('en-US', {
		year: 'numeric',
		month: 'long',
		day: 'numeric'
	});
}

const readingTime = computed(() => {
	if (!props.post.description) return 3;
	const wordsPerMinute = 200;
	const words = props.post.description.split(/\s+/).length;
	return Math.max(1, Math.ceil(words / wordsPerMinute)) + 2;
});
</script>

<style scoped>
.blog-card {
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

.blog-card::before {
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

.blog-card:hover {
	transform: translateY(-12px) scale(1.02);
	box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
	border-color: rgba(100, 100, 255, 0.3);
}

.blog-card:hover::before {
	background: linear-gradient(135deg, rgba(100, 100, 255, 0.5), rgba(200, 100, 255, 0.5));
	opacity: 1;
}

.card-image {
	position: relative;
	width: 100%;
	height: 200px;
	overflow: hidden;
	background: var(--bg-color);
}

.card-image img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform 0.3s ease;
}

.blog-card:hover .card-image img {
	transform: scale(1.05);
}

.card-overlay {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.3));
}

.card-content {
	padding: 24px;
	text-align: left;
	transition: background-color 0.3s ease;
}

.blog-card:hover .card-content {
	background: linear-gradient(135deg, rgba(100, 100, 255, 0.03), rgba(200, 100, 255, 0.03));
}

.card-meta {
	display: flex;
	align-items: center;
	gap: 12px;
	margin-bottom: 16px;
	flex-wrap: wrap;
}

.card-meta time {
	font: normal 12px monospace;
	color: var(--header-text);
	opacity: 0.6;
	transition: all 0.3s ease;
}

.reading-time {
	font: normal 12px monospace;
	color: var(--header-text);
	opacity: 0.6;
	transition: all 0.3s ease;
}

.blog-card:hover .card-meta time,
.blog-card:hover .reading-time {
	opacity: 0.9;
	transform: translateY(-1px);
}

.reading-time::before {
	content: '•';
	margin-right: 8px;
}

.card-title {
	font: bold 20px/1.3 monospace;
	color: var(--header-text);
	margin-bottom: 12px;
	text-align: left;
	transition: all 0.3s ease;
}

.blog-card:hover .card-title {
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

.blog-card:hover .card-description {
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

.blog-card:hover .tag {
	transform: translateY(-2px);
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.read-more {
	font: bold 13px monospace;
	color: var(--header-text);
	opacity: 0.9;
	transition: all 0.3s ease;
	white-space: nowrap;
	display: inline-block;
}

.blog-card:hover .read-more {
	opacity: 1;
	transform: translateX(4px);
}
</style>
