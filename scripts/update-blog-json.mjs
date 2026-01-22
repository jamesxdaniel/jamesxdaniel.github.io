import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

function parseFrontmatter(content) {
	const frontmatterRegex = /^---\s*\n([\s\S]*?)\n---/;
	const match = content.match(frontmatterRegex);
	
	if (!match) return null;
	
	const frontmatter = match[1];
	const meta = {};
	
	frontmatter.split('\n').forEach(line => {
		const [key, ...valueParts] = line.split(':');
		if (key && valueParts.length) {
			const value = valueParts.join(':').trim();
			// Handle arrays (tags)
			if (value.startsWith('[') && value.endsWith(']')) {
				meta[key.trim()] = value
					.slice(1, -1)
					.split(',')
					.map(v => v.trim().replace(/['"]/g, ''));
			} else {
				meta[key.trim()] = value.replace(/['"]/g, '');
			}
		}
	});
	
	return meta;
}

function updateBlogPostsJson() {
	const contentDir = path.join(__dirname, '..', 'content', 'blog');
	const outputPath = path.join(__dirname, '..', 'public', 'blog-posts.json');
	
	// Check if content directory exists
	if (!fs.existsSync(contentDir)) {
		console.error('Content directory not found:', contentDir);
		process.exit(1);
	}
	
	// Read all markdown files
	const files = fs.readdirSync(contentDir)
		.filter(file => file.endsWith('.md'))
		.sort()
		.reverse(); // Newest first
	
	const posts = [];
	
	for (const file of files) {
		const filepath = path.join(contentDir, file);
		const content = fs.readFileSync(filepath, 'utf-8');
		const meta = parseFrontmatter(content);
		
		if (meta) {
			const slug = file.replace('.md', '');
			posts.push({
				_path: `/blog/${slug}/`, // Add trailing slash
				title: meta.title || '',
				description: meta.description || '',
				date: meta.date || '',
				tags: meta.tags || []
			});
		}
	}
	
	// Sort by date (newest first)
	posts.sort((a, b) => new Date(b.date) - new Date(a.date));
	
	// Write to public/blog-posts.json
	fs.writeFileSync(outputPath, JSON.stringify(posts, null, 2), 'utf-8');
	
	console.log(`✅ Successfully updated blog-posts.json with ${posts.length} posts`);
	console.log(`📝 Output: ${outputPath}`);
}

// Run the updater
updateBlogPostsJson();
