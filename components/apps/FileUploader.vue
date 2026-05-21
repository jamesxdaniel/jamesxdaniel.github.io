<template>
	<div
		class="file-uploader"
		:class="{ 'is-dragover': isDragover }"
		@dragover.prevent="onDragOver"
		@dragleave.prevent="onDragLeave"
		@drop.prevent="onDrop"
		@click="triggerFileInput"
	>
		<input
			type="file"
			ref="fileInput"
			class="hidden-input"
			accept=".json,.html,.zip"
			multiple
			@change="onFileSelect"
		/>
		<div class="uploader-content">
			<svg class="upload-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
				<polyline points="17 8 12 3 7 8"></polyline>
				<line x1="12" y1="3" x2="12" y2="15"></line>
			</svg>
			<h3 class="upload-title">{{ title }}</h3>
			<p class="upload-desc">{{ description }}</p>

			<div v-if="fileNames.length > 0" class="selected-files">
				<div v-for="name in fileNames" :key="name" class="selected-file">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
					<span>{{ name }}</span>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
	title: {
		type: String,
		default: 'Upload Files'
	},
	description: {
		type: String,
		default: 'Drag & drop your .zip or multiple .json/.html files here'
	}
});

const emit = defineEmits(['files-selected']);

const fileInput = ref(null);
const isDragover = ref(false);
const fileNames = ref([]);

const triggerFileInput = () => {
	fileInput.value.click();
};

const onDragOver = () => {
	isDragover.value = true;
};

const onDragLeave = () => {
	isDragover.value = false;
};

const onDrop = (e) => {
	isDragover.value = false;
	const files = Array.from(e.dataTransfer.files);
	if (files.length > 0) {
		handleFiles(files);
	}
};

const onFileSelect = (e) => {
	const files = Array.from(e.target.files);
	if (files.length > 0) {
		handleFiles(files);
	}
};

const handleFiles = (files) => {
	const validFiles = files.filter(f =>
		f.type === 'application/json' ||
		f.type === 'text/html' ||
		f.type === 'application/zip' ||
		f.type === 'application/x-zip-compressed' ||
		f.name.endsWith('.json') ||
		f.name.endsWith('.html') ||
		f.name.endsWith('.zip')
	);

	if (validFiles.length > 0) {
		fileNames.value = validFiles.map(f => f.name);
		emit('files-selected', validFiles);
	} else {
		alert('Please upload a valid .zip file or .json/.html files.');
	}
};
</script>

<style scoped>
.file-uploader {
	background: var(--surface-color);
	border: 2px dashed var(--surface-border);
	border-radius: 12px;
	padding: 3rem 2rem;
	text-align: center;
	cursor: pointer;
	transition: all 0.3s ease;
	position: relative;
	overflow: hidden;
	width: 100%;
	height: 100%;
	display: flex;
	align-items: center;
	justify-content: center;
	box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.file-uploader:hover,
.file-uploader.is-dragover {
	border-color: var(--header-text);
	background: var(--surface-hover);
}

.file-uploader.is-dragover {
	transform: scale(1.02);
}

.hidden-input {
	display: none;
}

.uploader-content {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 1rem;
}

.upload-icon {
	width: 48px;
	height: 48px;
	color: var(--header-text);
	margin-bottom: 0.5rem;
	transition: transform 0.3s ease;
}

.file-uploader:hover .upload-icon {
	transform: translateY(-5px);
}

.upload-title {
	font: bold 1.25rem monospace;
	color: var(--header-text);
}

.upload-desc {
	font: normal 0.9rem monospace;
	color: var(--header-text);
	opacity: 0.88;
}

.selected-files {
	margin-top: 1rem;
	display: flex;
	flex-direction: column;
	gap: 0.5rem;
	max-width: 100%;
}

.selected-file {
	display: flex;
	align-items: center;
	gap: 0.5rem;
	background: var(--card-bg);
	padding: 0.5rem 1rem;
	border-radius: 8px;
	font: normal 0.9rem monospace;
	color: var(--header-text);
	border: 1px solid var(--border-color);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
</style>
