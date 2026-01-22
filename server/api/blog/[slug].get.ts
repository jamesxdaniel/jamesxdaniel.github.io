import { readFile } from 'fs/promises';
import { join } from 'path';

export default defineEventHandler(async (event) => {
	try {
		const slug = getRouterParam(event, 'slug');
		if (!slug) {
			throw createError({ statusCode: 400, message: 'Slug is required' });
		}

		// Read the markdown file
		const filePath = join(process.cwd(), 'content', 'blog', `${slug}.md`);
		const content = await readFile(filePath, 'utf-8');

		// Parse frontmatter (simple parser)
		const frontmatterRegex = /^---\s*\n([\s\S]*?)\n---\s*\n([\s\S]*)$/;
		const match = content.match(frontmatterRegex);

		if (!match) {
			return { body: content, title: '', description: '', date: '', tags: [] };
		}

		const [, frontmatter, body] = match;
		const meta: Record<string, any> = {};

		// Parse frontmatter lines
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

		return {
			body: body.trim(),
			title: meta.title || '',
			description: meta.description || '',
			date: meta.date || '',
			tags: meta.tags || []
		};
	} catch (error: any) {
		console.error('Error reading blog post:', error);
		throw createError({
			statusCode: 404,
			message: 'Blog post not found'
		});
	}
});
