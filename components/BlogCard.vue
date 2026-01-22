<template>
	<NuxtLink :to="post._path" class="blog-card">
		<article>
			<div class="card-content">
				<div class="card-meta">
					<time :datetime="post.date">{{ formatDate(post.date) }}</time>
					<div class="tags" v-if="post.tags && post.tags.length">
						<span v-for="tag in post.tags.slice(0, 3)" :key="tag" class="tag">{{ tag }}</span>
					</div>
				</div>
				<h2 class="card-title">{{ post.title }}</h2>
				<p class="card-description">{{ post.description }}</p>
				<span class="read-more">Read more →</span>
			</div>
		</article>
	</NuxtLink>
</template>

<script setup>
defineProps({
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
</script>

<style scoped>
.blog-card {
	display: block;
	background: var(--header-bg);
	border-radius: 10px;
	box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
	transition: transform 0.3s, box-shadow 0.3s;
	text-decoration: none;
	overflow: hidden;
}

.blog-card:hover {
	transform: translateY(-5px);
	box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.card-content {
	padding: 24px;
}

.card-meta {
	display: flex;
	align-items: center;
	gap: 12px;
	margin-bottom: 12px;
	flex-wrap: wrap;
}

.card-meta time {
	font: normal 12px monospace;
	color: var(--header-text);
	opacity: 0.7;
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
	padding: 4px 8px;
	border-radius: 4px;
	text-transform: uppercase;
}

.card-title {
	font: bold 18px/1.4 monospace;
	color: var(--header-text);
	margin-bottom: 12px;
}

.card-description {
	font: normal 14px/1.6 monospace;
	color: var(--header-text);
	opacity: 0.8;
	margin-bottom: 16px;
	display: -webkit-box;
	-webkit-line-clamp: 3;
	-webkit-box-orient: vertical;
	overflow: hidden;
}

.read-more {
	font: bold 12px monospace;
	color: var(--header-text);
	opacity: 0.9;
}

.blog-card:hover .read-more {
	opacity: 1;
}
</style>
