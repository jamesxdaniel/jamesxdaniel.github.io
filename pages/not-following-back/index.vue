<template>
	<div class="nfb-page">
		<div class="background-pattern"></div>
		<div class="nfb-container">
			<header class="nfb-header">
				<button class="theme-toggle" @click="toggleTheme" :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
					<div class="toggle-track">
						<div class="toggle-slider" :class="{ 'is-dark': isDark }">
							<span class="toggle-icon">{{ isDark ? '☀️' : '🌙' }}</span>
						</div>
					</div>
				</button>
				<NuxtLink to="/apps/" class="back-link">← Back to Apps</NuxtLink>
				<h1 class="nfb-title">Not Following Back</h1>
				<p class="nfb-subtitle">
					Easily find out who isn't following you back on
					<span class="instagram-text">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="instagram-icon">
							<defs>
								<linearGradient id="ig-grad" x1="0%" y1="100%" x2="100%" y2="0%">
									<stop offset="0%" stop-color="#f09433"/>
									<stop offset="50%" stop-color="#dc2743"/>
									<stop offset="100%" stop-color="#bc1888"/>
								</linearGradient>
							</defs>
							<rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke="url(#ig-grad)"></rect>
							<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" stroke="url(#ig-grad)"></path>
							<line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke="url(#ig-grad)"></line>
						</svg>
						Instagram
					</span>
					by comparing your exported data.
				</p>
			</header>

			<ClientOnly>
				<main class="nfb-main">
					<div v-if="error" class="error-message panel-card animate-fade-in">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
						<span>{{ error }}</span>
						<button @click="error = null" class="btn btn-secondary">Dismiss</button>
					</div>

					<div v-if="!results" class="upload-section animate-fade-in">
						<AppsFileUploader
							title="Upload Data"
							description="Drop your followers_1.json and following.json files, or just upload the entire .zip export."
							@files-selected="onFilesSelected"
						/>
					</div>

					<div v-if="!results && selectedFiles.length > 0" class="actions-section animate-fade-in">
						<button
							@click="processFiles"
							class="btn btn-primary process-btn"
							:disabled="isProcessing"
						>
							<span v-if="isProcessing">Processing...</span>
							<span v-else>Check Who is Not Following Back</span>
						</button>
						<button @click="reset" class="btn btn-secondary">Reset</button>
					</div>

					<div
						v-if="!results"
						class="instructions-section panel-card animate-fade-in"
						:class="{ 'is-open': instructionsOpen }"
					>
						<button
							type="button"
							class="instructions-header"
							:aria-expanded="instructionsOpen"
							aria-controls="instructions-panel"
							@click="instructionsOpen = !instructionsOpen"
						>
							<div class="instructions-header-text">
								<h3 class="instructions-title">How to get your data</h3>
								<p class="instructions-subtitle">Export your Instagram followers and following from Meta's Accounts Center, then upload the file above.</p>
							</div>
							<span class="accordion-icon" aria-hidden="true"></span>
						</button>
						<div
							id="instructions-panel"
							class="instructions-panel"
							:aria-hidden="!instructionsOpen"
						>
							<div class="instructions-panel-inner">
								<div class="instructions-steps">
									<div
										v-for="(step, index) in instructionSteps"
										:key="step.id"
										class="instruction-step"
									>
										<div class="step-header">
											<span class="step-badge">{{ index + 1 }}</span>
											<span class="step-title">{{ step.title }}</span>
										</div>
										<div class="step-content">
											<p v-if="index === 0">Open the Accounts Center and go to <strong>Your information and permissions &gt; Download your information</strong>.</p>
											<p v-else-if="index === 1">Click <strong>Download or transfer information</strong> and select your Instagram profile.</p>
											<p v-else-if="index === 2">Choose <strong>Some of your information</strong> and select <strong>Followers and following</strong>.</p>
											<template v-else-if="index === 3">
												<p>Choose <strong>Download to device</strong>.</p>
												<div class="important-note">
													<strong>Important:</strong> Set Date range to <span class="highlight">All time</span> and Format to <span class="highlight">JSON</span>. The HTML format will not work.
												</div>
											</template>
											<p v-else>Click <strong>Create files</strong>. Once Meta notifies you that it's ready, download the <code>.zip</code> file and drop it above.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div v-if="results" class="results-section">
						<div class="actions-section right animate-fade-in" style="margin-bottom: 1rem;">
							<button @click="reset" class="btn btn-secondary">Start Over</button>
						</div>
						<AppsUserList :users="results" />
					</div>
				</main>
			</ClientOnly>
		</div>
	</div>
</template>

<script setup>
import { ref } from 'vue';
import JSZip from 'jszip';

const { isDark, toggleTheme } = useTheme();

const selectedFiles = ref([]);
const isProcessing = ref(false);
const error = ref(null);
const results = ref(null);
const instructionsOpen = ref(false);

const instructionSteps = [
	{ id: 'accounts-center', title: 'Open Accounts Center' },
	{ id: 'download-request', title: 'Download or transfer information' },
	{ id: 'select-data', title: 'Select followers and following' },
	{ id: 'download-format', title: 'Choose download format' },
	{ id: 'create-files', title: 'Create and download files' }
];

useHead({
	title: 'Not Following Back - James Daniel Enovero',
	meta: [
		{ name: 'description', content: 'Find out who is not following you back on Instagram. Upload your exported data — everything runs locally in your browser.' }
	]
});

const onFilesSelected = (files) => {
	selectedFiles.value = files;
	error.value = null;
};

const readFileContent = (file) => {
	return new Promise((resolve, reject) => {
		const reader = new FileReader();
		reader.onload = (e) => resolve(e.target.result);
		reader.onerror = () => reject(new Error('Failed to read file'));
		reader.readAsText(file);
	});
};

const extractUsers = (data) => {
	const usersMap = new Map();

	const traverse = (obj) => {
		if (Array.isArray(obj)) {
			obj.forEach(traverse);
		} else if (obj !== null && typeof obj === 'object') {
			if (obj.string_list_data && Array.isArray(obj.string_list_data)) {
				obj.string_list_data.forEach(item => {
					let username = item.value || obj.title;
					if (!username && item.href) {
						try {
							const url = new URL(item.href);
							username = url.pathname.replace('/_u/', '/').split('/').filter(Boolean).pop();
						} catch (e) {}
					}
					if (username && item.href) {
						usersMap.set(username, {
							username,
							url: item.href,
							timestamp: item.timestamp || 0
						});
					}
				});
			} else {
				Object.values(obj).forEach(traverse);
			}
		}
	};

	traverse(data);
	return Array.from(usersMap.values());
};

const extractUsersFromHtml = (htmlString) => {
	const usersMap = new Map();
	const parser = new DOMParser();
	const doc = parser.parseFromString(htmlString, 'text/html');
	const links = doc.querySelectorAll('a[href*="instagram.com"]');

	links.forEach(link => {
		let username = link.textContent.trim();
		let url = link.href;

		if (!username || username.startsWith('http')) {
			try {
				const urlObj = new URL(url);
				username = urlObj.pathname.replace('/_u/', '/').split('/').filter(Boolean).pop();
			} catch (e) {}
		}

		if (username && url) {
			let timestamp = 0;
			const timeDiv = link.parentElement?.nextElementSibling;
			if (timeDiv && timeDiv.textContent) {
				timestamp = Math.floor(Date.parse(timeDiv.textContent) / 1000) || 0;
			}
			usersMap.set(username.toLowerCase(), { username, url, timestamp });
		}
	});

	return Array.from(usersMap.values());
};

const processFiles = async () => {
	if (selectedFiles.value.length === 0) return;

	isProcessing.value = true;
	error.value = null;

	try {
		let followersList = [];
		let followingList = [];
		let followersFound = false;
		let followingFound = false;

		const zipFiles = selectedFiles.value.filter(f => f.name.endsWith('.zip'));
		if (zipFiles.length > 0) {
			const zip = new JSZip();
			const zipContent = await zip.loadAsync(zipFiles[0]);

			for (const [relativePath, zipEntry] of Object.entries(zipContent.files)) {
				if (!zipEntry.dir && (relativePath.endsWith('.json') || relativePath.endsWith('.html'))) {
					const fileName = relativePath.split('/').pop().toLowerCase();
					const isHtml = fileName.endsWith('.html');

					if (fileName.includes('followers') && fileName.includes('1')) {
						const content = await zipEntry.async('string');
						followersList = isHtml ? extractUsersFromHtml(content) : extractUsers(JSON.parse(content));
						followersFound = true;
					} else if (fileName.includes('following') && !fileName.includes('followers')) {
						const content = await zipEntry.async('string');
						followingList = isHtml ? extractUsersFromHtml(content) : extractUsers(JSON.parse(content));
						followingFound = true;
					}
				}
			}
		} else {
			for (const file of selectedFiles.value) {
				if (file.name.endsWith('.json') || file.name.endsWith('.html')) {
					const fileName = file.name.split('/').pop().toLowerCase();
					const isHtml = fileName.endsWith('.html');

					if (fileName.includes('followers') && fileName.includes('1')) {
						const content = await readFileContent(file);
						followersList = isHtml ? extractUsersFromHtml(content) : extractUsers(JSON.parse(content));
						followersFound = true;
					} else if (fileName.includes('following') && !fileName.includes('followers')) {
						const content = await readFileContent(file);
						followingList = isHtml ? extractUsersFromHtml(content) : extractUsers(JSON.parse(content));
						followingFound = true;
					}
				}
			}
		}

		if (!followersFound) {
			throw new Error('Could not find followers file. Ensure you uploaded followers_1.json/.html or a zip containing it.');
		}
		if (!followingFound) {
			throw new Error('Could not find following file. Ensure you uploaded following.json/.html or a zip containing it.');
		}
		if (followersList.length === 0) {
			throw new Error('Found 0 followers in the followers file. The file might have an unexpected format or you have 0 followers.');
		}
		if (followingList.length === 0) {
			throw new Error('Found 0 following in the following file. The file might have an unexpected format or you are not following anyone.');
		}

		const followersSet = new Set(followersList.map(u => u.username.toLowerCase()));
		results.value = followingList.filter(u => !followersSet.has(u.username.toLowerCase()));
	} catch (err) {
		error.value = err.message;
	} finally {
		isProcessing.value = false;
	}
};

const reset = () => {
	selectedFiles.value = [];
	results.value = null;
	error.value = null;
	isProcessing.value = false;
};
</script>

<style scoped>
.nfb-page {
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

.nfb-container {
	position: relative;
	z-index: 1;
	max-width: 900px;
	margin: 0 auto;
	padding: 40px 20px;
}

.nfb-header {
	text-align: center;
	background: var(--panel-bg);
	margin-bottom: 30px;
	padding: 50px 40px;
	border-radius: 15px;
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
	border: 1px solid var(--border-color);
	position: relative;
	animation: fadeInDown 0.6s ease-out;
}

@keyframes fadeInDown {
	from { opacity: 0; transform: translateY(-20px); }
	to { opacity: 1; transform: translateY(0); }
}

.back-link {
	display: inline-block;
	font: normal 14px monospace;
	color: var(--header-text);
	margin-bottom: 25px;
	transition: all 0.3s;
	text-decoration: none;
	text-align: left;
	width: 100%;
}

.back-link:hover {
	opacity: 0.85;
	transform: translateX(-5px);
}

.nfb-title {
	font: bold 36px monospace;
	color: var(--header-text);
	margin-bottom: 15px;
}

.nfb-subtitle {
	font: normal 16px monospace;
	color: var(--header-text);
	opacity: 0.88;
	max-width: 600px;
	margin: 0 auto;
	line-height: 1.6;
}

.instagram-text {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	font-weight: 700;
	background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
	background-clip: text;
	color: transparent;
}

.instagram-icon {
	flex-shrink: 0;
	position: relative;
	top: -1px;
}

.nfb-main {
	background: var(--panel-bg);
	padding: 50px 40px;
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
	border: 1px solid var(--border-color);
	border-radius: 15px;
	animation: fadeIn-49f87554 0.6s ease-out 0.2s both;
}

@keyframes fadeIn {
	from { opacity: 0; }
	to { opacity: 1; }
}

.panel-card {
	background: var(--panel-bg);
	border-radius: 12px;
	padding: 1.5rem 2rem;
	box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
	border: 1px solid var(--border-color);
}

.upload-section {
	display: flex;
	justify-content: center;
	margin-bottom: 2rem;
	margin-left: auto;
	margin-right: auto;
	width: 100%;
}

.actions-section {
	display: flex;
	justify-content: center;
	gap: 1rem;
	margin-bottom: 2rem;
	flex-wrap: wrap;
}

.actions-section.right {
	justify-content: flex-end;
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

.btn-primary:hover:not(:disabled) {
	transform: translateY(-2px);
	box-shadow: 0 4px 12px var(--toggle-shadow);
}

.btn-primary:disabled {
	opacity: 0.5;
	cursor: not-allowed;
}

.btn-secondary {
	background: var(--input-bg);
	color: var(--header-text);
	border: 2px solid var(--border-color);
}

.btn-secondary:hover {
	border-color: var(--header-text);
	transform: translateY(-2px);
}

.process-btn {
	font-size: 1rem;
	padding: 0.75rem 2rem;
}

.error-message {
	display: flex;
	align-items: center;
	gap: 1rem;
	background: var(--error-bg);
	border-color: var(--error-border);
	color: var(--header-text);
	margin-bottom: 2rem;
	flex-wrap: wrap;
	font: normal 0.95rem monospace;
}

.error-message svg {
	flex-shrink: 0;
	color: var(--error-color);
}

.instructions-section {
	margin: 0 auto;
	padding: 0;
	overflow: hidden;
}

.instructions-section.panel-card {
	border-top: 3px solid var(--toggle-bg);
}

.instructions-header {
	width: 100%;
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	gap: 1rem;
	padding: 1.75rem 2rem;
	border: none;
	border-bottom: 1px solid transparent;
	background: var(--surface-color);
	cursor: pointer;
	text-align: left;
	transition: background-color 0.3s, border-color 0.3s;
}

.instructions-section.is-open .instructions-header {
	border-bottom-color: var(--border-color);
}

.instructions-header:hover {
	background: var(--surface-hover);
}

.instructions-header-text {
	flex: 1;
	min-width: 0;
}

.instructions-title {
	font: bold 1.25rem monospace;
	color: var(--header-text);
	margin-bottom: 0.5rem;
}

.instructions-subtitle {
	font: normal 0.9rem monospace;
	color: var(--text-secondary);
	line-height: 1.5;
	margin: 0;
}

.accordion-icon {
	width: 0.55rem;
	height: 0.55rem;
	border-right: 2px solid var(--header-text);
	border-bottom: 2px solid var(--header-text);
	transform: rotate(45deg);
	transition: transform 0.3s ease;
	flex-shrink: 0;
	margin-top: 0.45rem;
}

.instructions-section.is-open .accordion-icon {
	transform: rotate(-135deg);
}

.instructions-panel {
	display: grid;
	grid-template-rows: 0fr;
	transition: grid-template-rows 0.3s ease;
}

.instructions-section.is-open .instructions-panel {
	grid-template-rows: 1fr;
}

.instructions-panel-inner {
	overflow: hidden;
}

.instructions-steps {
	padding: 1.25rem 2rem 2rem;
	display: flex;
	flex-direction: column;
	gap: 0.85rem;
}

.instruction-step {
	border: 1px solid var(--border-color);
	border-radius: 10px;
	background: var(--input-bg);
	overflow: hidden;
}

.step-header {
	display: flex;
	align-items: center;
	gap: 0.85rem;
	padding: 0.85rem 1.1rem;
	border-bottom: 1px solid var(--border-color);
	background: var(--surface-color);
}

.step-badge {
	width: 1.75rem;
	height: 1.75rem;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	font: bold 0.8rem monospace;
	color: var(--toggle-slider-bg);
	background: var(--toggle-bg);
	border-radius: 50%;
	flex-shrink: 0;
}

.step-title {
	font: bold 0.92rem monospace;
	color: var(--header-text);
	line-height: 1.4;
}

.step-content {
	font: normal 0.92rem monospace;
	color: var(--header-text);
	line-height: 1.65;
	padding: 1rem 1.1rem;
}

.step-content p {
	margin: 0;
}

.step-content p + .important-note {
	margin-top: 0.75rem;
}

.step-content strong {
	font-weight: 700;
}

.step-content code {
	background: var(--card-bg);
	padding: 0.15rem 0.45rem;
	border-radius: 4px;
	color: var(--header-text);
	border: 1px solid var(--border-color);
	font-size: 0.88em;
}

.important-note {
	margin-top: 0.75rem;
	margin-left: 0;
	padding: 0.85rem 1rem 0.85rem 2.75rem;
	background: var(--error-bg);
	border: 1px solid var(--error-border);
	border-left: 4px solid var(--error-color);
	border-radius: 8px;
	font: normal 0.88rem monospace;
	line-height: 1.55;
	text-align: left;
	color: var(--header-text);
	position: relative;
}

.important-note::before {
	content: '!';
	position: absolute;
	left: 0.85rem;
	top: 0.85rem;
	width: 1.35rem;
	height: 1.35rem;
	display: flex;
	align-items: center;
	justify-content: center;
	font: bold 0.75rem monospace;
	color: var(--error-color);
	background: var(--panel-bg);
	border: 1px solid var(--error-border);
	border-radius: 50%;
}

.important-note strong {
	color: var(--error-color);
	text-transform: uppercase;
	font-size: 0.8rem;
	letter-spacing: 0.04em;
}

.highlight {
	background: var(--toggle-bg);
	color: var(--toggle-slider-bg);
	padding: 0.15rem 0.45rem;
	border-radius: 4px;
	font-weight: 700;
	font-size: 0.75rem;
	text-transform: uppercase;
	letter-spacing: 0.05em;
	margin: 0 0.15rem;
	white-space: nowrap;
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

.toggle-track {
	width: 100%;
	height: 100%;
	background: var(--toggle-bg);
	border-radius: 20px;
	display: flex;
	align-items: center;
	justify-content: flex-start;
	box-shadow: 0 2px 5px var(--toggle-shadow);
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
}

.animate-fade-in {
	animation: fadeInUp 0.4s ease forwards;
}

@keyframes fadeInUp {
	from { opacity: 0; transform: translateY(10px); }
	to { opacity: 1; transform: translateY(0); }
}

@media only screen and (max-width: 768px) {
	.nfb-container {
		padding: 20px 15px;
	}

	.nfb-header {
		padding: 35px 25px;
	}

	.nfb-title {
		font-size: 28px;
	}

	.nfb-subtitle {
		font-size: 14px;
	}

	.nfb-main {
		padding: 35px 25px;
	}

	.instructions-header {
		padding: 1.35rem 1.25rem;
	}

	.instructions-steps {
		padding: 1rem 1.25rem 1.25rem;
		gap: 0.75rem;
	}

	.step-header {
		padding: 0.75rem 0.9rem;
	}

	.step-content {
		padding: 0.9rem;
		font-size: 0.88rem;
	}
}
</style>
