import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const CONTENT_DIR = path.join(__dirname, '..', 'content', 'blog');
const OUTPUT_FILE = path.join(__dirname, '..', 'public', 'blog-posts.json');

// Parse frontmatter from markdown file
function parseFrontmatter(content) {
	const frontmatterRegex = /^---\s*\n([\s\S]*?)\n---\s*\n/;
	const match = content.match(frontmatterRegex);
	
	if (!match) {
		return null;
	}

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

// Get all blog posts
function getBlogPosts() {
	const posts = [];

	try {
		const files = fs.readdirSync(CONTENT_DIR);
		
		for (const file of files) {
			if (file.endsWith('.md')) {
				const filePath = path.join(CONTENT_DIR, file);
				const content = fs.readFileSync(filePath, 'utf-8');
				const meta = parseFrontmatter(content);

				if (meta && meta.title && meta.date) {
					// Extract slug from filename (remove .md extension)
					const slug = file.replace('.md', '');
					
					posts.push({
						_path: `/blog/${slug}/`,
						title: meta.title,
						description: meta.description || '',
						date: meta.date,
						tags: meta.tags || []
					});
				}
			}
		}

		// Sort by date (newest first)
		posts.sort((a, b) => new Date(b.date) - new Date(a.date));

		return posts;
	} catch (error) {
		console.error('Error reading blog posts:', error);
		return [];
	}
}

// Main function
function updateBlogJson() {
	console.log('Scanning blog posts...');
	const posts = getBlogPosts();
	
	console.log(`Found ${posts.length} blog post(s)`);
	
	// Write to JSON file
	fs.writeFileSync(OUTPUT_FILE, JSON.stringify(posts, null, 2), 'utf-8');
	
	console.log(`✓ Updated ${OUTPUT_FILE}`);
	console.log('\nPosts included:');
	posts.forEach((post, index) => {
		console.log(`  ${index + 1}. ${post.title} (${post.date})`);
	});
}

// Run the script
updateBlogJson();
