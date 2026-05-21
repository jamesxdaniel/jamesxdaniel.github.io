<template>
	<div class="user-list-container panel-card animate-fade-in">
		<div class="header">
			<div>
				<h2 class="results-title">Results</h2>
				<p class="subtitle">{{ users.length }} accounts are not following you back</p>
			</div>
			<div class="actions">
				<input
					type="text"
					v-model="searchQuery"
					placeholder="Search username..."
					class="search-input"
				/>
				<select v-model="sortOrder" class="sort-select">
					<option value="newest">Newest First</option>
					<option value="oldest">Oldest First</option>
				</select>
				<button @click="downloadCSV" class="btn btn-primary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
					Download CSV
				</button>
			</div>
		</div>

		<div v-if="filteredUsers.length === 0" class="empty-state">
			<p>No users found matching "{{ searchQuery }}"</p>
		</div>

		<div v-else class="users-grid">
			<a
				v-for="user in filteredUsers"
				:key="user.username"
				:href="user.url"
				target="_blank"
				rel="noopener noreferrer"
				class="user-card"
			>
				<div class="avatar-placeholder">
					{{ user.username.charAt(0).toUpperCase() }}
				</div>
				<div class="user-info">
					<span class="username">{{ user.username }}</span>
					<span class="view-profile">View Profile <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg></span>
				</div>
			</a>
		</div>
	</div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
	users: {
		type: Array,
		required: true
	}
});

const searchQuery = ref('');
const sortOrder = ref('newest');

const filteredUsers = computed(() => {
	let result = props.users;
	if (searchQuery.value) {
		const query = searchQuery.value.toLowerCase();
		result = result.filter(user => user.username.toLowerCase().includes(query));
	}

	return result.slice().sort((a, b) => {
		if (sortOrder.value === 'newest') {
			return b.timestamp - a.timestamp;
		}
		return a.timestamp - b.timestamp;
	});
});

const downloadCSV = () => {
	if (props.users.length === 0) return;

	const headers = ['Username', 'Profile URL'];
	const csvContent = [
		headers.join(','),
		...props.users.map(u => `${u.username},${u.url}`)
	].join('\n');

	const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
	const url = URL.createObjectURL(blob);

	const link = document.createElement('a');
	link.setAttribute('href', url);
	link.setAttribute('download', 'not_following_back.csv');
	link.style.visibility = 'hidden';
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
};
</script>

<style scoped>
.user-list-container {
	display: flex;
	flex-direction: column;
	gap: 2rem;
}

.panel-card {
	background: var(--panel-bg);
	border-radius: 12px;
	padding: 2rem;
	box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
	border: 1px solid var(--border-color);
}

.header {
	display: flex;
	justify-content: space-between;
	align-items: flex-end;
	flex-wrap: wrap;
	gap: 1rem;
	border-bottom: 1px solid var(--border-color);
	padding-bottom: 1.5rem;
}

.results-title {
	font: bold 1.5rem monospace;
	color: var(--header-text);
}

.subtitle {
	font: normal 0.95rem monospace;
	color: var(--header-text);
	opacity: 0.88;
	margin-top: 0.5rem;
}

.actions {
	display: flex;
	gap: 1rem;
	flex-wrap: wrap;
}

.search-input {
	background: var(--input-bg);
	border: 2px solid var(--border-color);
	color: var(--header-text);
	padding: 0.75rem 1rem;
	border-radius: 8px;
	font: normal 0.95rem monospace;
	min-width: 250px;
	outline: none;
	transition: border-color 0.3s;
}

.search-input::placeholder {
	color: var(--placeholder-color);
	opacity: 1;
}

.search-input:focus {
	border-color: var(--header-text);
	box-shadow: 0 0 0 3px var(--toggle-shadow);
}

.sort-select {
	appearance: none;
	background-color: var(--input-bg);
	background-image: var(--select-chevron);
	background-repeat: no-repeat;
	background-position: right 1rem top 50%;
	background-size: 0.65rem auto;
	border: 2px solid var(--border-color);
	color: var(--header-text);
	padding: 0.75rem 2.5rem 0.75rem 1rem;
	border-radius: 8px;
	font: normal 0.95rem monospace;
	outline: none;
	transition: all 0.3s ease;
	cursor: pointer;
}

.sort-select:focus {
	border-color: var(--header-text);
	box-shadow: 0 0 0 3px var(--toggle-shadow);
}

.sort-select option {
	background: var(--header-bg);
	color: var(--header-text);
}

.btn {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 0.5rem;
	padding: 0.75rem 1.5rem;
	border-radius: 8px;
	font: bold 0.95rem monospace;
	cursor: pointer;
	transition: all 0.3s ease;
	border: none;
	outline: none;
}

.btn-primary {
	color: var(--toggle-slider-bg);
	background: var(--toggle-bg);
}

.btn-primary:hover {
	transform: translateY(-2px);
	box-shadow: 0 4px 12px var(--toggle-shadow);
}

.users-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
	gap: 1rem;
	max-height: 60vh;
	overflow-y: auto;
	padding-right: 0.5rem;
}

.user-card {
	display: flex;
	align-items: center;
	gap: 1rem;
	padding: 1rem;
	background: var(--card-bg);
	border: 1px solid var(--border-color);
	border-radius: 12px;
	text-decoration: none;
	transition: all 0.3s ease;
}

.user-card:hover {
	background: var(--surface-hover);
	border-color: var(--header-text);
	transform: translateY(-2px);
	box-shadow: 0 4px 12px var(--toggle-shadow);
}

.avatar-placeholder {
	width: 40px;
	height: 40px;
	border-radius: 50%;
	background: var(--toggle-bg);
	display: flex;
	align-items: center;
	justify-content: center;
	font: bold 1.2rem monospace;
	color: var(--toggle-slider-bg);
	flex-shrink: 0;
}

.user-info {
	display: flex;
	flex-direction: column;
	overflow: hidden;
}

.username {
	font: normal 500 0.95rem monospace;
	color: var(--header-text);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.view-profile {
	font: normal 0.8rem monospace;
	color: var(--header-text);
	opacity: 0.75;
	display: flex;
	align-items: center;
	gap: 0.25rem;
	margin-top: 0.25rem;
	transition: opacity 0.3s;
}

.user-card:hover .view-profile {
	opacity: 1;
}

.empty-state {
	text-align: center;
	padding: 3rem;
	font: normal 0.95rem monospace;
	color: var(--header-text);
}

@keyframes fadeIn {
	from { opacity: 0; transform: translateY(10px); }
	to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
	animation: fadeIn 0.4s ease forwards;
}
</style>
