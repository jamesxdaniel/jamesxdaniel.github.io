import { ref, onMounted } from 'vue';

export function useTheme() {
	const isDark = ref(false);

	onMounted(() => {
		const theme = document.documentElement.getAttribute('data-theme')
			|| localStorage.getItem('theme')
			|| (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
		isDark.value = theme === 'dark';
	});

	function toggleTheme() {
		isDark.value = !isDark.value;
		const theme = isDark.value ? 'dark' : 'light';
		document.documentElement.setAttribute('data-theme', theme);
		localStorage.setItem('theme', theme);
	}

	return { isDark, toggleTheme };
}
