import { ref, onMounted } from 'vue';

export function useTheme() {
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

	return { isDark, toggleTheme };
}
