import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const CONTENT_DIR = path.join(__dirname, '..', 'content', 'blog');
const OUTPUT_FILE = path.join(__dirname, '..', 'public', 'blog-posts.json');
const POSTS_DIR = path.join(__dirname, '..', 'public', 'blog-posts');

// Parse frontmatter and body from markdown file
function parseMarkdown(content) {
	const frontmatterRegex = /^---\s*\n([\s\S]*?)\n---\s*\n([\s\S]*)$/;
	const match = content.match(frontmatterRegex);

	if (!match) {
		return null;
	}

	const [, frontmatter, body] = match;
	const meta = {};

	frontmatter.split('\n').forEach(line => {
		const [key, ...valueParts] = line.split(':');
		if (key && valueParts.length) {
			const value = valueParts.join(':').trim();
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

	return { meta, body: body.trim() };
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
				const parsed = parseMarkdown(content);

				if (parsed?.meta?.title && parsed?.meta?.date) {
					const slug = file.replace('.md', '');
					const { meta, body } = parsed;

					posts.push({
						_path: `/blog/${slug}/`,
						slug,
						title: meta.title,
						description: meta.description || '',
						date: meta.date,
						tags: meta.tags || [],
						body
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

	if (!fs.existsSync(POSTS_DIR)) {
		fs.mkdirSync(POSTS_DIR, { recursive: true });
	}

	for (const file of fs.readdirSync(POSTS_DIR)) {
		if (file.endsWith('.json')) {
			fs.unlinkSync(path.join(POSTS_DIR, file));
		}
	}

	const indexPosts = posts.map(({ body, slug, ...post }) => post);

	fs.writeFileSync(OUTPUT_FILE, JSON.stringify(indexPosts, null, 2), 'utf-8');
	console.log(`✓ Updated ${OUTPUT_FILE}`);

	for (const post of posts) {
		const { body, slug, ...meta } = post;
		const postFile = path.join(POSTS_DIR, `${slug}.json`);
		fs.writeFileSync(postFile, JSON.stringify({ ...meta, body }, null, 2), 'utf-8');
	}

	console.log(`✓ Updated ${posts.length} post file(s) in ${POSTS_DIR}`);
	console.log('\nPosts included:');
	posts.forEach((post, index) => {
		console.log(`  ${index + 1}. ${post.title} (${post.date})`);
	});
}

// Run the script
updateBlogJson();
